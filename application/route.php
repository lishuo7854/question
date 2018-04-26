<?php


use think\Route;
Route::rule('demo','demo/Demos/demo');
Route::rule('get_code','demo/Demos/get_code');
Route::rule('valid_token','demo/Demos/valid_token');
Route::rule('user_ranks','demo/Demos/user_rank');

Route::rule('index','demo/Index/index');

Route::rule('question','demo/Index/question');

Route::rule('results','demo/Index/results');

