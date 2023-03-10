<div class="form-group">
    @if (auth()->user()->isInOutPartner())
    <label>회원아이디</label>
    <span class="text-red">* 회원아이디를 입력하지 않으면 전체회원들에게 발송됩니다.</span>
    <input type="text" class="form-control" id="user" name="user" placeholder="@lang('app.title')" value="{{($refmsg &&$refmsg->writer)?$refmsg->writer->username:Request::get('to') }}">
    @else
    <span class="text-red">***이 쪽지는 본사에 발송됩니다 ***</span>
    @endif
</div>
<div class="form-group">
    <label>제목</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="@lang('app.title')" required value="{{$refmsg?('[RE]'.$refmsg->title):Request::get('title') }}">
</div>
@if ($refmsg)
<div class="form-group">
    <label>문의내용</label>
    <textarea  id="refcontent" name="refcontent"  rows="5" cols="150" style="width:100%;" disabled >{{$refmsg->content}}</textarea>
</div>
@endif
<div class="form-group">
    <label>쪽지내용</label>
    <textarea id="content" name="content" rows="10" cols="150" style="width:100%;"></textarea>
</div>
