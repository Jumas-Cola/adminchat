<?php

use JumasCola\AdminChat\Http\Controllers\AdminChatController;

Route::get("adminchat/ajax/search", AdminChatController::class . "@search");
Route::get("adminchat/ajax/{user}", AdminChatController::class . "@show_api");
Route::get("adminchat/{user}", AdminChatController::class . "@show");
Route::get("adminchat", AdminChatController::class . "@index");
Route::post("adminchat/{user}", AdminChatController::class . "@store");
