<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('roles', 'RoleController');
Route::apiResource('permissions', 'PermissionController')
    ->only('index', 'show');

Route::apiResource('users', 'UserController')
    ->except('create');
Route::apiResource('teams', 'TeamController')
    ->except('create');

Route::apiResource('teams.projects', 'ProjectController')
    ->shallow();
Route::apiResource('teams.languages', 'LanguageController')
    ->shallow()->except('index');
Route::apiResource('teams.forms', 'FormController')
    ->shallow()->except('index');
Route::apiResource('projects.keys', 'KeyController')
    ->shallow();
Route::apiResource('keys.values', 'ValueController')
    ->shallow()->except('index');

Route::apiResources([
    'users.roles' => 'UserRoleController',
    'teams.users' => 'TeamUserController',
    'projects.users' => 'ProjectUserController',
    'projects.languages' => 'ProjectLanguageController',
], [
    'only' => [
        'store',
        'destroy',
    ],
]);