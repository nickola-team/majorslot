var express = require('express');
var SignalRJS = require('signalrjs');
var syncrequest = require('sync-request');
var cors = require('cors');
const redis = require('redis');
const redisClient = redis.createClient();

var fs = require('fs');
var serverConfig = JSON.parse(fs.readFileSync('../public/socket_config_gp.json', 'utf8'));

var signalR = SignalRJS();
signalR.hub('gamehub',{
	auth : function(userName,message){
		console.log('auth:'+userName + ", p : " + message);
		// clients[message].push(userName);
		clients = JSON.parse(redisClient.get('players'));
		
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

		redisClient.set('players', JSON.stringify(clients)).then(function(v){
			this.clients.user(userName).invoke('commandMessage').withArgs(['Auth','OK']);
		});

	}
});
setInterval(function () {
	redisClient.get('players').then(function(strValue){
		clients = JSON.parse(strValue);
		console.log('clients = ');
		console.log(clients);
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
	});
},1000);

//init redis
redisClient.connect().then(function(){
	redisClient.set('players', JSON.stringify({
		12 : [],
		13 : []
	})).then();
	
});

var server = express();
var corsOptions = {
	origin: `${serverConfig.prefix}${serverConfig.origin_host}`,
	credentials: true,
  }
server.use(cors(corsOptions));
server.use(express.static(__dirname));
server.use(signalR.createListener());
server.listen(serverConfig.listen);
