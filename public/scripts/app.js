'use strict';

angular
.module('slamApp', [
    'ngAnimate',
    'ngAria',
    'ngCookies',
    'ngMessages',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'config',
    'ngTouch',
    'ui.router',
    //'ui.bootstrap',
    'satellizer'
])
.config(function($routeProvider, $authProvider, $locationProvider) {
    $routeProvider
    .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl',
        controllerAs: 'main'
    })
    .when('/profile', {
        templateUrl: 'views/profile.html',
        controller: 'profile-view'
    })
    .when('/foro', {
        templateUrl: 'views/foro.html',
        controller: 'foro-view'
    })
    .when('/revista', {
        templateUrl: 'views/revista.html',
        controller: 'revista-view'
    })
    .when('/torneo', {
        templateUrl: 'views/torneo.html',
        controller: 'torneo-view'
    })
    .when('/jugador', {
        templateUrl: 'views/jugador.html',
        controller: 'jugador-view'
    })
    .when('/jugadores', {
        templateUrl: 'views/jugadores.html',
        controller: 'jugador-list'
    })
    .when('/torneos', {
        templateUrl: 'views/torneos.html',
        controller: 'torneo-list'
    })
    .when('/videos', {
        templateUrl: 'views/videos.html',
        controller: 'video-list'
    })
    .when('/registro', {
        templateUrl: 'views/registro.html',
        controller: 'user-signup'
    })
    .when('/about', {
        templateUrl: 'views/about.html',
        controller: 'AboutCtrl',
        controllerAs: 'about'
    })
    .otherwise({
        redirectTo: '/'
    });

    $locationProvider.html5Mode(true);


    function skipIfLoggedIn($q, $auth) {
        var deferred = $q.defer();
        if ($auth.isAuthenticated()) {
            deferred.reject();
        } else {
            deferred.resolve();
        }
        return deferred.promise;
    }

    function loginRequired($q, $location, $auth) {
        var deferred = $q.defer();
        if ($auth.isAuthenticated()) {
            deferred.resolve();
        } else {
            $location.path('/login');
        }
        return deferred.promise;
    }



})
.config(function ($authProvider, api_host) {

    $authProvider.baseUrl = api_host+'/';
    $authProvider.httpInterceptor = true;
    $authProvider.signupRedirect = null;

    $authProvider.facebook({
        url: '/auth/social/facebook',
        clientId: '295482087249664',
        scope: 'email,public_profile'
    });

    $authProvider.google({
        url: '/auth/social/google',
        clientId: '313110710680-p22p1s5brqn7tfaqj9v16u67bic5smqk.apps.googleusercontent.com'
    });

    $authProvider.twitter({
        url: '/auth/social/twitter'
    });


})

;
