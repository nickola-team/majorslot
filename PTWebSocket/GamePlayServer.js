var express = require('express');
var SignalRJS = require('signalrjs');
const request = require('request');
var cors = require('cors');

var fs = require('fs');
var serverConfig = JSON.parse(fs.readFileSync('../public/socket_config_gp.json', 'utf8'));
global.clients = {
	12 : [],
	13 : [],
	90 : [],
	91 : [],
	92 : [],
};

var signalR = SignalRJS();
signalR.hub('gamehub',{
	auth : function(userName,gametype){
		console.log('auth:'+userName + ", p : " + gametype);
		//remove old userName from clients
		Object.keys(global.clients).forEach(g => {
			global.clients[g] = global.clients[g].filter(item => item !== userName);
		});

		global.clients[gametype].push(userName);


		this.clients.user(userName).invoke('commandMessage').withArgs(['Auth','OK']);
	}
});
function gamePing(game)
{
	var gameURL = serverConfig.prefix+serverConfig.origin_host+"/REST/GameCore/trendInfo?p=" + game;
	console.log(gameURL);
	return new Promise(resolve =>{
		 request.post(gameURL, function(error, response, body)
			{
				if(error)
				{
					resolve(game);
					return;
				}
				var data = JSON.parse(body);
				players = global.clients[game];
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
				resolve(0);
			}
		);
	}).then(value => {
		setTimeout(gamePing, 1000, game);
	});
}
function startPing() {
	Object.entries(global.clients).forEach(entry => {
		const [game, players] = entry;
		gamePing(game);
	});
}


var server = express();
var corsOptions = {
	origin: "*",
	credentials: true,
  }
server.use(cors(corsOptions));
server.use(express.static(__dirname));
server.use(signalR.createListener());
server.listen(serverConfig.listen);
startPing();