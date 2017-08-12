<?php

Larakit\Boot::register_boot(__DIR__ . '/boot');
Larakit\Boot::register_command(\Larakit\Verstak\CommandVerstakExample::class);

\Larakit\StaticFiles\Manager::package('larakit/sf-angular')
    ->addInclude('verstak');
\Larakit\StaticFiles\Manager::package('larakit/sf-jquery')
    ->addInclude('verstak');
\Larakit\StaticFiles\Manager::package('larakit/sf-angular-cookies')
    ->addInclude('verstak');
\Larakit\StaticFiles\Manager::package('larakit/lk-verstak')
    ->cssPackage('css/normalize.css')
    ->cssPackage('fonts/uni-sans/uni-sans.css')
    ->cssPackage('css/verstak.css')
    ->setSourceDir('public')
    ->addExclude('*')
    ->addInclude('verstak');
\Larakit\Boot::register_view_path(__DIR__.'/views', 'lk-verstak');

//\Larakit\StaticFiles\Manager::conditions(null, '*', 'verstak*');
//\Larakit\StaticFiles\Manager::conditions('larakit/sf-angular', 'verstak', []);
//\Larakit\StaticFiles\Manager::conditions('larakit/lk-verstak', 'verstak', []);

if(class_exists('\Larakit\Twig')) {
    \Larakit\Twig::register_function('verstak_block', function ($block_name, $props = []) {
        return view('lk-verstak::blocks.' . $block_name . '.block', $props);
    });
    \Larakit\Twig::register_function('verstak_url', function ($resource) {
        return '/' . \Larakit\Verstak\VerstakManager::$prefix . '/' . trim($resource);
    });
    \Larakit\Twig::register_function('verstak_page_url', function ($page_name) {
        return  '/verstak/frame-page-' . $page_name . '?theme=' . Request::input('theme') . '&breakpoint=' . Request::input('breakpoint');
    });
}

\Larakit\Boot::register_view_path(__DIR__ . '/views', 'lk-verstak');
