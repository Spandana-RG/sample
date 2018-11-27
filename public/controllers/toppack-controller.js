function toppackController($scope, ToppackService) {
  'use strict';

  $scope.searchTerm = "";
  $scope.searchError = "";

  $scope.searchRepository = function(searchTerm) {
    var successCallback = function(data) {
      $scope.repositories = data;
    }
    var errorCallback = function(error) {
      $scope.searchError = "We are unable load data. Please try again later"
    }
    ToppackService.retreiveRepositories(searchTerm, successCallback, errorCallback);
  };

  $scope.importRepository = function(repository, index) {
    console.log(repository);
    console.log(index);
    var successCallback = function(response) {
      $scope.repositories[index].imported = true;
      $scope.repositories[index].importError = false;
    }

    var errorCallback = function(error) {
      console.log("cmg here");
      $scope.repositories[index].importError = true;
    }
    ToppackService.importRepository(repository, successCallback, errorCallback);
  }
}

angular.module("toppack").controller("ToppackController", toppackController);
