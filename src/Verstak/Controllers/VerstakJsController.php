<?php
namespace Larakit\Verstak\Controllers;

use Illuminate\Support\Arr;
use Larakit\Controller;
use Larakit\Page\PageTheme;
use Larakit\Verstak\VerstakManager;

class VerstakJsController extends Controller {
    
    function js() {
        $js = file_get_contents(__DIR__ . '/../../javascripts/module.js');
        $js = str_replace('$scope.pages = [];', '$scope.pages = ' . json_encode(VerstakManager::$pages, JSON_PRETTY_PRINT) . ';', $js);
        $js = str_replace('$scope.blocks = [];', '$scope.blocks = ' . json_encode(VerstakManager::$blocks, JSON_PRETTY_PRINT) . ';', $js);
        $js = str_replace('$scope.themes = [];', '$scope.themes = ' . json_encode(VerstakManager::$themes, JSON_PRETTY_PRINT) . ';', $js);
        if(count(VerstakManager::$themes)) {
            $theme = Arr::first(VerstakManager::$themes);
            $js = str_replace('$scope.theme = \'default\';', '$scope.theme = \'' . $theme . '\';', $js);
        }
        $js = str_replace('$scope.breakpoints = [];', '$scope.breakpoints = ' . json_encode(VerstakManager::$breakpoints, JSON_PRETTY_PRINT) . ';', $js);
        $response = \Response::make($js, 200);
        $response->header('Content-Type', 'application/javascript');
        
        return $response;
    }
    
}