angular.module('chat')
    .component('chat', {
        templateUrl: 'front/app/chat/chat.template.html',
        controller: Chat
    });

Chat.$inject = ['$http','$scope','$httpParamSerializerJQLike', '$cookies', 'ab'];

function Chat($http, $scope, $httpParamSerializerJQLike, $cookies, ab) {
    var self = this;

    self.text = '';
    self.token = $cookies.get('user_token');
    $http.get('/front.php/getAllChats').then(function(responce) {
        self.messages = responce.data.data;
    });

    var conn = new ab.Session('ws://localhost:1234',
        function() {
            conn.subscribe('pubSub', function(topic, data) {
                $http.get('/front.php/getAllChats').then(function(responce) {
                    self.messages = responce.data.data;
                });
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );

    self.sendMessage = function () {
        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        };
        var data = {"userName":$cookies.get('user_name'), "text": self.text, "token": self.token};

        $http.post('/front.php/newMessage', $httpParamSerializerJQLike(data), config).then(function(response) {});
    };

    self.removeMessage = function(id) {
        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        };
        var data = {"id":id, "token": self.token};

        $http.post('/front.php/removeMessage', $httpParamSerializerJQLike(data), config).then(function(response) {});
    };

    self.likeMessage = function(id) {
        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        };
        var data = {"id":id};

        $http.post('/front.php/likeMessage', $httpParamSerializerJQLike(data), config).then(function(response) {});
    };
}
