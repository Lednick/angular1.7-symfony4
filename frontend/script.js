(function (angular) {
    'use strict';
    angular.module('app', ['ngRoute', 'ngResource'])

        .controller('MainController', function ($scope, $route, $routeParams, $location) {
            $scope.$route = $route;
            $scope.$location = $location;
            $scope.$routeParams = $routeParams;
        })

        .controller('ListsController', function ($scope, $routeParams, $http) {

            $http.get('http://localhost:8000/api/lists').then(function successCallback(response) {

                $scope.lists = response.data;
            });

            $scope.doSearch = function () {
                $http.get(`http://localhost:8000/api/lists?search=${$scope.search}`).then(function successCallback(response) {

                    $scope.lists = response.data;
                });
            }
        })
        .controller('ListController', function ($scope, $routeParams, $http) {

            $http.get(`http://localhost:8000/api/list/${$routeParams.listId}`).then(function successCallback(response) {

                $scope.list = response.data;
            });

            $scope.deleteTodo = function (id) {
                $http.delete(`http://localhost:8000/api/list/${$routeParams.listId}/todo/${id}`).then(function successCallback(response) {
                    window.location.href = `/list/${$scope.list.id}`;
                });
            }
        })
        .controller('TodoAddController', function ($scope, $routeParams, $http) {

            $http.get(`http://localhost:8000/api/list/${$routeParams.listId}`).then(function successCallback(response) {

                $scope.list = response.data;
            });

            $scope.submit = function () {
                $http.post(`http://localhost:8000/api/list/${$routeParams.listId}/todo`, {name: $scope.name}).then(function successCallback(response) {

                    // $scope.list = response.data;

                    window.location.href = `/list/${$scope.list.id}`;
                });
            }
        })

        .controller('ListAddController', function ($scope, $routeParams, $http) {

            $scope.submit = function () {
                $http.post('http://localhost:8000/api/list', {name: $scope.name}).then(function successCallback(response) {

                    // $scope.list = response.data;

                    window.location.href = '/lists';
                });
            }
        })

        .controller('TodoController', function ($scope, $routeParams) {
            $scope.name = 'TodoController';
            $scope.params = $routeParams;
        })

        .config(function ($routeProvider, $locationProvider) {
            $routeProvider
                .when('/lists', {
                    templateUrl: 'lists.html',
                    controller: 'ListsController',
                })
                .when('/list/add', {
                    templateUrl: 'list_add.html',
                    controller: 'ListAddController',
                })
                .when('/list/:listId', {
                    templateUrl: 'list.html',
                    controller: 'ListController',
                })
                .when('/list/:listId/todo/add', {
                    templateUrl: 'todo_add.html',
                     controller: 'TodoAddController',
                })
                .when('/list/:listId/todo/:todoId', {
                    templateUrl: 'todo.html',
                    controller: 'TodoController'
                });

            // configure html5 to get links working on jsfiddle
            $locationProvider.html5Mode(true);
        })

})(window.angular);
