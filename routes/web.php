<?php

use Illuminate\Support\Facades\Route;

Route::get('/{app?}',[\App\Http\Controllers\HomeController::class,'index'])->where('app','.*');
