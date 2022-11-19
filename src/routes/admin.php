<?php

use A17\TwillHttpBasicAuth\Support\Facades\Route;
use A17\TwillHttpBasicAuth\Http\Controllers\TwillHttpBasicAuthController;

Route::name('TwillHttpBasicAuth.redirectToEdit')->get('/TwillHttpBasicAuths/redirectToEdit', [
    TwillHttpBasicAuthController::class,
    'redirectToEdit',
]);

// @phpstan-ignore-next-line
Route::module('TwillHttpBasicAuth');
