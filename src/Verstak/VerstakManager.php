<?php
namespace Larakit\Verstak;

class VerstakManager {
    
    static $prefix      = null;
    static $pages       = [];
    static $blocks      = null;
    static $breakpoints = [];
    static $themes      = [];
    
    public static function getUrl($sub = null) {
        return '/' . self::$prefix . DIRECTORY_SEPARATOR . $sub;
    }
    
    static function init() {
        
        self::$prefix      = trim(env('VERSTAK_PREFIX', '!/static'), '/');
        self::$breakpoints = explode(',', env('VERSTAK_BREAKPOINTS', '1920,1440,1366,1280,1080,1024,900,854,800,768,640,480,360,320'));
        self::$breakpoints = array_unique(self::$breakpoints);
        sort(self::$breakpoints);
        self::$breakpoints = array_map('intval', self::$breakpoints);
        
        //РЕГИСТРАЦИЯ СТРАНИЦ
        
        //РЕГИСТРАЦИЯ COMMON-СТАТИКИ
        
        //РЕГИСТРАЦИЯ ТЕМ ОФОРМЛЕНИЯ
        
    }
    
    static function make_path($element) {
        return public_path($element);
    }
    
    static function registerBlock() {
        $dir = public_path(self::$prefix . '/blocks');
        $dir = str_replace('\\', '/', $dir);
        foreach(\File::directories($dir) as $directory) {
            $directory      = str_replace('\\', '/', $directory);
            $directory      = str_replace($dir . '/', '', $directory);
            self::$blocks[] = $directory;
        }
    }
    
    static function registerPages() {
        $dir = public_path(self::$prefix . '/pages');
        $dir = str_replace('\\', '/', $dir);
        foreach(\File::allFiles($dir) as $file) {
            if('twig' == $file->getExtension() && 'file' == $file->getType()) {
                $page          = $file->getBasename();
                self::$pages[] = mb_substr($page, 0, -5);
            }
            
        }
    }
    
    static function registerThemes() {
        $dir            = public_path(self::$prefix . '/themes');
        $dir            = str_replace('\\', '/', $dir);
        self::$themes[] = 'default';
        foreach(\File::allFiles($dir) as $file) {
            if('css' == $file->getExtension() && 'file' == $file->getType()) {
                $page           = $file->getBasename();
                self::$themes[] = mb_substr($page, 0, -4);
            }
            
        }
    }
}