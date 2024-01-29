var fs = require('fs');
var serverConfig;

serverConfig = JSON.parse(fs.readFileSync('../public/socket_config_cq9.json', 'utf8'));
if(serverConfig.ssl) {
	var privateKey = fs.readFileSync('ssl/goldsvet.com.key', 'utf8');
	var certificate = fs.readFileSync('ssl/goldsvet.com.crt', 'utf8');
	var credentials = {
		key: privateKey,
		cert: certificate
	};
	var https = require('https');
	var httpsServer = https.createServer(credentials);
	httpsServer.listen(serverConfig.port);
	var WebSocket = require('ws').Server;
	var wss = new WebSocket({
		server: httpsServer
	});
} else {
	var WebSocket = require('ws');
	var wss = new WebSocket.Server({
		port: serverConfig.listen
	});
}
wss.on('connection', function connection(ws) {
	ws.cookie = '';
	ws.gameName = '';
	ws.sessionId = '';
	ws.on('close', function incoming(message){
		if(ws.cookie != ''){
			var request = require('request');
			var gameName = ws.gameName;
			var ck = ws.cookie;
			var gameURL = serverConfig.prefix + serverConfig.host + '/game/' + gameName + 'CQ9/server?&sessionId=' + ws.sessionId;
			console.log( 'close : ' + gameURL);
			if(gameName == undefined) {
				console.log(gameURL);
				return;
			}
			// var paramStr = "~m~811~m~~j~{\"err\":200,\"req\":1000,\"vals\":[],\"msg\":null}";
			var paramStr = "{\"gameData\":{\"err\":200,\"req\":1000,\"vals\":[],\"msg\":null}}";
			var options = {
				method: 'post',
				body: JSON.parse(paramStr),
				json: true,
				rejectUnauthorized: false,
				requestCert: false,
				agent: false,
				url: gameURL,
				headers: {
					'Connection': 'keep-alive',
					"Content-Type": "application/json",
					'Content-Length': paramStr.length,
					'Cookie': ck
				}
			}
			request(options, function(err, res, body) {
				console.log("--SocketClosed--");
			});
		}
	});
	ws.on('message', function incoming(message) {
		console.log("message : " + message);
		/*------------------------*/
		var request = require('request');
		var gameName = '';
		message = message + '';
		var param = {};
		if(message.split(":::")[1] != undefined) {
			try {
				param = JSON.parse(message.split(":::")[1]);
			} catch(e) {
				return;
			}
			var ck = param.cookie;
			var sessionId = param.sessionId;
			param.cookie = '';
			gameName = param.gameName;
			ws.cookie = ck;
			ws.gameName = gameName;
			ws.sessionId = sessionId;
		} else {
			var param = {};
			var ck = '';
		}
		var gameURL = serverConfig.prefix + serverConfig.host + '/game/' + gameName + 'CQ9/server?&sessionId=' + sessionId;
		console.log(gameURL);
		if(gameName == undefined) {
			console.log(param);
			return;
		}
		var paramStr = JSON.stringify(param);
		console.log("Param str = " + paramStr);
		console.log("Cookie str = " + ck);
		var options = {
			method: 'post',
			body: param,
			json: true,
			rejectUnauthorized: false,
			requestCert: false,
			agent: false,
			url: gameURL,
			headers: {
				'Connection': 'keep-alive',
				"Content-Type": "application/json",
				 'Content-Length': paramStr.length,
				'Cookie': ck
			}
		}
		console.log("Option Url : " + JSON.stringify(options));
		request(options, function(err, res, body) {
			if(err) {
				console.log('Error Request :', err)
				return
			}
			if(body != undefined) {
				try {
					body = body + '';
					var allReq = body.toString().split("------");
				} catch(e) {
					console.log('Error body :', e)
					return;
				}
				console.log('-------  message  ----------');
				console.log(message);
				console.log('-------  send  ----------');
				for(var i = 0; i < allReq.length; i++) {
					console.log(allReq[i]);
					ws.send(allReq[i]);
				}
			}
		});
		/*-------------------------*/
	});
	ws.send('~m~36~m~809342d7-a2b7-4a2d-abac-4ac5489ea8ac');
});