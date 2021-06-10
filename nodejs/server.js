var app = require('express')();

var server = require('http').Server(app);

var io = require('socket.io')(server, {
    cors: {
        origin: "http://localhost:8890",
        methods: ["GET", "POST"],
        transports: ['websocket', 'polling'],
        credentials: true
    },
    allowEIO3: true
});

var redis = require('redis');



server.listen(8890);

io.on('connection', function (socket) {



    console.log("client connected");

    var redisClient = redis.createClient();

    redisClient.subscribe('message');



    redisClient.on("message", function(channel, data) {

        console.log("mew message add in queue "+ data['message'] + " channel");

        socket.emit(channel, data);

    });



    socket.on('disconnect', function() {

        redisClient.quit();

    });



});