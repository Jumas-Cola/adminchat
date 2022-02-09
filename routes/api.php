<?php

use JumasCola\AdminChat\AdminChat;
use JumasCola\AdminChat\Http\Controllers\AdminChatMessageController;

$auth = AdminChat::config('auth_middleware', 'auth:api');

Route::middleware($auth)->group(function () use ($auth) { 
    Route::get('adminchat/messages', AdminChatMessageController::class.'@index')
        ->middleware($auth);
    Route::post('adminchat/messages', AdminChatMessageController::class.'@store')
        ->middleware($auth);
});
