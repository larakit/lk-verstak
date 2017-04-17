<?php
\Larakit\Verstak\VerstakManager::init();
Larakit\Boot::register_boot(__DIR__ . '/boot');
Larakit\Boot::register_command(\Larakit\Verstak\CommandVerstakExample::class);

\Larakit\StaticFiles\Manager::package('larakit/lk-verstak')
    ->cssPackage('css/normalize.css')
    ->cssPackage('fonts/uni-sans/uni-sans.css')
    ->cssPackage('css/verstak.css')
    ->setSourceDir('public')
    ->addExclude('*')
    ->addInclude('verstak');

//\Larakit\StaticFiles\Manager::conditions(null, '*', 'verstak*');
//\Larakit\StaticFiles\Manager::conditions('larakit/sf-angular', 'verstak', []);
//\Larakit\StaticFiles\Manager::conditions('larakit/lk-verstak', 'verstak', []);

\Larakit\Twig::register_function('verstak_block', function ($block_name, $props = []) {
    return view('lk-verstak::blocks.' . $block_name . '.block', $props);
});

\Larakit\Twig::register_function('verstak_url', function ($page_name) {
    $url = '/verstak/frame-page-' . $page_name . '?theme=' . Request::input('theme') . '&breakpoint=' . Request::input('breakpoint');
    
    return HtmlA::setHref($url);
});
\Larakit\Boot::register_view_path(__DIR__ . '/views', 'lk-verstak');
