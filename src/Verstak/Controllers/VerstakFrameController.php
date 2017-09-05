<?php
namespace Larakit\Verstak\Controllers;

use Larakit\Controller;
use Larakit\Page\LkPage;
use Larakit\StaticFiles\Css;
use Larakit\StaticFiles\Js;
use Larakit\Verstak\VerstakManager;

class VerstakFrameController extends Controller {
    
    function initStaticiles() {
        //\Larakit\StaticFiles\Manager::conditions(null, '*', 'verstak*');
        $file_conf_packages = public_path(VerstakManager::$prefix . '/common/packages.conf');
        $packages           = [];
        if(file_exists($file_conf_packages)) {
            $handle = fopen($file_conf_packages, "r");
            while(!feof($handle)) {
                $buffer     = fgets($handle, 4096);
                $packages[] = trim($buffer);
            }
            fclose($handle);
        }
        foreach(\Larakit\StaticFiles\Manager::packages() as $package => $data) {
            if(!in_array($package, $packages)) {
                \Larakit\StaticFiles\Manager::conditions($package, '*', 'verstak*');
            } else {
                \Larakit\StaticFiles\Manager::conditions($package, '*verstak*');
            }
        }
        $p = \Larakit\StaticFiles\Manager::package('verstak');
        foreach($packages as $package) {
            $p->usePackage($package);
        }
        $file_conf_js = public_path(VerstakManager::$prefix . '/common/js.conf');
        if(file_exists($file_conf_js)) {
            $handle = fopen($file_conf_js, "r");
            while(!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $url    = $this->prepareUlr($buffer);
                if($url) {
                    $p->js($url);
                }
            }
            fclose($handle);
        }
        $file_conf_css = public_path(VerstakManager::$prefix . '/common/css.conf');
        if(file_exists($file_conf_css)) {
            $handle = fopen($file_conf_css, "r");
            while(!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $url    = $this->prepareUlr($buffer);
                if($url) {
                    $p->css($url);
                }
            }
            fclose($handle);
        }
        foreach(VerstakManager::$blocks as $block) {
            $file_css = public_path(VerstakManager::$prefix . '/blocks/' . $block . '/block.css');
            if(file_exists($file_css)) {
                $url = '/' . VerstakManager::$prefix . '/blocks/' . $block . '/block.css';
                $p->css($url);
            }
        }
        foreach(VerstakManager::$blocks as $block) {
            $file_js = public_path(VerstakManager::$prefix . '/blocks/' . $block . '/block.js');
            if(file_exists($file_js)) {
                $url = '/' . VerstakManager::$prefix . '/blocks/' . $block . '/block.js';
                $p->js($url);
            }
        }
        foreach(VerstakManager::$themes as $theme) {
            $file_css = public_path(VerstakManager::$prefix . '/themes/' . $theme . '.css');
            if(file_exists($file_css)) {
                $url = '/' . VerstakManager::$prefix . '/themes/' . $theme . '.css';
                $p->css($url);
            }
        }
        
        $build = public_path(VerstakManager::$prefix . '/staticfiles.php');
        file_put_contents($build, '<?php' . PHP_EOL . $p->export());
    }
    
    function prepareUlr($string) {
        $string = trim($string);
        if(!$string) {
            return false;
        }
        $prefix_url = '/' . VerstakManager::$prefix . '/common/';
        if('@' == mb_substr($string, 0, 1)) {
            $url = $prefix_url . mb_substr($string, 1);
        } else {
            $url = $string;
        }
        
        return $url;
    }
    
    function frameBlock() {
        \Config::set('larakit.lk-staticfiles.js.external.build', false);
        \Config::set('larakit.lk-staticfiles.js.external.min', false);
    
        $this->initStaticiles();
        $block = \Route::input('block');
        LkPage::instance()->body()->setAttribute('class', '');
        $theme = \Request::input('theme');
        if($theme) {
            LkPage::instance()->body()
                ->addClass('theme--' . $theme);
        }
        
        return $this->setLayout('lk-verstak::!.layouts.frame_block')
            ->response([
                'block' => $block,
                'theme' => $theme,
            ]);
    }
    
    function framePage() {
        \Config::set('larakit.lk-staticfiles.js.external.build', false);
        \Config::set('larakit.lk-staticfiles.js.external.min', false);
    
        $this->initStaticiles();
        //        dd(VerstakManager::blocks());
        \Config::set('app.debug', false);
        $page  = \Route::input('page');
        $theme = \Request::input('theme');
        if($theme) {
            LkPage::instance()->body()->addClass('theme--'.$theme);
        }
        Js::instance()->addInline('
        $("a").on("click", function(e){
            if("#"==$(this).attr("href")){
                e.preventDefault();
            }
        });
        ');
        $breakpoint = (int)\Request::input('breakpoint');
        if($breakpoint) {
            Css::instance()->addInline('
.no-scroll::-webkit-scrollbar { width: 0; }

/* ie 10+ */
.no-scroll{ -ms-overflow-style: none; }

/* фф (свойство больше не работает, других способов тоже нет)*/
.no-scroll{ overflow: -moz-scrollbars-none; }            
            ');
            LkPage::instance()->body()->addClass('no-scroll');
        }
        
        return $this
            ->setLayout('lk-verstak::pages.' . $page)
            ->response([
                'page'        => $page,
                'theme'       => $theme,
            ]);
    }
    
}
