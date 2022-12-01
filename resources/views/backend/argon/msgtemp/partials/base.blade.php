<div class="form-group">
    <label>제목</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="@lang('app.title')" required value="{{ $edit ? $msgtemp->title : '' }}">
</div>
<div class="form-group">
    <label>순서</label>
    <input type="text" class="form-control" id="order" name="order"  required value="{{ $edit ? $msgtemp->order : '' }}">
</div>

<div class="form-group">
    <label>메시지 템플릿</label>
    <textarea id="content" name="content" rows="10" cols="150" style="width:100%;">{{ $edit ? $msgtemp->content : '템플릿내용을 입력하세요' }}</textarea>
</div>
