function toppackService($http) {
  return {
    retreiveRepositories: function(searchTerm, successCallback, errorCallback) {
      var url = "/repositories/search/" + searchTerm;
      var httpParams = {
        method: 'GET',
        url: url
      }
      $http(httpParams).then(function(response) {
        successCallback(response.data);
      }, function(error) {
        errorCallback(error);
      });
    },

    importRepository: function(repoData, successCallback, errorCallback) {
      console.log("in import");
      var url = "/import"
      var data = repoData;
      var httpParams = {
        method: "POST",
        url: url,
        data: data
      }

      $http(httpParams).then(function(response) {
        successCallback(response);
      }, function(error) {
        errorCallback(error)
      });
    }
  }
};

angular.module("toppack").service("ToppackService", toppackService);
