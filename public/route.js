angular.module("toppack").config(function ($routeProvider) {
    'use strict';

    var toppackRouteConfig = {
      controller: 'ToppackController',
      templateUrl: '../views/search.html'
    };

    $routeProvider
      .when('/', toppackRouteConfig)
      .otherwise({
        redirectTo: '/'
      });
});
