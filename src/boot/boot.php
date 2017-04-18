<?php

\Route::pattern('block', '.*');
\Route::pattern('page', '.*');

//****************************************
//  Главная
//****************************************
\Larakit\Route\Route::item('verstak')
    ->setBaseUrl('/verstak')
    ->setNamespace('Larakit\Verstak\Controllers')
    ->put();
\Larakit\Route\Route::item('verstak/js')
    ->setBaseUrl('/verstak/js')
    ->setController('VerstakJs')
    ->setAction('js')
    ->setNamespace('Larakit\Verstak\Controllers')
    ->put();
if('production' != App::environment()) {
    \Larakit\Route\Route::item('verstak/download')
        ->setBaseUrl('/verstak/download')
        ->setNamespace('Larakit\Verstak\Controllers')
        ->setController('VerstakDownload')
        ->put();
}
\Larakit\Route\Route::item('verstak/frame_block')
    ->setBaseUrl('/verstak/frame-block-{block}')
    ->setNamespace('Larakit\Verstak\Controllers')
    ->setController('VerstakFrame')
    ->setAction('frameBlock')
    ->put();
\Larakit\Route\Route::item('verstak/frame_page')
    ->setBaseUrl('/verstak/frame-page-{page}')
    ->setNamespace('Larakit\Verstak\Controllers')
    ->setController('VerstakFrame')
    ->setAction('framePage')
    ->put();
\Larakit\Boot::register_view_path(public_path(\Larakit\Verstak\VerstakManager::$prefix), 'lk-verstak');

//РЕГИСТРАЦИЯ БЛОКОВ
\Larakit\Verstak\VerstakManager::registerBlock();
//РЕГИСТРАЦИЯ СТРАНИЦ
\Larakit\Verstak\VerstakManager::registerPages();
//РЕГИСТРАЦИЯ ТЕМ ОФОРМЛЕНИЯ
\Larakit\Verstak\VerstakManager::registerThemes();