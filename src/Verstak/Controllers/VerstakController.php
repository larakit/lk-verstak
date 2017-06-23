<?php
namespace Larakit\Verstak\Controllers;

use Larakit\Controller;
use Larakit\Page\LkPage;

class VerstakController extends Controller {
    
    protected $layout = 'lk-verstak::!.layouts.default';
    
    function index() {
        LkPage::instance()->html()->ngApp('verstak');
        LkPage::instance()->body()->setAttribute('ng-controller', 'VerstakCtrl');
        LkPage::instance()->body()->setAttribute('ng-keydows', 'keydown');
        \Larakit\StaticFiles\Manager::conditions(null, '*', 'verstak*');
        \Larakit\StaticFiles\Manager::package('larakit/lk-verstak')
//            ->jsPackage('js/angular.js', null, true)
            ->js('/verstak/js', null, true);
        
        return $this->response([
            'environment' => \App::environment(),
        ]);
    }
}