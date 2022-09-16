<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
});
Route::post("/todo/new",[App\Http\Controllers\TodolistController::class,"store"]);
Route::get("/todo/getall",[App\Http\Controllers\TodolistController::class,"getall"]);
Route::post("/todo/delete",[App\Http\Controllers\TodolistController::class,"delete"]);
Route::post("/todo/updateTodoDone",[App\Http\Controllers\TodolistController::class,"updateStateToDoItem"]);
Route::post("/todo/getRecodorsTodoSearch",[App\Http\Controllers\TodolistController::class,"searchExistingTodo"]);
Route::post("/todo/updateItemTodoByPk",[App\Http\Controllers\TodolistController::class,"updateItemTodoByPk"]);
