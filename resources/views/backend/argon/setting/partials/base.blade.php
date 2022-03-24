<div class="form-group">
    <label>도메인</label>
    <input type="text" class="form-control" id="domain" name="domain" value="{{ ($edit && $website->domain) ? $website->domain : '' }}">
</div>
<div class="form-group">
    <label>제목</label>
    <input type="text" class="form-control" id="title" name="title"  value="{{ $edit ? $website->title : '' }}">
</div>

<div class="form-group">
    <label>페이지디자인</label>
    {!! Form::select('frontend', $frontends,  $edit ? $website->frontend : '' , ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label>관리자디자인</label>
    <input type="text" class="form-control" id="backend" name="backend"  value="{{ $edit ? $website->backend : '' }}">
</div>

<div class="form-group">
    <label>총본사</label>
    <input type="text" class="form-control" id="admin" name="admin"  value="{{ ($edit && $website->admin) ? $website->admin->username : '' }}">
</div>