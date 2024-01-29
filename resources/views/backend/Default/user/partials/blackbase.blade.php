<div class="row">
    <div class="col-md-6">
    <div class="form-group">
        <label>이름</label>
        <input type="text" class="form-control" name="name" value = "{{$edit==true?$user->name:''}}" placeholder="이름">
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label>폰번호</label>
        <input type="text" class="form-control" name="phone" value = "{{$edit==true?$user->phone:''}}" placeholder="">
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label>은행</label>
        <input type="text" class="form-control" name="account_bank" value = "{{$edit==true?$user->account_bank:''}}" placeholder="">
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label>예금주</label>
        <input type="text" class="form-control" name="account_name" value = "{{$edit==true?$user->account_name:''}}" placeholder="">
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label>계좌번호</label>
        <input type="text" class="form-control" name="account_number" value = "{{$edit==true?$user->account_number:''}}" placeholder="">
    </div>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <label>설명</label>
        <input type="text" class="form-control" name="memo" value = "{{$edit==true?$user->memo:''}}" placeholder="">
    </div>
    </div>
</div>