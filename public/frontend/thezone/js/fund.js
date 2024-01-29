function money_count(a) {
    var objMoney = window.document.getElementById("money");
    var objMoney1 = window.document.getElementById("money1");

    if (objMoney1.value == "") {
        objMoney.value = "0";
        objMoney1.value = "0";
    }

    objMoney.value = parseInt(objMoney.value) + parseInt(a);
    objMoney1.value = parseInt(objMoney.value); // +  parseInt(a);

    comma();
}

function money_count_clear(a) {
    var objMoney = window.document.getElementById("money");
    var objMoney1 = window.document.getElementById("money1");

    objMoney.value = "0";
    objMoney1.value = "0";
}

function money_count_hand(a) {
    var objMoney = window.document.getElementById("money");
    var objMoney1 = window.document.getElementById("money1");

    objMoney1.value = "";
    objMoney.value = "0";
    objMoney1.focus();
    //document.all.money.enabled = false;
}

function comma() {
    var form = window.document.getElementById("fundFrm");

    form.money.value = form.money1.value;
    form.money1.value = fCurrency(form.money.value);
}

function getContentDocument() {
    var objFrame = window.document.getElementById("iframe");

    return objFrame.contentDocument
        ? objFrame.contentDocument
        : objFrame.contentWindow.document;
}
