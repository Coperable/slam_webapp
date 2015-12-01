'use strict';

/**
 * @ngdoc function
 * @name slamApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the slamApp
 */
angular.module('slamApp')
.controller('MainCtrl', function ($scope, $rootScope, $http, $sce, api_host, Region, Account) {
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



    $rootScope.$on("region_summary", function(event, summary) {
        $scope.summary = summary;
    });

    $scope.getYoutubeSrc = function(video) {
        return $sce.trustAsResourceUrl("http://www.youtube.com/embed/"+video.name);
    };

    $scope.participate = function(competition) {
        $http.post(api_host+'/api/competition/'+competition.id+'/participate', {})
        .success(function(data) {
            $scope.refresh();
        })
        .error(function(error) {

        });
    };


})
.controller('slider-controller', function ($scope, $auth, $timeout, Slider) {
    $scope.sliders = [];
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
    /*
    $scope.current_region = {};

    Account.listenRegion(function(region) {
        $scope.current_region = region;
    });
    */
    $scope.refresh = function() {
        Account.fetchRegionSummary();
    };

    $scope.isAuthenticated = function() {
        return $auth.isAuthenticated();
    };


})
.controller('sessionBar', function ($scope, $rootScope, $http, $route, $location, Region, Account) {
    $scope.regions = [];
    $scope.region = {};
    $scope.summary = {};

    $scope.is_authenticated = false;
    $scope.account = false;
    $scope.profile = false;

    Region.query(function(regions) {
        $scope.regions = regions;
        if(_.isEmpty($scope.current_region)) {
            Account.setCurrentRegion(_.first($scope.regions));
        }
    });

    Account.getProfile(function(data) {
        $scope.profile = data;
    });

    $scope.changeCurrentRegion = function(region) {
        Account.setCurrentRegion(region);
    };

    $rootScope.$on("account", function(event, profile) {
        if(profile) {
            $scope.account = profile;
        }
    });

    $scope.logout = function() {
        Account.logout();
        $route.reload();
        $scope.refresh();
        window.scrollTo(0, 0);
    };

    $scope.goProfile = function() {
        $location.path('/jugador/'+Account.profile.id);
    };


})
.controller('video-list', function ($scope, $rootScope, $http, $sce, api_host, Region, Account) {

	$rootScope.home_page = false;

	$scope.predicate = 'lastname';
	$scope.reverse = false;

    $scope.direction = function(direction) {
        $scope.reverse = direction;
    };

    $scope.sort = function(field) {
        if(field === 'date') {
            $scope.predicate = 'id';
        }
        if(field === 'ranking') {
            $scope.predicate = 'competitions';
        }
    };

    $scope.videos = [];

    $rootScope.$on("region_summary", function(event, summary) {
        $scope.processSummary();
    });

    $scope.processSummary = function() {
        if($rootScope.region_summary) {
            $scope.summary = $rootScope.region_summary;
            $scope.videos = $scope.summary.videos;
        }

        jQuery("#preloader").fadeOut("fast",function(){
            jQuery(this).remove()
        });
    };

    $scope.search_term = '';

    $scope.doSearch = function() {
        if($scope.search_term.trim() == '') {
            $scope.participants = $scope.summary.participants;
            return;
        }
        var pattern = new RegExp($scope.search_term, "gi");
        $scope.videos = _.filter($scope.summary.videos, function(model) {
            return pattern.test(JSON.stringify(model));
        });
    };

    $scope.getYoutubeSrc = function(video) {
        return $sce.trustAsResourceUrl("http://www.youtube.com/embed/"+video.name);
    };

    $scope.processSummary();

})
.controller('jugador-list', function ($scope, $rootScope, $http, api_host, Region, Account) {
	$rootScope.home_page = false;

	$scope.predicate = 'lastname';
	$scope.reverse = false;

    $scope.direction = function(direction) {
        $scope.reverse = direction;
    };

    $scope.sort = function(field) {
        if(field === 'date') {
            $scope.predicate = 'id';
        }
        if(field === 'ranking') {
            $scope.predicate = 'competitions';
        }
    };

    $scope.participants = [];

    $rootScope.$on("region_summary", function(event, summary) {
        $scope.processSummary();
    });

    $scope.processSummary = function() {
        if($rootScope.region_summary) {
            $scope.summary = $rootScope.region_summary;
            $scope.participants = $scope.summary.participants;
        }
        jQuery("#preloader").fadeOut("fast",function(){
            jQuery(this).remove()
        });

    };

    $scope.search_term = '';

    $scope.doSearch = function() {
        if($scope.search_term.trim() == '') {
            $scope.participants = $scope.summary.participants;
            return;
        }
        var pattern = new RegExp($scope.search_term, "gi");
        $scope.participants = _.filter($scope.summary.participants, function(model) {
            return pattern.test(JSON.stringify(model));
        });
    };

    $scope.processSummary();


})
.controller('LoginCtrl', function($scope, $rootScope, $auth, $location, $state, $stateParams, $route, Account) {

    $scope.backUrl = $stateParams.backUrl;

    $scope.login = function() {
        $auth.login({ email: $scope.email, password: $scope.password })
        .then(function(response) {
            Account.getProfile(function(profile) {
                $scope.account = profile;
                $route.reload();
                $scope.refresh();
                window.scrollTo(0, 0);
            });
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
.controller('SignupCtrl', function($scope, $auth, $state, $location, $route, Account) {

    $scope.signup = function() {
        $auth.signup({
            username: $scope.username,
            email: $scope.email,
            password: $scope.password,
            language_code: 'ES' //ToDo: Obtener del sitio
        })
        .then(function() {
            $auth.login({ email: $scope.email, password: $scope.password })
            .then(function(response) {
                Account.getProfile(function(profile) {
                    $scope.account = profile;
                    $route.reload();
                    $scope.refresh();
                    window.scrollTo(0, 0);
                });
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
.controller('jugador-view', function ($scope, $rootScope, $routeParams, $http, $sce, api_host, Participant) {
	$rootScope.home_page = false;
    
    $scope.participant = {};

    $scope.getYoutubeSrc = function(video) {
        return $sce.trustAsResourceUrl("http://www.youtube.com/embed/"+video.name);
    };

    Participant.get({
        id: $routeParams.id
    }, function(participant ) {
        $scope.participant = participant;
        jQuery("#preloader").fadeOut("fast",function(){
            jQuery(this).remove()
        });
    });


})
.controller('revista-view', function ($scope, $rootScope) {
	$rootScope.home_page = false;
    jQuery("#preloader").fadeOut("fast",function(){
        jQuery(this).remove()
    });

})
.controller('torneo-view', function ($scope, $rootScope, $routeParams, $http, $sce, api_host, Competition) {
	$rootScope.home_page = false;
    
    $scope.competition = {};

    $scope.getYoutubeSrc = function(video) {
        return $sce.trustAsResourceUrl("http://www.youtube.com/embed/"+video.name);
    };

    Competition.get({
        id: $routeParams.id
    }, function(competition) {
        $scope.competition = competition;
        jQuery("#preloader").fadeOut("fast",function(){
            jQuery(this).remove()
        });
    });

    $scope.participate = function(competition) {
        $http.post(api_host+'/api/competition/'+competition.id+'/participate', {})
        .success(function(data) {
            $scope.refresh();
        })
        .error(function(error) {
            console.log(error);
        });
    };



})

.controller('torneo-list', function ($scope, $rootScope, $http, api_host, Region, Account) {
	$rootScope.home_page = false;

    $rootScope.$on("region_summary", function(event, summary) {
        $scope.processSummary();
    });

    $scope.processSummary = function() {
        $scope.summary = $rootScope.region_summary;
        jQuery("#preloader").fadeOut("fast",function(){
            jQuery(this).remove()
        });
    };

    $scope.processSummary();

})




.controller('user-signup', function ($scope, $rootScope) {
	$rootScope.home_page = false;
})

.controller('foro-view', function ($scope, $rootScope) {
	$rootScope.home_page = false;

})
.controller('profile-view', function ($scope, $rootScope) {
	$rootScope.home_page = false;

})

;
