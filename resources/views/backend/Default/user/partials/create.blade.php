<div class="col-md-6">
    <div class="form-group">
        @if( Auth::user()->hasRole('cashier') || Auth::user()->hasRole('manager'))
        <label>회원이름</label>
        @else
        <label>파트너이름</label>
        @endif
        <input type="text" class="form-control" id="username" name="username" value="">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.status')</label>
        {!! Form::select('status', $statuses, '',
            ['class' => 'form-control', 'id' => 'status', '']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.role')</label>
        <?php  
            $available_roles = Auth::user()->available_roles(  );
            $available_roles_trans = [];
            foreach ($available_roles as $key=>$role)
            {
                $role = \VanguardLTE\Role::find($key)->description;
                $available_roles_trans[$key] = $role;
            }
        ?>
        {!! Form::select('role_id', $available_roles_trans, '',
            ['class' => 'form-control', 'id' => 'role_id', '']) !!}
    </div>
</div>
@if(auth()->user()->hasRole('admin'))
<div class="col-md-6">
    <div class="form-group">
    <label>머니퍼센트</label>
    <input type="number" step="0.01" class="form-control" id="money_percent" name="money_percent" value="0">
    </div>
</div>
@endif
@if(auth()->user()->hasRole(['comaster','master','agent','manager']))
<div class="col-md-6">
    <div class="form-group">
    <label>딜비%<span class="text-red">{{auth()->user()->hasRole('manager')?'(일반 회원들에게 딜비를 적용하려면 입력하세요.)':''}}</span></label>
    <input type="number" step="0.01" class="form-control" id="deal_percent" name="deal_percent" value="0">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
    <label>라이브딜비%<span class="text-red">{{auth()->user()->hasRole('manager')?'(일반 회원들에게 라이브딜비를 적용하려면 입력하세요.)':''}}</span></label>
    <input type="number" step="0.01" class="form-control" id="table_deal_percent" name="table_deal_percent" value="0">
    </div>
</div>
@if (!auth()->user()->hasRole('manager'))
<div class="col-md-6">
    <div class="form-group">
    <label>죽장%</label>
    <input type="number" step="0.01" class="form-control" id="ggr_percent" name="ggr_percent" value="0">
    </div>
</div>
@endif
@if(auth()->user()->hasRole(['comaster']))
<div class="col-md-6">
    <div class="form-group">
        <label>정산기간</label>
        {!! Form::select('reset_days', \VanguardLTE\User::$values['reset_days'], '' ,
        ['class' => 'form-control', 'id' => 'reset_days']) !!}
    </div>
</div>
@endif
@endif

@if(auth()->user()->hasRole(['distributor']))
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('app.shops')</label>
            @if( auth()->user()->hasRole(['admin', 'agent']) )
                {!! Form::select('shop_id', ['0' => '---'] + $shops, '0', ['class' => 'form-control', 'id' => 'shops']) !!}
            @else
                {!! Form::select('shop_id', $shops, '0', ['class' => 'form-control', 'id' => 'shops']) !!}
            @endif
        </div>
    </div>
@endif
@if( auth()->user()->hasRole(['manager', 'cashier']) )
    <input type="hidden" name="shop_id" value="{{ auth()->user()->shop_id }}">
@endif

@if( auth()->user()->hasRole(['cashier']) )
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ trans('app.balance') }}</label>
            <input type="text" class="form-control" id="balance" name="balance" value="0">
        </div>
    </div>
@endif
<div class="col-md-6">
    <div class="form-group">
        <label>{{ trans('app.password') }}</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>{{ trans('app.confirm_password') }}</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
    </div>
</div>