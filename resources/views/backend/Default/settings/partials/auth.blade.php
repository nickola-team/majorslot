{!! Form::open(['route' => $admurl.'.settings.auth.update', 'id' => 'auth-general-settings-form']) !!}

<div class="form-group">
    <label>
        @lang('app.reset_token_lifetime')
    </label>
    <input type="text" name="login_reset_token_lifetime" class="form-control" value="{{ settings('login_reset_token_lifetime', 30) }}">
</div>

<div class="form-group">
    <input type="hidden" value="0" name="siteisclosed">
    <label class="checkbox-container">
        @lang('app.turn_off_the_site')
        {!! Form::checkbox('siteisclosed', 1, settings('siteisclosed'), ['id' => 'switch-siteisclosed']) !!}
        <span class="checkmark"></span>
    </label>
</div>


<div class="form-group">
    <input type="hidden" value="0" name="enable_master_deal">
    <label class="checkbox-container">
        본사 딜비 허용
        {!! Form::checkbox('enable_master_deal', 1, settings('enable_master_deal'), ['id' => 'switch-enable_master_deal']) !!}
        <span class="checkmark"></span>
    </label>
</div>
