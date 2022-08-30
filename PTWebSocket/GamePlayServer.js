var express = require('express');
var SignalRJS = require('signalrjs');
var syncrequest = require('sync-request');
var cors = require('cors');

var fs = require('fs');
var serverConfig = JSON.parse(fs.readFileSync('../public/socket_config_gp.json', 'utf8'));
var clients = {};

var signalR = SignalRJS();
signalR.hub('gamehub',{
	auth : function(userName,message){
		console.log('auth:'+userName + ", p : " + message);
		// clients[message].push(userName);
		if (message in clients)
		{
			if (!clients[message].includes(userName))
			{
				clients[message].push(userName);
			}
		}
		else
		{
			clients[message] = [userName];
		}

		this.clients.user(userName).invoke('commandMessage').withArgs(['Auth','OK']);
	}
});
setInterval(function () {
	Object.entries(clients).forEach(entry => {
		const [game, players] = entry;
		var gameURL = serverConfig.prefix+serverConfig.origin_host+"/REST/GameCore/trendInfo?p=" + game;
		var result = syncrequest('POST', gameURL);
		var data = JSON.parse(result.getBody());
		players.forEach(player => {
			if (data.s == 1)
			{
				signalR.sendToUser(player, {
					H:'gameHub', M:'livePool',
					A:[JSON.stringify({
						p:game,a:1,
						data : [],
						r:0.0
					})]
				});
				signalR.sendToUser(player, {
					H:'gameHub', M:'broadcastMessage',
					A:[JSON.stringify({
						cmd : 'Trend',
						data : {
							draw : [data.old]
						}
					})]
				});

				signalR.sendToUser(player, {
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
				signalR.sendToUser(player, {
					H:'gameHub', M:'livePool',
					A:[JSON.stringify(data.live)]
				});
			}
		});
	});
},1000);

var server = express();
var corsOptions = {
	origin: `${serverConfig.prefix}${serverConfig.origin_host}`,
	credentials: true,
  }
server.use(cors(corsOptions));
server.use(express.static(__dirname));
server.use(signalR.createListener());
server.listen(serverConfig.listen);
