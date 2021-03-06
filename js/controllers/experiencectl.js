hrmsApp.controller('experienceCtl', function ($scope, $timeout, $resource, ngTableParams) {
    var Api = $resource('/portal/api/experience/M421-2014');
    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,          // count per page
        sorting: {
            name: 'asc'     // initial sorting
        }
    }, {
        total: 0,           // length of data
        getData: function($defer, params) {
            // ajax request to api
            Api.get(params.url(), function(data) {
                $timeout(function() {
                    // update table params
                    params.total(data.total);
                    // set new data
                    $defer.resolve(data.result);
                    console.log(data.result);
                }, 10);
            });
        }
    });
});