<?php

use App\Http\Controllers\Api\V1\Auth\TaskController;
use Illuminate\Support\Facades\Route;
use CloudCreativity\LaravelJsonApi\Facades\JsonApi;

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

Route::namespace('Api\V1\Auth')->prefix('api/v1')->middleware('json.api')->group(function () {
    Route::post('/login', 'LoginController')->name('login');
    Route::post('/register', 'RegisterController')->name('register');
    Route::post('/logout', 'LogoutController')->middleware('auth:api')->name('logout');
    Route::post('/password-forgot', 'ForgotPasswordController')->name('password-forgot');
    Route::post('/password-reset', 'ResetPasswordController')->name('password-reset');

    //General Settings
    Route::get('/get-settings', 'SettingsController@getSettings')->name('get-settings');
    Route::patch('/save-settings', 'SettingsController@saveSettings')->name('save-settings');
    Route::get('/get-social-settings', 'SettingsController@getSocialSettings')->name('get-social-settings');
    Route::post('/test', 'SettingsController@test')->name('test');
    
    //Sidebar
    Route::get('/sidebar-image', 'SidebarImageController@getSidebars')->name('sidebar-image');
    Route::get('/sidebar-image-to-edit', 'SidebarImageController@getSidebarsToEdit')->name('sidebar-image-to-edit');
    Route::post('/save-sidebars', 'SidebarImageController@saveSidebars')->name('save-sidebars');
    Route::get('/sidebar-image-active', 'SidebarImageController@activeSidebar')->name('sidebar-image-active');
    Route::get('/sidebar-image-activate/{id}', 'SidebarImageController@activateImage')->name('sidebar-image-activate');
    Route::get('/get-image-sidebar/{image?}', 'SidebarImageController@getImageSidebar')->name('get-image-sidebar');

    //Roles

    Route::get('/list-roles', 'RoleController@list')->name('list-roles');


    //Usuarios 
    Route::post('/create-user', 'CreateUserController')->name('create-user');
    Route::get('/info-user/{id}', 'InfoUserController')->name('info-user');
    Route::patch('/user-profile-update/{user}/{authUser}', 'UserProfileUpdateController')->name('user-profile-update');
    Route::patch('/user-password-update/{user}/{authUser}', 'UserUpdatePasswordController')->name('user-password-update');
    Route::delete('/delete-user/{id}', 'DeleteUserController')->name('delete-user');
    Route::get('/list-users/{perPage?}/{field?}/{order?}/{search?}', 'ListUsersController')->name('list-users');
    Route::post('/image-profile-update', 'ImageProfileUpdateController')->name('image-profile-update');
    Route::get('/get-experiences/{user}', 'UserExperienceController@getExperiences')->name('get-experiences');
    Route::post('/modify-experiences',  'UserExperienceController@modifyExperiences')->name('modify-experiences');
    Route::delete('/delete-experience/{id}', 'UserExperienceController@deleteExperience')->name('delete-experience');
    Route::get('/user-about', 'UserAboutController@index')->name('user-about');
    

    
    //Posts

    Route::get('/list-posts', 'PostController@index')->name('list-posts');
    Route::get('/get-post/{id}', 'PostController@edit')->name('get-post');
    Route::post('/create-post', 'PostController@save')->name('create-post');
    Route::patch('/update-post/{post}', 'PostController@update')->name('update-post');
    Route::delete('/delete-post/{id}', 'PostController@destroy')->name('delete-post');
    Route::get('/featured-to-home','PostController@homeFeatured')->name('featured-to-home');
    Route::get('/posts-to-blog', 'PostController@postsToBlog')->name('posts-to-blog');
    Route::post('/post-visits', 'PostController@postVisits')->name('post-visits');


    //Categories

    Route::get('/list-categories', 'CategoryController@index')->name('list-categories');
    Route::get('/categories-list', 'CategoryController@listCategories')->name('categories-list');
    Route::get('/parents-categories', 'CategoryController@parents')->name('parents-categories');
    Route::post('/create-category', 'CategoryController@create')->name('create-category');
    Route::get('/get-category/{id}', 'CategoryController@edit')->name('get-category');
    Route::patch('/update-category/{category}', 'CategoryController@update')->name('update-category');
    Route::delete('/delete-category/{id}', 'CategoryController@destroy')->name('delete-category');
    Route::patch('/set-default/{category}', 'CategoryController@setDefault')->name('set-default');

    //Tasks
    Route::get('/parents-tasks', 'TaskController@parents')->name('parents-tasks');
    Route::get('/list-tasks', 'TaskController@listTasks')->name('list-tasks');
    Route::get('/children-tasks/{id}', 'TaskController@children')->name('children-tasks');
    Route::post('/create-task', 'TaskController@create')->name('create-task');
    Route::get('/get-task/{id}', 'TaskController@get')->name('get-task');
    Route::get('/get-statuses', 'TaskController@statuses')->name('get-statuses');
    Route::patch('/update-task/{task}', 'TaskController@update')->name('update-task');
    Route::delete('/delete-task/{id}', 'TaskController@destroy')->name('delete-task');
    Route::get('/tasks-statistics', 'TaskController@statistics')->name('tasks-statistics');
    Route::get('/pending-tasks', 'TaskController@pendingTasks')->name('pending-tasks');

    //Media 
    Route::prefix('laravel-filemanager')->group(function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
    Route::post('/add-media', 'MediaController@create')->name('add-media');
    Route::get('/get-media/{image}', 'MediaController@get')->name('get-media');

    //Comments
    
    Route::get('/comments', 'CommentController@fetchComments');
    Route::post('/comments', 'CommentController@store');
   
    //Visits
    Route::get('/get-visits', 'VisitController@getVisits')->name('get-visits');

    
    //PlaceHolders
    Route::get('/get-placeholder/{image?}', 'GetPlaceholderController')->name('get-placeholder');

    
});



JsonApi::register('v1')->middleware('auth:api')->routes(function ($api) {
    $api->get('me', 'Api\V1\MeController@readProfile');
    $api->patch('me', 'Api\V1\MeController@updateProfile');

    $api->resource('users');
});
