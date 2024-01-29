<div class="col-md-8">
    <div class="form-group">
        <label>회원아이디</label> <span class="text-red">* 회원아이디를 입력하지 않으면 전체회원들에게 발송됩니다.</span>
        <input type="text" class="form-control" id="user" name="user" placeholder="@lang('app.title')" value="{{Request::get('to') }}">
    </div>
</div>
<div class="col-md-8">
    <div class="form-group">
        <label>제목</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="@lang('app.title')" required value="">
    </div>
</div>
<div class="col-md-8">
    <div class="form-group">
        <label>쪽지내용</label>
        <textarea id="content" name="content" rows="10" cols="150" style="width:100%;"></textarea>
    </div>
</div>
