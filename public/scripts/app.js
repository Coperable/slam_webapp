'use strict';

/**
 * @ngdoc overview
 * @name slamApp
 * @description
 * # slamApp
 *
 * Main module of the application.
 */
angular
  .module('slamApp', [
    'ngAnimate',
    'ngAria',
    'ngCookies',
    'ngMessages',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'satellizer',
    'config',
    'ngTouch'
  ])
  .config(function ($routeProvider, $locationProvider) {
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

  });
