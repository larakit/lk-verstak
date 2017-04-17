<?php
namespace Larakit\Verstak\Controllers;

use Larakit\Controller;

class VerstakController extends Controller {
    
    protected $layout = 'lk-verstak::!.layouts.default';
    
    function index() {
        \LaraPage::html()->ngApp('verstak');
        \LaraPage::body()->setAttribute('ng-controller', 'VerstakCtrl');
        \LaraPage::body()->setAttribute('ng-keydows', 'keydown');
        \Larakit\StaticFiles\Manager::conditions(null, '*', 'verstak*');
        \Larakit\StaticFiles\Manager::package('larakit/lk-verstak')
            ->jsPackage('js/angular.js')
            ->js('/verstak/js');
        
        return $this->response();
    }
}