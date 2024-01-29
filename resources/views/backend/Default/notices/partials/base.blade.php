@if (auth()->user()->hasRole('admin'))
<div class="col-md-8">
    <div class="form-group">
        <label>작성자</label>
        <input type="text" class="form-control" id="user" name="user" placeholder="@lang('app.title')" value="{{ ($edit && $notice->writer) ? $notice->writer->username : '' }}">
    </div>
</div>

@endif
<div class="col-md-8">
    <div class="form-group">
        <label>제목</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="@lang('app.title')" required value="{{ $edit ? $notice->title : '' }}">
    </div>
</div>

<div class="col-md-8">
    <div class="form-group">
        <label>공지내용</label>
        <textarea id="content" name="content" rows="10" cols="80">
            {{ $edit ? $notice->content : '공지내용을 입력하세요' }}
        </textarea>
    </div>
</div>

<div class="col-md-8">
    <div class="form-group">
        <label>공지대상</label>
        {!! Form::select('type', \VanguardLTE\Notice::lists(), $edit ? $notice->type : 'user', ['id' => 'type', 'class' => 'form-control']) !!}
    </div>
</div>

<div class="col-md-8">
    <div class="form-group">
        <label>@lang('app.status')</label>
        {!! Form::select('active', ['0' => '비활성', '1' => '활성'], $edit ? $notice->active : 1, ['id' => 'active', 'class' => 'form-control']) !!}
    </div>
</div>
