angular.module('verstak', [
    'ngCookies'
]);
angular.module('verstak')
    .controller('VerstakCtrl', ['$scope', '$cookies', function ($scope, $cookies) {
        /*################################################################################
         # РАБОТА в полноэкранном режиме
         ################################################################################*/
        $scope.is_show = true;
        $scope.keydown = function (keyEvent) {
            console.log('keydown -' + keyEvent);
        };
        $scope.frame_url = '';
        $scope.$watchGroup(['element', 'theme', 'breakpoint'], function () {
            if (!$scope.element.name) {
                return '';
            }
            var url = '/verstak/frame-';
            url += $scope.element.type;
            url += '-';
            url += $scope.element.name;
            url += '?theme=' + $scope.theme;
            url += '&breakpoint=' + $scope.breakpoint;
            $scope.frame_url = url;
            $cookies.put('verstak_element', $scope.element);
            $cookies.put('verstak_theme', $scope.theme);
        });

        /*################################################################################
         # РАБОТА С ЭЛЕМЕНТОМ
         ################################################################################*/
        $scope.getFrame = function () {
            if (!$scope.frame_url) {
                return null;
            }
            return '<iframe src="' + $scope.frame_url + '" name="site" frameborder="0"></iframe>';
        };
        $scope.getFrameStyle = function () {
            var ret = {};
            ret.width = $scope.getBreakpointTitle($scope.breakpoint);
            ret.top = $scope.is_show ? 48 : 0;
            return ret;
        };
        var element_name = $cookies.get('verstak_element_name'),
            element_type = $cookies.get('verstak_element_type');
        $scope.element = {
            name: ('undefined' != typeof element_name) ? element_name : 'index',
            type: ('undefined' != typeof element_type) ? element_type : 'page'
        };
        $scope.pages = [];
        $scope.blocks = [];
        $scope.setElement = function (name, type) {
            $scope.element = {
                name: name,
                type: type
            };
            $cookies.put('verstak_element_name', name);
            $cookies.put('verstak_element_type', type);
        };

        /*################################################################################
         # РАБОТА С ТЕМОЙ ОФОРМЛЕНИЯ
         ################################################################################*/
        $scope.setTheme = function (t) {
            $scope.theme = t;
        };
        $scope.themes = [];
        var theme = $cookies.get('verstak_theme');
        $scope.theme = ('undefined' != typeof theme) ? theme : 'default';

        /*################################################################################
         # РАБОТА С БРЕЙКПОИНТАМИ
         ################################################################################*/
        $scope.getBreakpointTitle = function (k) {
            return k ? k + 'px' : '100%';
        };
        $scope.setBreakpoint = function (k) {
            $scope.breakpoint = k;
            $cookies.put('verstak_breakpoint', $scope.breakpoint);
        };
        $scope.breakpoint = 0;
        $scope.breakpoints = [];
        var breakpoint = $cookies.get('verstak_breakpoint');
        $scope.breakpoint = parseInt(('undefined' != typeof breakpoint) ? breakpoint : 0);
    }]);