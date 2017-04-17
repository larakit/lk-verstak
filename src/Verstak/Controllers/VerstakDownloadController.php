<?php
namespace Larakit\Verstak\Controllers;

use Alchemy\Zippy\Zippy;
use Larakit\Controller;
use Larakit\Verstak\Manager;

class VerstakDownloadController extends Controller {
    
    function index() {
        $blocks    = \Larakit\Verstak\VerstakManager::$blocks;
        $themes    = \Larakit\Verstak\VerstakManager::$themes;
        $pages     = \Larakit\Verstak\VerstakManager::$pages;
        $contents  = [
            '!'        => public_path('!/'),
            'packages' => public_path('packages'),
        ];
        $tmp_names = [];
        if(!is_dir(storage_path('verstak/'))) {
            mkdir(storage_path('verstak/'), 0777, true);
        }
        $digest = [];
        foreach($pages as $page) {
            foreach($themes as $theme) {
                $name     = 'page_' . $page . '--' . $theme . '.html';
                $url      = url('/verstak/frame-page-' . $page . '?theme=' . $theme);
                $content  = $this->prepare_content($url);
                $tmp_name = storage_path('verstak/' . $name);
                file_put_contents($tmp_name, $content);
                $contents[$name]                = fopen($tmp_name, 'r');
                $tmp_names[]                    = $tmp_name;
                $digest['pages'][$page][$theme] = $name;
            }
        }
        foreach($blocks as $block) {
            foreach($themes as $theme) {
                $name     = 'block_' . $block . '--' . $theme . '.html';
                $url      = url('/verstak/frame-block-' . $block . '?theme=' . $theme);
                $content  = $this->prepare_content($url);
                $tmp_name = storage_path('verstak/' . $name);
                file_put_contents($tmp_name, $content);
                $contents[$name]                  = fopen($tmp_name, 'r');
                $tmp_names[]                      = $tmp_name;
                $digest['blocks'][$block][$theme] = $name;
            }
        }
        $tmp_name = storage_path('verstak/index.html');
        //        $tmp_names[]     = $tmp_name;
        file_put_contents($tmp_name, \View::make('lk-verstak::!.digest', ['digest' => $digest])->__toString());
        $contents['index.html'] = fopen($tmp_name, 'r');
        //        dd(compact('path', 'theme', 'url', 'content'));
        
        // Creates an archive.zip that contains a directory "folder" that contains
        // files contained in "/path/to/directory" recursively
        // Load Zippy
        $zip_path = storage_path(date('H_i_s') . '.zip');
        $zippy    = Zippy::load();
        $zippy->create($zip_path, $contents, true);
        foreach($tmp_names as $tmp_name) {
            unlink($tmp_name);
        }
        
        return \Response::download($zip_path);
    }
    
    function prepare_content($url) {
        $content = file_get_contents($url);
        $content = str_replace('href="//', 'href="http://', $content);
        $content = str_replace('src="//', 'src="http://', $content);
        $content = str_replace('href="/!', 'href="!', $content);
        $content = str_replace('src="/!', 'src="!', $content);
        $content = str_replace('href="/packages', 'href="packages', $content);
        $content = str_replace('src="/packages', 'src="packages', $content);
        
        return $content;
    }
    
}