<?php

use App\Enums\KakaoTemplate;
use App\Models\Kakao;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get("/test", function(){

});


Route::post("/users", [\App\Http\Controllers\UserController::class, "store"]);

Route::get('/', [\App\Http\Controllers\PageController::class, "index"])->name("home");
Route::get('/home', [\App\Http\Controllers\PageController::class, "index"]);



Route::post("/verifyNumbers", [\App\Http\Controllers\Api\VerifyNumberController::class, "store"]);
Route::patch("/verifyNumbers", [\App\Http\Controllers\Api\VerifyNumberController::class, "update"]);

Route::middleware("auth")->group(function(){
    Route::get("/users/remove", [\App\Http\Controllers\UserController::class, "remove"]);
    Route::delete("/users", [\App\Http\Controllers\UserController::class, "destroy"]);
    Route::get("/users/edit", [\App\Http\Controllers\UserController::class, "edit"]);
    Route::post("/users/update", [\App\Http\Controllers\UserController::class, "update"]);
});

Route::middleware("guest")->group(function(){
    Route::get("/login", [\App\Http\Controllers\UserController::class, "loginForm"])->name("login");
    Route::get("/register", [\App\Http\Controllers\UserController::class, "create"]);
    Route::get("/openLoginPop/{social}", [\App\Http\Controllers\UserController::class, "openSocialLoginPop"]);
    Route::get("/login/{social}", [\App\Http\Controllers\UserController::class, "socialLogin"]);
    Route::post("/login", [\App\Http\Controllers\UserController::class, "login"]);
    Route::post("/register", [\App\Http\Controllers\UserController::class, "register"]);
    Route::resource("/users", \App\Http\Controllers\UserController::class);
    Route::get("/passwordResets/{token}/edit", [\App\Http\Controllers\PasswordResetController::class, "edit"]);
    Route::resource("/passwordResets", \App\Http\Controllers\PasswordResetController::class);
});


Route::middleware("auth")->group(function(){
    Route::get("/logout", [\App\Http\Controllers\UserController::class, "logout"]);
    Route::get("/mypage", [\App\Http\Controllers\PageController::class, "mypage"]);
});

Route::get("/mailable", function(){
    return (new \App\Mail\PasswordResetCreated(new \App\Models\User(), new \App\Models\PasswordReset()));
});


// 개발

// 가끔 결제되는 순간 네트워크가 끊길 경우나 가상계좌처럼 입금이 나중에 되는 경우를 대비한 웹훅

Route::middleware("auth")->group(function(){
});

Route::get("/404", [\App\Http\Controllers\ErrorController::class, "notFound"]);
Route::get("/403", [\App\Http\Controllers\ErrorController::class, "unAuthenticated"]);

Route::get("/privacyPolicy", [\App\Http\Controllers\PageController::class, "privacyPolicy"]);

// 기타
Route::get("/contents/privacyPolicy", [\App\Http\Controllers\PageController::class, "privacyPolicy"]); // 개인정보처리방침

Route::get("/contents/planning", [\App\Http\Controllers\PageController::class, "planning"]);
Route::get("/contents/front", [\App\Http\Controllers\PageController::class, "front"]);
Route::get("/contents/design", [\App\Http\Controllers\PageController::class, "design"]);
Route::get("/contents/backend", [\App\Http\Controllers\PageController::class, "backend"]);

Route::get("/columns", [\App\Http\Controllers\ColumnController::class, "index"]);
Route::get("/columns/{column}", [\App\Http\Controllers\ColumnController::class, "show"]);

Route::get("/portfolios", [\App\Http\Controllers\PortfolioController::class, "index"]);
Route::get("/portfolios/{portfolio}", [\App\Http\Controllers\PortfolioController::class, "show"]);

Route::get("/qnas/create", [\App\Http\Controllers\QnaController::class, "create"]);
Route::get("/qnas/{qna}", [\App\Http\Controllers\QnaController::class, "show"]);
Route::post("/qnas", [\App\Http\Controllers\QnaController::class, "store"]);
