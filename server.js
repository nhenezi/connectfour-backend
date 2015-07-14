var PORT = 4444;

var io = require('socket.io')(PORT);
var cookie_reader = require('cookie');
var querystring = require('querystring');

console.log('Server listening at:', PORT);

var redis = require('redis');
var sub = redis.createClient();
var client = redis.createClient();

var connected_clients = {};

io.set('authorization', function(data, accept) {
  if (data.headers.cookie) {
    data.cookie = cookie_reader.parse(data.headers.cookie);
    return accept(null, true);
  }

  console.log('Cookie not found');
  return accept('error', false);
});


var events = [
  'match found',
  'move',
  'game over'
];


io.on('connection', function (socket) {
  var id = socket.client.request.cookie.access_token;
  connected_clients[id] = socket;
  console.log('New client conntected:', id);

  // subscribe to events
  events.forEach(function(event) {
    sub.subscribe(id + '|' + event, function(channel, count) {
    });
  });

  console.log('Subscribed to', events);
  socket.on('disconnect', function() {
    console.log(id, 'disconnected');
    events.forEach(function(event) {
      client.lrem('users in poll', 0, id, function(err, a) {
        console.error('ERR', err, a);
      });
      sub.unsubscribe(id + '|' + event);
    });
    delete connected_clients[id];
  });
});


sub.on('message', function(pattern, message) {
  var id = pattern.split('|')[0];
  var channel = pattern.split('|')[1];
  if (id in connected_clients) {
    connected_clients[id].emit(channel, JSON.parse(message));
  } else {
    console.error("Trying to send message t", id);
  }
});
