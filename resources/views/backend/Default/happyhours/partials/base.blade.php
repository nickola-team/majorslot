<div class="col-md-6">
    <div class="form-group">
        <label>회원아이디</label>
        <input type="text" class="form-control" name="username" value="{{$edit ? \VanguardLTE\User::find($happyhour->user_id)?\VanguardLTE\User::find($happyhour->user_id)->username:'unknown':'' }}">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>총 당첨금</label>
        <input type="text" class="form-control" id="total_bank" name="total_bank" placeholder="0.00" value="{{ $edit ? $happyhour->total_bank : '' }}">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>잭팟기능</label>
        {!! Form::select('jackpot', ['0' => '비활성', '1' => '메이저', '2' => '그랜드'], $edit ? $happyhour->jackpot : 0, ['id' => 'jackpot', 'class' => 'form-control']) !!}
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.time')</label>
        @php
            $times = array_combine(\VanguardLTE\HappyHour::$values['time'], \VanguardLTE\HappyHour::$values['time']);
        @endphp
        {!! Form::select('time', \VanguardLTE\HappyHour::$values['time'], $edit ? $happyhour->time : '', ['class' => 'form-control']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.status')</label>
        {!! Form::select('status', ['0' => __('app.disabled'), '1' => __('app.active')], $edit ? $happyhour->status : 1, ['id' => 'status', 'class' => 'form-control']) !!}
    </div>
</div>