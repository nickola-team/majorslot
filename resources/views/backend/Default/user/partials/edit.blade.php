<div class="box-body box-profile">


    <div class="form-group">
        <label>@lang('app.role')</label>
        <?php  
            $available_roles = Auth::user()->available_roles( true );
            $available_roles_trans = [];
            foreach ($available_roles as $key=>$role)
            {
                $role = \VanguardLTE\Role::find($key)->description;
                $available_roles_trans[$key] = $role;
            }
        ?>

        {!! Form::select('role_id', $available_roles_trans, $edit ? $user->role_id : '',
            ['class' => 'form-control', 'id' => 'role_id', 'disabled' => true]) !!}
    </div>

    <div class="form-group">
        <label>@lang('app.shops')</label>
        {!! Form::select('shops[]', $shops, ($edit && $user->hasRole(['admin', 'agent', 'distributor'])) ? $user->shops(true) : Auth::user()->shop_id,
            ['class' => 'form-control', 'id' => 'shops', ($edit) ? 'disabled' : '', ($edit && $user->hasRole(['agent','distributor'])) ? 'multiple' : '']) !!}
    </div>
    @if(auth()->user()->hasRole('admin') && $user->hasRole('comaster'))
    <div class="form-group">
        <label>머니퍼센트</label>
        <input type="number" step="0.01" class="form-control" id="money_percent" name="money_percent" value="0">
    </div>
    @endif
    @if($user->hasRole(['master','agent','distributor','user']) || (auth()->user()->hasRole('admin') && $user->hasRole('comaster')))
        <div class="form-group">
            <label>딜비%</label>
            <input type="text" class="form-control" id="deal_percent" name="deal_percent" placeholder="(@lang('app.optional'))" value="{{ $edit ? $user->deal_percent : '' }}" {{$user->id == auth()->user()->id?'disabled':''}}>
        </div>
        <div class="form-group">
            <label>라이브딜비%</label>
            <input type="text" class="form-control" id="table_deal_percent" name="table_deal_percent" placeholder="(@lang('app.optional'))" value="{{ $edit ? $user->table_deal_percent : '' }}" {{$user->id == auth()->user()->id?'disabled':''}}>
        </div>
        @if (!$user->hasRole('user'))
        <div class="form-group">
            <label>죽장%</label>
            <input type="text" class="form-control" id="ggr_percent" name="ggr_percent" placeholder="(@lang('app.optional'))" value="{{ $edit ? $user->ggr_percent : '' }}" {{$user->id == auth()->user()->id?'disabled':''}}>
        </div>
        <div class="form-group">
            <label>정산기간</label>
            {!! Form::select('reset_days', \VanguardLTE\User::$values['reset_days'], $edit ? $user->reset_days : '' ,
            ['class' => 'form-control', 'id' => 'reset_days', 'disabled' => ($user->hasRole(['master']) && $user->id != auth()->user()->id) ? false: true]) !!}
        </div>
        @endif
    @endif


    <div class="form-group">
        <label>@lang('app.status')</label>
        {!! Form::select('status', $statuses, $edit ? $user->status : '' ,
            ['class' => 'form-control', 'id' => 'status', 'disabled' => ($user->hasRole(['admin']) || $user->id == auth()->user()->id) ? true: false]) !!}
    </div>

    <div class="form-group">
        <label>@lang('app.username')</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="(@lang('app.optional'))" value="{{ $edit ? $user->username : '' }}">
    </div>
    <div class="form-group">
        <label>전화번호</label>
        <input type="text" class="form-control" id="phone" name="phone"  value="{{ $edit ? $user->phone : '' }}">
    </div>

    @if( $user->email != '' )
    <div class="form-group">
        <label>@lang('app.email')</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="(@lang('app.optional'))" value="{{ $edit ? $user->email : '' }}">
    </div>
    @endif
    @if (auth()->user()->isInOutPartner())
    <div class="form-group">
        <label>메모</label>
        @if ($edit && $user->memo)
        <p> 작성날짜 : {{$user->memo->created_at}} 저장날짜 : {{$user->memo->updated_at}}</p>
        @endif
        <textarea id="memo" name="memo" class="form-control" rows="5">{{($edit && $user->memo)?$user->memo->memo:''}}</textarea>
    </div>
    @endif
    
    @if (!$user->isInoutPartner() && $user->id == auth()->user()->id)
    <div class="form-group">
        <label>이전 환전비밀번호</label>
        <input type="password" class="form-control" id="old_confirmation_token" name="old_confirmation_token" >
    </div>
    <div class="form-group">
        <label>새 환전비밀번호</label>
        <input type="password" class="form-control" id="confirmation_token" placeholder="@lang('app.leave_blank_if_you_dont_want_to_change')" name="confirmation_token" >
    </div>
    <div class="form-group">
        <label>새 환전비밀번호 확인</label>
        <input type="password" class="form-control" id="confirmation_token_confirmation" placeholder="@lang('app.leave_blank_if_you_dont_want_to_change')" name="confirmation_token_confirmation" >
    </div>
    @endif

    <div class="form-group">
        <label>{{ $edit ? trans("app.new_password") : trans('app.password') }}</label>
        <input type="password" class="form-control" id="password" name="password" @if ($edit) placeholder="@lang('app.leave_blank_if_you_dont_want_to_change')" @endif>
    </div>

    <div class="form-group">
        <label>{{ $edit ? trans("app.confirm_new_password") : trans('app.confirm_password') }}</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" @if ($edit) placeholder="@lang('app.leave_blank_if_you_dont_want_to_change')" @endif>
    </div>

</div>

<div class="box-footer">
    <button type="submit" class="btn btn-primary" id="update-details-btn">
        확인
    </button>
@if (auth()->user()->isInOutPartner())
    <a href="{{route($admurl.'.user.update.resetpwd', $user->id)}}">
    <button type="button" class="btn btn-danger" id="reset-confirmation-token-btn">
        환전비번 리셋
    </button>
    </a>
@endif    
</div>
