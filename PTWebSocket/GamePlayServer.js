var express = require('express');
var SignalRJS = require('signalrjs');
var syncrequest = require('sync-request');
var cors = require('cors');

var fs = require('fs');
var serverConfig = JSON.parse(fs.readFileSync('../public/socket_config_gp.json', 'utf8'));


var signalR = SignalRJS();
signalR.hub('gamehub',{
	send : function(userName,message){
		console.log('send:'+userName);
		this.clients.all.invoke('broadcast').withArgs([userName,message]);
	},
	auth : function(userName,message){
		console.log('auth:'+userName);
		this.clients.user(userName).invoke('commandMessage').withArgs(['Auth','OK']);
	}
});
setInterval(function () {

	var gameURL = serverConfig.prefix+serverConfig.origin_host+"/REST/GameCore/trendInfo?p=12";
	var result = syncrequest('POST', gameURL);
	var data = JSON.parse(result.getBody());
	if (data.s == 1)
	{
		signalR.broadcast({
			H:'gameHub', M:'livePool',
			A:[JSON.stringify({
				p:12,a:1,
				data : [],
				r:0.0
			})]
		});
		signalR.broadcast({
			H:'gameHub', M:'broadcastMessage',
			A:[JSON.stringify({
				cmd : 'Trend',
				data : {
					draw : [data.old]
				}
			})]
		});

		signalR.broadcast({
			H:'gameHub', M:'broadcastMessage',
			A:[JSON.stringify({
				cmd : 'Trend',
				data : {
					draw : [data.new]
				}
			})]
		});
		
	}
	else
	{
		signalR.broadcast({
			H:'gameHub', M:'livePool',
			A:[JSON.stringify(data.live)]
		});
	}
},1000);

function proxyGetFunc(req, res) 
{
	var gameURL = `${serverConfig.prefix}${serverConfig.origin_host}${req.originalUrl}`;
	console.log(gameURL);
	var result = syncrequest('GET', gameURL, {
		headers: {       
		  'content-type': 'application/x-www-form-urlencoded'
		}
	  });
	res.setHeader('Content-Type', 'application/json'); 
	res.send(result.getBody());
	res.end();
}
function proxyPostFormFunc(req, res) 
{
	var gameURL = `${serverConfig.prefix}${serverConfig.origin_host}${req.originalUrl}`;
	var result = syncrequest('POST', gameURL, {
		headers: {       
		'content-type': 'application/x-www-form-urlencoded'
		},
		body: new URLSearchParams(req.body).toString()
	});
	res.setHeader('Content-Type', 'application/json'); 
	res.send(result.getBody());
	res.end();
}

function proxyPostJsonFunc(req, res) 
{
	var gameURL = `${serverConfig.prefix}${serverConfig.origin_host}${req.originalUrl}`;
	
	let data = '';
	req.on('data', chunk => {
		data += chunk;
	});
	req.on('end', () => {
		var result = syncrequest('POST', gameURL, {
			headers: {       
			'content-type': 'application/json'
			},
			body: data
		});
		res.setHeader('Content-Type', 'application/json'); 
		res.send(result.getBody());
		res.end();
	});
}


var server = express();
var corsOptions = {
	origin: `${serverConfig.prefix}${serverConfig.origin_host}`,
	credentials: true,
  }
server.use(cors(corsOptions));
server.use(express.static(__dirname));
server.use(signalR.createListener());
server.post('/REST/GameEngine/Livebetpool', proxyPostFormFunc);
server.post('/REST/GameEngine/GetMemberDrawResult', proxyPostFormFunc);
server.post('/REST/GameEngine/HistoryBet', proxyPostJsonFunc);
server.post('/REST/GameEngine/MultiLimit', proxyPostFormFunc);
server.post('/REST/GameEngine/GameSetting', proxyPostFormFunc);
server.get('/REST/TrialPromo/GetTrialPromotionInfo', proxyGetFunc);
server.post('/REST/GameEngine/DrawResult', proxyPostJsonFunc);
server.get('/REST/GameEngine/OpenBet3', proxyGetFunc);
server.get('/REST/GameEngine/ServerTime', proxyGetFunc);
server.post('/REST/GameEngine/UserInfo',  proxyPostFormFunc);
server.post('/REST/GameConfig/GetActiveProductsByVendor', proxyPostFormFunc);
server.post('/REST/GameEngine/SpreadBet', proxyPostJsonFunc);
server.post('/REST/GameEngine/WinLose', proxyPostJsonFunc);
server.post('/REST/GameEngine/Trend', proxyPostFormFunc);

server.listen(serverConfig.listen);
