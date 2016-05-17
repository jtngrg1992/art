var app=angular.module('app',['ngRoute']);

app.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        templateUrl: 'templates/allarts.html',
        controller: 'requestData'
      }).
       when('/details/:id', {
        templateUrl: 'templates/details.html',
        controller: 'getDetail'
      }).
        when('/test',{
          templateUrl:'templates/test.html',
          controller: 'test'
        }).
      otherwise({
        redirectTo: '/'
      });
  }]);

app.factory('fetch',function($http){
  return{
    getData:function(url){
      return $http.get(url)
        .then(function(result){
          return result.data;
        })
    }
  }
})