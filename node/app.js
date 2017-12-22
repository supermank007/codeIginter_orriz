var app = require('http').createServer(handler);
var io = require('socket.io')(app);
var requestify = require('requestify');
var fs = require('fs');
const util = require('util');

var formidable = require('formidable');

app.listen(7682, '0.0.0.0');
var url = '';

fs.readFile(__dirname + '/.nodejs_url', "utf8",
    function (err, data) {
        if (err) {
            // res.writeHead(500);
            // return res.end('Error loading index.html');
            console.log("Can't find .nodejs_url");
        }
        url = data;
        console.log(url);
    });

function handler(req, res) {
    // fs.readFile(__dirname + '/.nodejs_url',
    //     function (err, data) {
    //         if (err) {
    //             res.writeHead(500);
    //             return res.end('Error loading index.html');
    //         }
    //     });
}

io.use(function (socket, next) {
    var handshakeData = socket.request;
    try {
        requestify.get(url, {
            dataType: 'json',
            headers: {
                'Cookie': handshakeData.headers["cookie"],
                'User-Agent': handshakeData.headers["user-agent"],
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function (response) {
            var user = null;
            var isJson = true;
            try {
                user = JSON.parse(response.getBody());
            } catch (err) {
                isJson = false;
            }
            if (isJson) {
                handshakeData.phpUser = user.user;
                next(null, true);
            }
        }, function (err) {
            console.log('err #1= ');
            console.log(util.inspect(err, false, null));
            next(null, false);
        });
    } catch (err) {
        console.log('err #2=' + err);
    }
});

var allClients = {};

io.on('connection', function (socket) {
    // var user = socket.handshake.user;
    var user = socket.client.conn.request.phpUser;
    if (user) {
        var id = user.id;
        allClients[id] = {user: user, socketId: socket.id};

        // socket.emit('newMessage', {hello: 'world'});
        //
        // socket.on('my other event', function (data) {
        //     console.log(data);
        // });


        socket.on('disconnect', function () {
            try {
                console.log(allClients[id].user.first_name + allClients[id].user.last_name + ' disconnected');
                delete allClients[id];
            } catch (err) {
            }
        });
    } else {
        console.log("user not found");
    }
});


//------------------------------------FOR PHP--------------------------------------------------------------------------

var appForPHP = require('http').createServer(function (req, res) {
    // Check for notices from PHP
    if (req.headers.host == '127.0.0.1:9246') {
        if (req.method == 'POST') {
            // The server is trying to send us an activity message
            var form = new formidable.IncomingForm();
            form.parse(req, function (err, fields, files) {
                res.writeHead(200, [["Content-Type", "text/plain"], ["Content-Length", 0]]);
                res.write('');
                res.end();
                //sys.puts(sys.inspect({fields: fields}, true, 4));
                handleServerNotice(fields);
            });
        }
    }
});
appForPHP.listen(9246);

function handleServerNotice(data) {
    try {
        if (data.type == 'newMessage') {
            var message = data.message;
            var recieverId = data.recieverId;
            var senderId = data.senderId;
            // io.to('notifierRoom').emit('newMessage', {message: message, recieverId: recieverId});
            var socketId = allClients[recieverId] ? allClients[recieverId].socketId : null;
            if (socketId) {
                io.to(socketId).emit('newMessage',
                    {
                        message: message,
                        senderId: senderId
                    });
            }
        }
    } catch (er) {
        console.log(er);
    }
}