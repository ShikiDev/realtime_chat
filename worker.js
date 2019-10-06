var http = require('http').Server();
var io = require('socket.io')(http);
var Redis = require("ioredis");

var redis = new Redis();
redis.subscribe('get-new-message');
redis.on('message', function (channel, message) {
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

http.listen(3000, function () {
    console.log('Listening on Port: 3000');
});
