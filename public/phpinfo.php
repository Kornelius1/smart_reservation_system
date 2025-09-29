<?php

use Illuminate\Support\Facades\Route;
    
Route::get(uri: '/', action : function () : View {
    return view(view: 'welcome');
});