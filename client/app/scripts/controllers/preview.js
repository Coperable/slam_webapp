'use strict';

angular.module('previewApp')
.controller('preview-main', function ($scope, $rootScope) {
    console.log('preview!');

    $scope.setup_components = function() {
        setTimeout(function() {
            $("#home_slider_2").carousel({
                interval:7e3
            });
            $('[data-toggle="tooltip"]').tooltip({
		      trigger: 'hover'
		    });
            $('#carousel-torneos').carousel({
                interval: 1000,
                wrap: false
            });

        }, 1000);
    };

    $scope.setup_components();

});


