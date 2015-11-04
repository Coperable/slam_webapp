'use strict';

/**
 * @ngdoc function
 * @name slamApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the slamApp
 */
angular.module('slamApp')
.controller('MainCtrl', function ($scope, $rootScope, $http, api_host, Region) {

	$rootScope.home_page = true;
    $scope.summary = {};
    $scope.setup_components = function() {
        setTimeout(function() {
            jQuery("#home_slider_2").carousel({
                interval:7e3
            });

            jQuery('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover'
		    });

            jQuery('#carousel-torneos').carousel({
                interval: 1000,
                wrap: false
            });

            jQuery("#preloader").fadeOut("fast",function(){
                jQuery(this).remove()
            });

        }, 1000);
    };

    $scope.setup_components();

    $scope.refreshRegion = function() {
        Region.get({
            id: $scope.current_region.id
        }, function(data) {
            $scope.region = data;
            $scope.fetchSummary();
        });
    };

    $scope.fetchSummary = function() {
        $http.get(api_host+'/api/region/'+$scope.current_region.id+'/summary').success(function(summary_data) {
            $scope.processSummary(summary_data);
        });
    };

    $scope.processSummary = function(summary_data) {
        $scope.summary = summary_data;
    };


    $rootScope.$on("current_region", function(event, current_region) {
        if(current_region) {
            $scope.current_region = current_region;
            $scope.refreshRegion();
        }
    });


})
.controller('site-controller', function ($scope, $rootScope, $http, Region, Account) {
    $scope.current_region = {};

    Account.listenRegion(function(region) {
        $scope.current_region = region;
    });

})
.controller('sessionBar', function ($scope, $rootScope, $http, Region, Account) {
    $scope.regions = [];
    $scope.region = {};
    $scope.current_region = {};
    $scope.summary = {};


    Region.query(function(regions) {
        $scope.regions = regions;
        if(_.isEmpty($scope.current_region)) {
            $scope.setCurrentRegion(_.first($scope.regions));
        }
    });


    $scope.setCurrentRegion = function(region) {
        Account.setCurrentRegion(region);
        $scope.current_region = region;
    };


})
.controller('revista-view', function ($scope, $rootScope) {
	$rootScope.home_page = false;

})
.controller('torneo-view', function ($scope, $rootScope) {
	$rootScope.home_page = false;

})
.controller('torneo-list', function ($scope, $rootScope) {
	$rootScope.home_page = false;
})
.controller('user-signup', function ($scope, $rootScope) {
	$rootScope.home_page = false;
})
.controller('video-list', function ($scope, $rootScope) {
	$rootScope.home_page = false;
})
.controller('jugador-view', function ($scope, $rootScope) {
	$rootScope.home_page = false;
})
.controller('jugador-list', function ($scope, $rootScope) {
	$rootScope.home_page = false;
})
.controller('foro-view', function ($scope, $rootScope) {
	$rootScope.home_page = false;

})
.controller('profile-view', function ($scope, $rootScope) {
	$rootScope.home_page = false;

});

