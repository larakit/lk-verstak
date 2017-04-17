<?php
namespace Larakit\Verstak;

use Larakit\ServiceProvider;
use Symfony\Component\Finder\SplFileInfo;

class LarakitServiceProvider extends \Illuminate\Support\ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot() {
        Manager::setPrefix(env('VERSTAKDIR', '!/static'));
        //        $this->larapackage('larakit/lk-verstak', 'lk-verstak');
        $this->loadViewsFrom(base_path('vendor/larakit/lk-verstak/src/views'), 'lk-verstak');
        if(file_exists(Manager::getPath())){
            $this->loadViewsFrom(Manager::getPath(), 'lk-verstak');
        }
        $this->verstakBlocks();
        $this->verstakPages();
        $this->verstakThemes();
        \Larakit\StaticFiles\Manager::package('larakit/lk-verstak')
                                    ->cssPackage('font-awesome/css/font-awesome.min.css')
                                    ->jsPackage('js/angular.js')
                                    ->js('/verstak/js');
    }

    function verstakThemes() {
        $themes_path = Manager::getPath('themes');
        if(file_exists($themes_path)) {
            $themes = [];
            $theme  = \Request::input('theme');
            \Larakit\StaticFiles\Manager::package('verstak-themes')
                                        ->usePackage('verstak-blocks');

            foreach(\File::allFiles($themes_path) as $f) {
                $name = str_replace('.css', '', $f->getFilename());
                if($theme == $name) {
                    \Larakit\StaticFiles\Manager::package('verstak-themes')
                                                ->css(Manager::getUrl('themes/' . $name . '.css'));
                }
                $themes[$name] = $name;
            }
            if(count($themes)) {
                \Larakit\Page\PageTheme::setThemes($themes);
            }
        }
    }

    function verstakPages() {
        $pages_path = Manager::getPath('pages');
        if(file_exists($pages_path)) {
            foreach(\File::allFiles($pages_path) as $file) {
                Manager::page(str_replace('.twig', '', $file->getFilename()));
            }
        }
    }

    function verstakBlocks() {
        $blocks_path = str_replace('\\', '/', Manager::getPath('blocks'));
        if(file_exists($blocks_path)) {
            foreach(\File::allFiles($blocks_path) as $f) {
                /** @var SplFileInfo $f */
                if('block.twig' == $f->getFilename()) {
                    $path = $f->getPath();
                    $path = str_replace('\\', '/', $path);
                    $path = trim(str_replace($blocks_path, '', $path), '/');
                    Manager::block($path);
                }
            }
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [];
    }

}