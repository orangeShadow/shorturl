;(function(){
    var shortUrlApp = angular.module('shortUrlApp', []);

    shortUrlApp.config(['$interpolateProvider', function ($interpolateProvider) {
            $interpolateProvider.startSymbol('[[');
            $interpolateProvider.endSymbol(']]');
        }]);

    shortUrlApp.controller('ShortUrlListController', function PhoneListController($scope,$http) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.form = {
            origin_url:null,
            slug:null
        };

        $scope.error = {
            originUrl:null,
            slug:null
        };

        $scope.enable_slug = false;

        $scope.shortUrls = $http.get('/shorturl').then(function(response){
            $scope.shortUrls = response.data;
        },function (response) {
            sweetAlert("Oops...", "Something went wrong!", "error");
        });

        $scope.host = jQuery("#ShortUrlListController").data('host');

        $scope.generateUrl = function(slug) {
            return $scope.host+"/"+slug;
        }

        $scope.sendForm = function() {
            $scope.error = {
                origin_url:null,
                slug:null
            };

            $http.post("/shorturl",$scope.form).then(function(response) {
                $scope.form = {
                    origin_url:null,
                    slug:null
                };

                $scope.shortUrls.push(response.data);

                swal('Success',"Your short url is: "+$scope.host+"/"+response.data.slug,"success")
            }
            , function(response){

                if( response.status === 422 ){
                    for (e  in response.data ) {
                        if (response.data[e] instanceof Array) {
                            $scope.error[e] = response.data[e].join(',');
                        } else {
                            $scope.error[e] = response.data[e];
                        }
                    }

                    if($scope.enable_slug==false) {
                        sweetAlert("Oops...", "Generated slug was busy, can you repeat your reqeust, or enter your slug manual!", "error");
                    }

                } else {
                    sweetAlert("Oops...", "Something went wrong!", "error");
                }
            })
        }

        $scope.hasError = function(el){
            if($scope.error[el]!= null) return 'has-error';
        }

        $scope.deleteUrl = function (id) {
            $http.delete('/shorturl/'+id).then(function() {
                var index = $scope.shortUrls.findIndex(function(item){
                   return item.id == id;
                });

                $scope.shortUrls.splice(index,1);

            },function(){
                swal('Oops...','Something went wrong!',"error");
            });
        }

    });

})();