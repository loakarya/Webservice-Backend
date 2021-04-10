<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyAccountController;

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
    Route::get('/login', function() {
        return response()->json(['message' => 'Please check your auth token!'], 401);
    });
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth');
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'The email has been verified!']);
})->middleware(['auth'])->name('verification.verify');

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
        $router->delete('/{id}', [ProductController::class, 'destroy']);
        $router->post('/user', [ProductController::class, 'indexOwned']);
        $router->post('/image', [ProductController::class, 'uploadImage']);
    });
});

$router->group( ['prefix' => 'user' ], function() use($router) {
    $router->put('/', [UserController::class, 'store']);
    
    
    $router->group([ 'middleware' => 'auth:api' ], function() use($router) {
        $router->patch('/', [UserController::class, 'updateDetails']);
        $router->delete('/', [UserController::class, 'destroy']);
        $router->post('/', [UserController::class, 'show']);
        $router->patch('/email', [UserController::class, 'updateEmail']);
        $router->patch('/password', [UserController::class, 'updatePassword']);
        $router->patch('/username', [UserController::class, 'updateUsername']);
    });
    
    $router->group([ 'middleware' => 'admin' ], function() use($router) {
        $router->post('/man', [UserController::class, 'showUserDetails']);
        $router->patch('/man', [UserController::class, 'updateUserDetails']);
        $router->get('/man', [UserController::class, 'index']);
        $router->post('/man/search', [UserController::class, 'search']);
        $router->delete('/man/{id}', [UserController::class, 'destroyUser']);
    });

    $router->group([ 'middleware' => 'master' ], function() use($router) {
        $router->get('/man/all', [UserController::class, 'indexAll']);
        $router->get('/man/deleted', [UserController::class, 'indexTrashed']);
        $router->delete('/man/deleted', [UserController::class, 'nukeUser']);
        $router->patch('/man/acl/{id}', [UserController::class, 'updateUserAcl']);
    });
});

$router->group( ['prefix' => 'employee' ], function() use($router) {
    
    $router->group([ 'middleware' => 'admin' ], function() use($router) {
        $router->get('/', [EmployeeController::class, 'index']);
        $router->get('/email', [EmployeeController::class, 'EmailLogIn']);
        $router->get('/view/{id}', [EmployeeController::class, 'show']);
    });

    $router->group([ 'middleware' => 'master' ], function() use($router) {
        $router->put('/', [EmployeeController::class, 'store']);
        $router->patch('/', [EmployeeController::class, 'update']);
        $router->patch('/email/randomize', [EmployeeController::class, 'randomizeCompanyEmailPassword']);
        $router->delete('/{id}', [EmployeeController::class, 'destroy']);
    });

});

$router->group( ['prefix' => 'account' ], function() use($router) {
    
    $router->group([ 'middleware' => 'admin' ], function() use($router) {

    });

    $router->group([ 'middleware' => 'master' ], function() use($router) {
        $router->get('/', [CompanyAccountController::class, 'index']);
        $router->put('/', [CompanyAccountController::class, 'store']);
        $router->post('/{id}', [CompanyAccountController::class, 'show']);
        $router->delete('/{id}', [CompanyAccountController::class, 'destroy']);
        $router->patch('/{id}', [CompanyAccountController::class, 'update']);
    });
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
