angular.module('chat')
    .component('author', {
        templateUrl: 'front/app/author/author.template.html',
        bindings: {
            message: '<',
            token: '<'
        },
        controller: Author
    });

Author.$inject = ['$http', '$httpParamSerializerJQLike', '$cookies'];

function Author ($http, $httpParamSerializerJQLike, $cookies) {
    var self = this;

    self.nameEditable = false;
    self.authorName = self.message.userName;

    self.editName = function() {
        self.nameEditable = true;
    };

    self.saveName = function () {
        self.nameEditable = false;
        //todo: вынести отдельно
        var config = {
            headers : {
                'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
            }
        };
        var data = {"userName":self.authorName, "token": self.token};

        $http.post('/front.php/changeName', $httpParamSerializerJQLike(data), config).then(function(response) {
            $cookies.put('user_name', self.authorName);
        });
    }
}
