<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/task', App\Http\Controllers\Api\toDoListController::class);

//filter task
Route::get('/task/{column}/value={value}', [App\Http\Controllers\Api\toDoListController::class, 'filterTask']);