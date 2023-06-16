var req;

function newXMLHttpRequest() {

	var xmlreq = false;

	if (window.XMLHttpRequest) {

		xmlreq = new XMLHttpRequest();

	} else if (window.ActiveXObject) {

		try {

			xmlreq = new ActiveXObject("Msxml2.XMLHTTP");

		} catch (e1) {

			try {

				xmlreq = new ActiveXObject("Microsoft.XMLHTTP");

			} catch (e2) {

			}

		}

	}

	return xmlreq;

}

function HTTPPostRequest(URL, PostData, FunctionReadyStateChange) {

	req = newXMLHttpRequest();

	req.onreadystatechange = FunctionReadyStateChange;

	req.open("POST", URL, true);

	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	PostSend = '';

	var VarRequst = PostData.split(",");

	for (var num in VarRequst) {

		var VarName = VarRequst[num];

		var VarInput = document.getElementById(VarName);

		if (num>0) {

			PostSend += '&';

		}

		PostSend += VarName+'='+VarInput.value;

	}

	req.send(PostSend);

}

function HTTPFormRequest(URL, Frm, PostData, FunctionReadyStateChange) {

	req = newXMLHttpRequest();

	req.onreadystatechange = FunctionReadyStateChange;

	req.open("POST", URL, true);

	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	PostSend = '';

	var VarRequst = PostData.split(",");

	for (var num in VarRequst) {

		var VarName = VarRequst[num];

		var VarInput = Frm.elements[VarName].value;

		if (num>0) {

			PostSend += '&';

		}

		PostSend += VarName+'='+VarInput;

	}

	req.send(PostSend);

}

function HTTPGetRequest(URL, FunctionReadyStateChange) {

	req = newXMLHttpRequest();

	req.onreadystatechange = FunctionReadyStateChange;

	req.open("GET", URL, false);

	req.send('');

}

function processReqChange() {

	if (req.readyState == 4) {

		if (req.status == 200) {

			StrResponseBody = req.responseBody;

			StrResponseText = req.responseText;

		} else {

			alert("There was a problem retrieving the XML data:\n"+req.statusText);

		}

	}

}