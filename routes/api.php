<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ProductController;

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

Route::group([ 'prefix' => 'auth' ], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});


$router->group([ 'prefix' => 'profile' ], function() use ($router) {
    $router->get('/products', [ProductController::class, 'showFeatured']);
    // $router->get('/articles', 'ArticleController@showFeatured');
    
    $router->group([ 'middleware' => 'master' ], function() use ($router) {
        // $router->put('/articles', 'ArticleController@setFeatured');
        // $router->put('/products', 'ProductController@setFeatured');
    });
});

$router->group([ 'prefix'=>'article' ], function() use($router) {
    $router->get('/', [ArticleController::class, 'index']);
    $router->get('/category', [ArticleCategoryController::class, 'indexCategory']);
    

    $router->group([ 'middleware' => 'admin' ], function() use($router) {
        $router->put('/', [ArticleController::class, 'store']);
        $router->get('/user', [ArticleController::class, 'indexOwned']);
        $router->post('/image', [ArticleController::class, 'uploadImage']);
        $router->put('/category', [ArticleCategoryController::class, 'store']);
        $router->patch('/category/{id}', [ArticleCategoryController::class, 'update']);
        $router->delete('/category/{id}', [ArticleCategoryController::class, 'destroy']);
        $router->patch('/{id}', [ArticleController::class, 'update']);
        $router->delete('/{id}', [ArticleController::class, 'destroy']);
    });

    $router->group([ 'middleware' => 'master' ], function() use($router) {
        $router->patch('/man', [ArticleController::class, 'updateSomebody']);
        $router->delete('/man', [ArticleController::class, 'destroySomebody']);
        $router->post('/man/all', [ArticleController::class, 'indexAll']);
        $router->post('/man/delete', [ArticleController::class, 'indexTrashed']);
        $router->delete('/man/delete', [ArticleController::class, 'nukeSomebody']);

        
    });

    $router->get('/{id}', [ArticleController::class, 'show']);
});

$router->group([ 'prefix' => 'faq' ], function() use ($router) {
    $router->get('/', [FAQController::class, 'index']);

    $router->group([ 'middleware' => 'master' ], function() use ($router) {
        $router->put('/', [FAQController::class, 'store']);
        $router->patch('/', [FAQController::class, 'update']);
        $router->delete('/', [FAQController::class, 'destroy']);
        $router->post('/user', [FAQController::class, 'indexOwned']);
        $router->post('/all', [FAQController::class, 'indexAll']);
        $router->post('/delete', [FAQController::class, 'indexTrashed']);
        $router->delete('/delete', [FAQController::class, 'nuke']);
    });
});

$router->group([ 'prefix' => 'product' ], function() use ($router) {
    $router->get('/', [ProductController::class, 'index']);
    $router->get('/{id}', [ProductController::class, 'show']);

    $router->group([ 'middleware' => 'admin' ], function() use($router) {
        $router->put('/', [ProductController::class, 'store']);
        $router->patch('/', [ProductController::class, 'update']);
        $router->delete('/', [ProductController::class, 'destroy']);
        $router->post('/user', [ProductController::class, 'indexOwned']);
    });
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
