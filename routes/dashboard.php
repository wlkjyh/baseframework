<?php

use Illuminate\Support\Facades\Route;

/**
 * The BaseFramework Dashboard Route
 */

Route::group(['prefix'=>'/dashboard'],function(){

    Route::group(['prefix'=>'/auth'],function(){
        Route::get('/login',fn() => view('dashboard.auth.login'))->name('auth.login');
        Route::post('/login',[\App\Http\Controllers\AuthController::class,'login'])->name('auth.login.submit');
        Route::get('/logout',[\App\Http\Controllers\AuthController::class,'logout'])->name('auth.logout');
    });

    Route::group(['prefix'=>'/web','middleware'=>['auth.dashboard']],function(){
        Route::get('/',[\App\Http\Controllers\DashboardController::class,'index'])->name('web.index');
        Route::get('/getinfo',[\App\Http\Controllers\DevelopController::class,'getinfo'])->name('web.getinfo');


        //    开发集成工具
        Route::group(['prefix'=>'/develop'],function (){
            // 菜单管理
            Route::get('/menu',[\App\Http\Controllers\DevelopController::class,'menu'])->name('develop.menu');
            Route::post('/menu/create',[\App\Http\Controllers\DevelopController::class,'menuCreate'])->name('develop.menu.create');
            Route::post('/menu/saveTree',[\App\Http\Controllers\DevelopController::class,'menuSaveTree'])->name('develop.menu.save');
            Route::get('/menu/delete',[\App\Http\Controllers\DevelopController::class,'menuDelete'])->name('develop.menu.delete');
            Route::get('/menu/edit',[\App\Http\Controllers\DevelopController::class,'menuEdit'])->name('develop.menu.edit');
            Route::post('/menu/edit/submit',[\App\Http\Controllers\DevelopController::class,'menuEditSubmit'])->name('develop.menu.edit.submit');
        //    角色管理
            Route::get('/role',[\App\Http\Controllers\DevelopController::class,'role'])->name('develop.role');
            Route::get('/role/create',fn() => view('dashboard.develop.roleCreate'))->name('develop.role.create');
            Route::post('/role/create',[\App\Http\Controllers\DevelopController::class,'roleCreateSubmit'])->name('develop.role.create.submit');
            Route::get('/role/edit/{id}',[\App\Http\Controllers\DevelopController::class,'roleEdit'])->name('develop.role.edit');
            Route::post('/role/edit/submit',[\App\Http\Controllers\DevelopController::class,'roleEditSubmit'])->name('develop.role.edit.submit');
            Route::get('/role/delete/{id}',[\App\Http\Controllers\DevelopController::class,'roleDelete'])->name('develop.role.delete');
            // URL权限
            Route::get('/permission',[\App\Http\Controllers\DevelopController::class,'permission'])->name('develop.permission');
            Route::get('/permission/create',fn() => view('dashboard.develop.permissionCreate'))->name('develop.permission.create');
            Route::post('/permission/create',[\App\Http\Controllers\DevelopController::class,'permissionCreateSubmit'])->name('develop.permission.create.submit');
            Route::get('/permission/edit/{id}',[\App\Http\Controllers\DevelopController::class,'permissionEdit'])->name('develop.permission.edit');
            Route::post('/permission/edit/submit',[\App\Http\Controllers\DevelopController::class,'permissionEditSubmit'])->name('develop.permission.edit.submit');
            Route::get('/permission/delete/{id}',[\App\Http\Controllers\DevelopController::class,'permissionDelete'])->name('develop.permission.delete');
            // Route::get('/test',[\App\Http\Controllers\DevelopController::class,'test'])->name('develop.test');


        });



    });


});
