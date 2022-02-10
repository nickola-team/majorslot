<div class="col-6">
    <div class="form-group">
        <label for="name" class="form-control-label">{{__('Name')}}</label>
        <input class="form-control" type="text" value="{{ $edit?$partner->username:Request::get('username') }}" id="username" name="username">
    </div>
</div>

<div class="col-6">
    <div class="form-group">
        <label for="name" class="form-control-label">{{__('Email')}}</label>
        <input class="form-control" type="email" value="{{ $edit?$partner->email:Request::get('email') }}" id="email" name="email">
    </div>
</div>
<div class="col-6">
    <div class="form-group">
        <label for="name" class="form-control-label">{{__('Rate')}}%</label>
        <input class="form-control" type="text" value="{{ $edit?$partner->money_percent:Request::get('money_percent') }}" id="money_percent" name="money_percent">
    </div>
</div>

@if ($edit && auth()->user()->role_id == \App\Models\User::ROLE_ADMIN && $partner->role_id == \App\Models\User::ROLE_OPERATOR )
<div class="col-6">
    <div class="form-group">
        <label for="name" class="form-control-label">{{__('RTP')}}%</label>
        <input class="form-control" type="text" value="{{ $partner->shop?$partner->shop->percent:Request::get('percent') }}" id="percent" name="percent">
    </div>
</div>

@endif

<div class="col-6">
    <div class="form-group">
        <label for="name" class="form-control-label">{{__('Role')}}</label>
        <?php
            $roles = [];
            for ($role=\App\Models\User::ROLE_OPERATOR;$role<auth()->user()->role_id;$role++)
            {
                $roles[$role] = __(\App\Models\User::ROLE_NAMES[$role]);
            }
        ?>
        {!! Form::select('role_id', $roles, $edit ? $partner->role_id : '',
            ['class' => 'form-control', 'id' => 'role_id', 'disabled' => $edit]) !!}
    </div>
</div>

<div class="col-6">
    <div class="form-group">
        <label for="name" class="form-control-label">{{__('Password')}}</label>
        <input class="form-control" type="password" value="{{ Request::get('password') }}" id="password" name="password">
    </div>
</div>

<div class="col-6">
    <div class="form-group">
        <label for="name" class="form-control-label">{{__('Confirm Password')}}</label>
        <input class="form-control" type="password" value="{{ Request::get('confirmpwd') }}" id="password_confirmation" name="password_confirmation">
    </div>
</div>