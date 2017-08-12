<?php

\Route::pattern('block', '.*');
\Route::pattern('page', '.*');

\Larakit\Verstak\VerstakManager::init();

//****************************************
//  Главная
//****************************************
Route::get('/verstak', [
    'uses'=> 'Larakit\Verstak\Controllers\VerstakController@index'
])->name('verstak');
Route::get('/verstak/js', [
    'uses'=> 'Larakit\Verstak\Controllers\VerstakJsController@js'
])->name('verstak/js');
if('production' != App::environment()) {
    Route::get('verstak/download', [
        'uses'=> 'Larakit\Verstak\Controllers\VerstakDownloadController@index'
    ])->name('verstak/download');
}
Route::get('/verstak/frame-block-{block}', [
    'uses'=> 'Larakit\Verstak\Controllers\VerstakFrameController@frameBlock'
])->name('verstak/frame_block');
Route::get('/verstak/frame-page-{page}', [
    'uses'=> 'Larakit\Verstak\Controllers\VerstakFrameController@framePage'
])->name('verstak/frame_page');
\Larakit\Boot::register_view_path(public_path(\Larakit\Verstak\VerstakManager::$prefix), 'lk-verstak');

//РЕГИСТРАЦИЯ БЛОКОВ
\Larakit\Verstak\VerstakManager::registerBlock();
//РЕГИСТРАЦИЯ СТРАНИЦ
\Larakit\Verstak\VerstakManager::registerPages();
//РЕГИСТРАЦИЯ ТЕМ ОФОРМЛЕНИЯ
\Larakit\Verstak\VerstakManager::registerThemes();