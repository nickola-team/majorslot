var fs = require('fs');
var serverConfig = JSON.parse(fs.readFileSync('../public/socket_live_config.json', 'utf8'));
/*
var serverConfig = {
    "port":3000,
    "host":"127.0.0.1",
    "prefix":"http://",
    "host_ws":"127.0.0.1",
    "prefix_ws":"ws://",
    "ssl":false
    }
*/
var http = require('http').Server();
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('Lives', function(err, count) {
    console.log('subscribe on Lives');
});

redis.on('message', function(channel, message) {

    message = JSON.parse(message);

    console.log(message);

    io.emit(channel + ':' + message.event, message.data);
});

http.listen(serverConfig.port, function() {
    console.log('Listening on Port '+serverConfig.port);
});