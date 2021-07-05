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
        <label>본사</label>
        <input type="text" class="form-control" id="mastername" name="mastername" value="{{ isset($master)?$master->username:'' }}" >
    </div>
    @if ($user->hasRole(['distributor', 'manager']))
    <div class="form-group">
        <label>부본사</label>
        <input type="text" class="form-control" id="agentname" name="agentname" value="{{ isset($agent)?$agent->username:'' }}" >
    </div>
    @endif
    @if ($user->hasRole('manager'))
    <div class="form-group">
        <label>총판</label>
        <input type="text" class="form-control" id="distributorname" name="distributorname" value="{{ isset($distributor)?$distributor->username:''  }}" >
    </div>
    @endif

</div>

<div class="box-footer">
    <button type="submit" class="btn btn-primary" id="update-details-btn">
        확인
    </button>
</div>
