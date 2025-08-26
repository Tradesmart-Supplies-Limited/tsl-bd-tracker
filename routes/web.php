<?php

use Illuminate\Support\Facades\Route;


Route::get('/birthdays', function () {
    return view('birthday-tracker.index');
});

Route::get('/birthdays/create', function () {
    return view('birthday-tracker.bio-data');
});

// Add this route for your form view
Route::get('/add-member', function () {
    return view('bio-data');
});
