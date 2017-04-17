angular.module('verstak', []);
angular.module('verstak')
    .controller('VerstakCtrl', ['$scope', function ($scope) {
        /*################################################################################
         # РАБОТА в полноэкранном режиме
         ################################################################################*/
        $scope.is_show = true;
        $scope.keydown = function (keyEvent) {
            console.log('keydown -' + keyEvent);
        };
        $scope.frame_url = '';
        $scope.$watchGroup(['element', 'theme'], function () {
            if (!$scope.element.name) {
                return '';
            }
            var url = '/verstak/frame-';
            url += $scope.element.type;
            url += '-';
            url += $scope.element.name;
            url += '?theme=' + $scope.theme;
            $scope.frame_url = url;
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
        $scope.element = {
            name: 'index',
            type: 'page'
        };
        $scope.pages = [];
        $scope.blocks = [];
        $scope.setElement = function (name, type) {
            $scope.element = {
                name: name,
                type: type
            };
        };

        /*################################################################################
         # РАБОТА С ТЕМОЙ ОФОРМЛЕНИЯ
         ################################################################################*/
        $scope.theme = 'default';
        $scope.setTheme = function (t) {
            $scope.theme = t;
        };
        $scope.themes = [];

        /*################################################################################
         # РАБОТА С БРЕЙКПОИНТАМИ
         ################################################################################*/
        $scope.getBreakpointTitle = function (k) {
            return k ? k + 'px' : '100%';
        };
        $scope.setBreakpoint = function (k) {
            $scope.breakpoint = k;
        };
        $scope.breakpoint = 0;
        $scope.breakpoints = [];
    }]);