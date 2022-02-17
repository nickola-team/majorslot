function goJoin() {
    var formData = $("#signUpForm").serialize();
    $.ajax({
        cache: false,
        url: "/api/join",
        type: "POST",
        data: formData,
        success: function(data) {
            console.log(data);

            if (data != "success") {
                $('.wrapper_loading').addClass('hidden');
                alertify.alert(data);
            } else {
                alertify.alert("", "회원 가입하셨습니다.");
                location.reload();
            }
        }
    });
}

function goDeposit() {
    var formData = $("#depositForm").serialize();
    $.ajax({
        cache: false,
        url: "/api/addbalance",
        type: "POST",
        data: formData,
        success: function(data) {
            //console.log(data);

            if (data.error) {
                $('.wrapper_loading').addClass('hidden');
                alertify.alert("",data.msg);
            } else {
                alertify.alert("","충전 신청하셨습니다.");
            }
        }
    });
}

function goWithdraw() {
    var formData = $("#withdrawalForm").serialize();
    $.ajax({
        cache: false,
        url: "/api/outbalance",
        type: "POST",
        data: formData,
        success: function(data) {
            //console.log(data);

            if (data.error) {
                $('.wrapper_loading').addClass('hidden');
                alertify.alert("",data.msg);
            } else {
                alertify.alert("","환전 신청하셨습니다.");
            }
        }
    });
}