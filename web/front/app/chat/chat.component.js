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
    self.CSRF = $cookies.get('CSRF');
    $http.get('/front.php/getAllChats').then(function(response) {
        self.messages = JSON.parse(response.data).data;
        scrollDown();
    });

    $http.get('/front.php/getSettings').then(function(response) {
        var settings = JSON.parse(response.data).data;
        var conn = new ab.Session('ws://' + settings.host + ':' + settings.port,
            function() {
                conn.subscribe('pubSub', function(topic, data) {
                    $http.get('/front.php/getAllChats').then(function(response) {
                        self.messages = JSON.parse(response.data).data;
                    });
                });
            },
            function() {
                console.warn('WebSocket connection closed');
            },
            {'skipSubprotocolCheck': true}
        );
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

    function getTextarea() {
        return angular.element(document.getElementById ("textarea"));
    }

    getTextarea().bind('keyup', function(event){
        var enterKeyCode = 13;
        if (event.keyCode == enterKeyCode) {
            self.sendMessage();
        }
    });

    self.sendMessage = function () {
        if (!self.canSend) {
            return null;
        }

        var data = {
            "userName":$cookies.get('user_name'),
            "text": self.text,
            "token": self.token,
            "CSRF": self.CSRF
        };

        $http.post('/front.php/newMessage', $httpParamSerializerJQLike(data), XHRConfig).then(function(response) {
            self.text = '';
            scrollDown();
        });
    };

    /**
     * @param number id
     */
    self.removeMessage = function(id) {
        var data = {
            "id": id,
            "token": self.token,
            "CSRF": self.CSRF
        };

        $http.post('/front.php/removeMessage', $httpParamSerializerJQLike(data), XHRConfig).then(function(response) {});
    };

    /**
     * @param number id
     */
    self.likeMessage = function(id) {
        var data = {
            "id": id,
            "CSRF": self.CSRF
        };

        $http.post('/front.php/likeMessage', $httpParamSerializerJQLike(data), XHRConfig).then(function(response) {});
    };
}

