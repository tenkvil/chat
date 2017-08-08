angular.module('chat')
    .component('chat', {
        templateUrl: 'front/app/chat/chat.template.html',
        controller: Chat
    });

Chat.$inject = ['$http', '$scope', '$httpParamSerializerJQLike', '$cookies', '$timeout', 'ab', 'XHRConfig'];

function Chat($http, $scope, $httpParamSerializerJQLike, $cookies, $timeout, ab, XHRConfig) {
    var self = this;

    self.text = '';
    self.canSend = false;
    self.token = $cookies.get('user_token');
    $http.get('/front.php/getAllChats').then(function(responce) {
        self.messages = JSON.parse(responce.data).data;
        scrollDown();
    });

    $scope.$watch(
        function(){ return self.text },
        function(text) {
            self.canSend = !!text.length;
        }
    );

    function scrollDown() {
        $timeout(function () {
            var element = angular.element(document.getElementsByClassName("chat-messages"));
            element[0].scrollTop = element[0].scrollHeight;
        }, 400);
    }

    var conn = new ab.Session('ws://localhost:1234',
        function() {
            conn.subscribe('pubSub', function(topic, data) {
                $http.get('/front.php/getAllChats').then(function(responce) {
                    self.messages = JSON.parse(responce.data).data;
                });
            });
        },
        function() {
            console.warn('WebSocket connection closed');
        },
        {'skipSubprotocolCheck': true}
    );

    self.sendMessage = function () {
        if (!self.canSend) {
            return null;
        }

        var data = {"userName":$cookies.get('user_name'), "text": self.text, "token": self.token};

        $http.post('/front.php/newMessage', $httpParamSerializerJQLike(data), XHRConfig).then(function(response) {
            self.text = '';
            scrollDown();
        });
    };

    /**
     * @param number id
     */
    self.removeMessage = function(id) {
        var data = {"id": id, "token": self.token};

        $http.post('/front.php/removeMessage', $httpParamSerializerJQLike(data), XHRConfig).then(function(response) {});
    };

    /**
     * @param number id
     */
    self.likeMessage = function(id) {
        var data = {"id": id};

        $http.post('/front.php/likeMessage', $httpParamSerializerJQLike(data), XHRConfig).then(function(response) {});
    };
}

