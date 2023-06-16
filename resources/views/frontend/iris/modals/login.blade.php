<div class="loginModal modal fade" role="dialog">
    <div class="login_modal evolution_modal modal-dialog">
        <div class="modal-head">
  			<span class="title">
  				<p>@yield('page-title')에 오신 것을 환영합니다.</p>
  				<p class="sub">성공적인 베팅을 위한 파트너 @yield('page-title')!</p>
  			</span>
            <a href="#" class="close-btn" data-dismiss="modal"><i class="fa fa-times"></i></a>
        </div>

        <div class="modal_body">

                <input type="hidden" value="<?= csrf_token() ?>" name="_token" id="_token">

                <div class="form-group">
                    <label>아이디</label>
                    <input class="id"type="text" name="mb_login_id"
                           data-parsley-required="required"
                           data-parsley-required-message="" autocomplete="off" id="id">
                </div>
                <div class="form-group">
                    <label>비밀번호</label>
                    <input class="password"type="password" name="password"
                           data-parsley-required="required"
                           data-parsley-required-message="" autocomplete="off" id="pass">
                </div>
                <div class="modal_btn_grp">
                    <button type="submit" class="login-btn" onclick="login()" >로그인</button>
                    <button type="reset" data-dismiss="modal">취소</button>
                </div>
  
        </div>
    </div>
</div>

<script>
    function login() {
        var id = $('#id').val();
        var pass = $('#pass').val();
        if(!id || !pass){
            mustSignIn('아이디와 비밀번호를 입력하세요.');
        } else {

            var formData = new FormData();
            formData.append("_token", $("#_token").val());
            formData.append("username", id);
            formData.append("password", pass);

            $.ajax({
                type: "POST",
                url: "/api/login",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function (data, status) {
                    if (data.error) {
                        alert(data.msg);
                    }

                    location.reload();
                },
                error: function (err, xhr) {
                }
            });
        }
    }
</script>