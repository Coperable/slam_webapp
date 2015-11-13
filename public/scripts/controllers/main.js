'use strict';

/**
 * @ngdoc function
 * @name slamApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the slamApp
 */
angular.module('slamApp')
.controller('MainCtrl', function ($scope, $rootScope, $http, $sce, api_host, Region) {

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

    $scope.getYoutubeSrc = function(video) {
        console.log("http://www.youtube.com/embed/"+video.name);
        return $sce.trustAsResourceUrl("http://www.youtube.com/embed/"+video.name);
    };


    $scope.participate = function(competition) {
        $http.post(api_host+'/api/competition/'+competition.id+'/participate', {})
        .success(function(data) {
            console.log(data);
        })
        .error(function(error) {
            console.log(error);
        });

    };


})
.controller('slider-controller', function ($scope, $auth, $timeout, Slider) {
    $scope.sliders = [];
    console.log('sliders controllers');
    Slider.query(function(sliders) {
        $scope.sliders = sliders;
        $timeout(function() {
            jQuery("#home_slider_main").carousel({
                interval:7e3
            });
        });
    });


})
.controller('site-controller', function ($scope, $rootScope, $http, $auth, Region, Account) {
    $scope.current_region = {};

    Account.listenRegion(function(region) {
        $scope.current_region = region;
    });

    $scope.isAuthenticated = function() {
        return $auth.isAuthenticated();
    };


})
.controller('sessionBar', function ($scope, $rootScope, $http, Region, Account) {
    $scope.regions = [];
    $scope.region = {};
    $scope.current_region = {};
    $scope.summary = {};

    $scope.is_authenticated = false;
    $scope.account = false;
    $scope.profile = false;

    Region.query(function(regions) {
        $scope.regions = regions;
        if(_.isEmpty($scope.current_region)) {
            $scope.setCurrentRegion(_.first($scope.regions));
        }
    });

    Account.getProfile(function(data) {
        $scope.profile = data;
    });

    $scope.setCurrentRegion = function(region) {
        Account.setCurrentRegion(region);
        $scope.current_region = region;
    };

    $rootScope.$on("account", function(event, profile) {
        if(profile) {
            $scope.account = profile;
        }
    });

    $scope.logout = function() {
        Account.logout();
    };

    $scope.goProfile = function() {

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

})
.controller('LoginCtrl', function($scope, $rootScope, $auth, $location, $state, $stateParams, Account) {

    $scope.backUrl = $stateParams.backUrl;

    $scope.login = function() {
        $auth.login({ email: $scope.email, password: $scope.password })
        .then(function(response) {
            Account.getProfile(function(profile) {
                $scope.account = profile;

                if(profile) {
                    //text: 'Ha ingresado a la plataforma', 
                    if($scope.backUrl) {
                        $state.go($scope.backUrl);
                    }
                }

            });
            if(response.data.select_profile) {
                $location.path('/select-profile');
            }
        })
        .catch(function(response) {
            //text: 'Los datos ingresados no son correctos', 
        });
    };
    $scope.authenticate = function(provider) {
      $auth.authenticate(provider)
        .then(function() {
            //text: 'Se ha autenticado el usuario', 
        })
        .catch(function(response) {
            //text: response.message, 
        });
    };

})
.controller('SignupCtrl', function($scope, $auth, $state, $location, Account) {

    $scope.signup = function() {
        $auth.signup({
            username: $scope.username,
            email: $scope.email,
            password: $scope.password,
            language_code: 'ES' //ToDo: Obtener del sitio
        })
        .then(function() {
            //$state.go('select-profile'); 
            Account.getProfile(function(profile) {
                $scope.user_data = profile;
            });
        })
        .catch(function(response) {
            //text: 'Los datos no son correctos', 
        });
    };

    $scope.authenticate = function(provider) {
        $auth.authenticate(provider)
        .then(function() {
           // $state.go('select-profile'); 
            Account.getProfile(function(profile) {
                $scope.user_data = profile;
            });
        })
        .catch(function(response) {
            //text: response.message, 
        });
    };


})
.controller('LogoutCtrl', function($auth, $rootScope, Account) {
    if (!$auth.isAuthenticated()) {
        return;
    }
    Account.logout();
})
;
