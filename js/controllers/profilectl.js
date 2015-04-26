hrmsApp.controller('profileCtl', function ($scope, $http) {
    $scope.profile_data = {};
    $http.get('/portal/api/employee/M421-2014').
    success(function(data, status, headers, config) {
        $scope.profile_data = data;
    }).
    error(function(data, status, headers, config) {
        // called asynchronously if an error occurs
        // or server returns response with an error status.
    });
});