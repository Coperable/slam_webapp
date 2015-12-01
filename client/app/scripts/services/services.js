angular.module('slamApp')
.factory('Region',['$resource', 'api_host', function($resource, api_host){
    return $resource(api_host+'/api/regions/:id', { id:'@id' }, {
        update: {
            method: 'PUT'
        }
    });
}])
.factory('Competition',['$resource', 'api_host', function($resource, api_host){
    return $resource(api_host+'/api/competitions/:id', { id:'@id' }, {
        update: {
            method: 'PUT'
        }
    });
}])
.factory('Slider',['$resource', 'api_host', function($resource, api_host){
    return $resource(api_host+'/api/sliders/:id', { id:'@id' }, {
        update: {
            method: 'PUT'
        }
    });
}])
.factory('User',['$resource', 'api_host', function($resource, api_host){
    return $resource(api_host+'/api/users/:id', { id:'@id' }, {
        update: {
            method: 'PUT'
        }
    });
}])
.factory('Participant',['$resource', 'api_host', function($resource, api_host){
    return $resource(api_host+'/api/participants/:id', { id:'@id' }, {
        update: {
            method: 'PUT'
        }
    });
}])
.factory('Account', ['$http', '$rootScope', '$auth', '$location', 'api_host', function($http, $rootScope, $auth, $location, api_host) {
    return {
        profile: false,
        profile_id: false,
        fetching: false,
        current_region: false,
        roles: [],
        regions: [],
    
        getProfile: function(callback) {
            var self = this;
            console.log('1');
            if(!$auth.isAuthenticated()) {
                if(callback) {
                    self.listen(callback);
                }
                return;
            }
            console.log('2');
            if(self.profile) {
                console.log('3');
                if(callback) {
                    callback(self.profile);
                    return;
                } else {
                    console.log('4');
                    return this.profile;
                }
            }
            console.log('5');
            if(!self.fetching) {
                console.log('6');
                self.fetching = true;
                return $http.get(api_host+'/api/me').success(function(data){
                    self.fetching = false;
                    if(data.user == null) {
                        $auth.logout();
                        self.profile = false;
                        self.profile_id = false;
                        $rootScope.account = false;
                        self.broadcast();
                        $location.url('/');
                     } else {
                        console.log('7');
                        self.profile = data.user; 
                        self.profile_id = self.profile.id;
                        $rootScope.account = self.profile;
                        self.regions = self.profile.regions;
                        self.roles = self.profile.roles;
                        self.broadcast();
                    }
                    if(callback) {
                        callback(self.profile);
                    }

                }).catch(function(response) {
                    $auth.logout();
                    self.profile = false;
                    self.profile_id = false;
                    self.broadcast();
                });
            } else {
                self.listen(callback);
            }
            return false;
        },

        hasRole: function(role_name) {
            var result = _.find(this.roles, function(role) {
                return role.name == role_name;
            });
            return result ? true : false;
        },

        logout: function() {
            if(!$auth.isAuthenticated()) {
                return;
            }
            this.profile = false;
            this.profile_id = false;
            $auth.logout();
            $rootScope.account = false;
            this.broadcast();
            $location.url('/')
        },
        getStatus: function() {
            return $http.get(api_host+'/api/me/status');
        },
        updateProfile: function(profileData) {
            return $http.put(api_host+'/api/me', profileData);
        },
        setCurrentRegion: function(region) {
            this.current_region = region;
            $rootScope.current_region = region;
            this.fetchRegionSummary();
            //this.broadcastRegion();
        },
        fetchRegionSummary: function() {
            $http.get(api_host+'/api/region/'+this.current_region.id+'/summary').success(function(summary_data) {
                $rootScope.region_summary = summary_data;
                $rootScope.$broadcast("region_summary", summary_data);
            });
        },
        broadcastRegion: function() {
            $rootScope.$broadcast("current_region", this.current_region);
        },
        listenRegion: function(callback) {
            $rootScope.$on("current_region", function(event, newValue) {
                if(newValue) {
                    callback(newValue);
                }
            });
        },
        broadcast: function() {
            console.log('8');
            $rootScope.$broadcast("account", this.profile);
        },
        listen: function(callback) {
            console.log('Account: set list for callback');
            $rootScope.$on("account", function(event, newValue) {
                if(newValue) {
                    console.log('Account: listening calling callback');
                    callback(newValue);
                }
            });
        }
    };
}])
.factory('User',['$resource', 'api_host', function($resource, api_host){
    return $resource(api_host+'/api/users/:id', { id:'@id' }, {
        update: {
            method: 'POST'
        }
    });
}]);

