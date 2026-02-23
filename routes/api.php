<?php

use App\Http\Controllers\Api\ComplexStatController;
use App\Http\Controllers\Api\PremiseController;

Route::get('/premises', [PremiseController::class, 'index']);
Route::get('/premises/{premise}', [PremiseController::class, 'show']);
Route::get('/complexes/stats', [ComplexStatController::class, 'index']);



