<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\StoryPartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryDispController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\StryPartLikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePicUploadController;
use App\Http\Controllers\SubscriberController;
use App\Models\SubscriberModel;
use App\Http\Controllers\StryPartCmntController;
use App\Models\StryPartCmntModel;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\dashController;
use App\Http\Controllers\wrtieNewStory;
use App\Http\Controllers\typestrupnw;
use App\Http\Controllers\editStory;
use App\Http\Controllers\updateEditedStory;
use App\Http\Controllers\finallyUpdateStory;
use App\Http\Controllers\delStory;
use App\Http\Controllers\adminUsers;
use App\Http\Controllers\MsgController;
use App\Http\Controllers\ThoughtsController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BookStoreController;
use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\AdminCommentsController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminProfileController;

// Backward compatible asset route (Render/Linux is case-sensitive: /Images != /images)
Route::get('/Images/{path}', function (string $path) {
    $base = realpath(public_path('images'));
    if ($base === false) {
        abort(404);
    }

    $candidate = public_path('images/' . $path);
    $resolved = realpath($candidate);

    if (
        $resolved === false
        || !str_starts_with($resolved, $base . DIRECTORY_SEPARATOR)
        || !is_file($resolved)
    ) {
        abort(404);
    }

    return response()->file($resolved);
})->where('path', '.*');
// display index elements 
Route::get('/', [App\Http\Controllers\IndexController::class, 'idexDisplay']);

Route::get('/all_stories', [App\Http\Controllers\AllStoryController::class, 'show']);

Route::get('/about_me', function () {
    return  view('homeAndStories.aboutme');
});

Route::get('/support-chat', function () {
    return view('homeAndStories.support_chat');
});

// all category
Route::get('/category', [CategoryController::class, 'show']);

// show all story related to category
Route::get('/catedisp/{id}/{hash}', [CategoryDispController::class, 'show']);
// error
Route::get('/error', function () {
    return view('errors.404');
});

Route::get('/Log_Out', function () {

    session()->forget(['usnm', 'loginstat']);
    return redirect('/Log_In');
});
// for showing stories 
Route::get('/stories/{id}/{hash}', [App\Http\Controllers\ShowStoryController::class, 'show']);

// for showing story parts
Route::get('/storiespart/{id}/{hash}/{stryId?}', [StoryPartController::class, 'show']);
//  submit registration post request 
Route::post('/Sign_Up', [\App\Http\Controllers\SignUpController::class, 'show']);


Route::get('/Sign_Up', function () {
    if (session()->has(['usnm', 'loginstat'])) {
        return redirect('/');
    } else {

        return view('loginSignup.userSignup');
    }
});

//  login post request 
Route::post('/Log_In', [\App\Http\Controllers\LogInController::class, 'show']);
Route::get('/Log_In', function () {

    if (session()->has(['usnm', 'loginstat'])) {
        return redirect('/');
    } else {

        return view('loginSignup.userLogin');
    }
});

// read stories and fetch from db

// like for story

Route::post('/like', [App\Http\Controllers\LikeController::class, 'show']);
// part stry like
Route::post('/likes', [App\Http\Controllers\StryPartLikeController::class, 'show']);
// comment for story

Route::post('/cpmt/{name}', [CommentController::class, 'show']);

// story part cmnts
Route::post('/spcpmt/{idk}', [StryPartCmntController::class, 'show']);

// profile section

Route::get('/profile', [ProfileController::class, 'show']);
// edit profile pic
Route::post('/uploadusrpic', [ProfilePicUploadController::class, 'show']);

// subscribe notifications
Route::post('/subscriber', [SubscriberController::class, 'show']);


Route::get('/PrivacyPolicy', function () {

    return view('privacyTerms.privacyPolicy');
});

Route::get('/TermsAndCond', function () {

    return view('privacyTerms.TermsAndCondition');
});

Route::get('/disclm', function () {

    return view('privacyTerms.disclaimer');
});


Route::post('/help', [HelpController::class, 'show']);
Route::get('/chat/session', [HelpController::class, 'sessionInfo']);
Route::get('/chat/messages', [HelpController::class, 'messages']);

// Book Store (User Panel)
Route::get('/books', [BookStoreController::class, 'books']);
Route::post('/books/cart/add/{bookId}', [BookStoreController::class, 'addToCart']);
Route::post('/books/buy/{bookId}', [BookStoreController::class, 'buyNow']);
Route::get('/cart', [BookStoreController::class, 'cart']);
Route::post('/cart/update/{cartId}', [BookStoreController::class, 'updateCart']);
Route::post('/cart/remove/{cartId}', [BookStoreController::class, 'removeFromCart']);
Route::post('/checkout/razorpay/order', [BookStoreController::class, 'createRazorpayOrder']);
Route::post('/checkout/razorpay/verify', [BookStoreController::class, 'verifyRazorpayPayment']);
Route::get('/my-orders', [BookStoreController::class, 'myOrders']);
Route::post('/books/free/{bookId}/claim', [BookStoreController::class, 'claimFreeBook']);
Route::get('/my-library', [BookStoreController::class, 'myLibrary']);
Route::get('/books/read/{bookId}', [BookStoreController::class, 'readBook']);
Route::get('/books/resale', [BookStoreController::class, 'resaleMarket']);
Route::post('/books/resale/list/{orderItemId}', [BookStoreController::class, 'createResaleListing']);
Route::post('/books/resale/buy/{listingId}', [BookStoreController::class, 'buyResale']);
Route::post('/books/resale/razorpay/order/{listingId}', [BookStoreController::class, 'createResaleRazorpayOrder']);
Route::post('/books/resale/razorpay/verify', [BookStoreController::class, 'verifyResaleRazorpayPayment']);


// Admin Related services

Route::get('/admin', [AdminAuthController::class, 'showLogin']);
Route::post('/admin', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
Route::get('/AdLog', [AdminAuthController::class, 'showLogin']);

Route::middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [dashController::class, 'show']);

    Route::get('/dash',  [dashController::class, 'dash']);

    Route::get('/wrst', [typestrupnw::class, 'show']);

    Route::get('/edst', [editStory::class,'show']);
    Route::get('/cmntget', [AdminCommentsController::class, 'index']);
    Route::post('/admin/comments/delete/{type}/{id}', [AdminCommentsController::class, 'destroy']);

    Route::get('/admin/books', [AdminBookController::class, 'booksPage']);
    Route::get('/admin/orders', [AdminBookController::class, 'ordersPage']);
    Route::get('/admin/profile', [AdminProfileController::class, 'show']);
    Route::post('/admin/books', [AdminBookController::class, 'addBook']);
    Route::post('/admin/books/update/{bookId}', [AdminBookController::class, 'updateBook']);
    Route::post('/admin/books/delete/{bookId}', [AdminBookController::class, 'deleteBook']);
    Route::post('/admin/orders/update/{orderId}', [AdminBookController::class, 'updateOrderStatus']);
    Route::get('/modist/{stid}', [updateEditedStory::class,'show']);

    Route::post('/strupnw', [wrtieNewStory::class, 'show']);

    Route::post('/updsted', [finallyUpdateStory::class, 'show']);

    Route::get('/strupnw', function(){
        return view('errors.404');
    });
    Route::post('/delistlo',[delStory::class,'show']);

    Route::get('/thgt',function(){
        $th=  DB::table('thoughts')->select('Mainthought')->get();

        return view('admin.thoughts')->with(['thgt'=>$th]);
    });
    Route::post('/thgt',[ThoughtsController::class,'show'] );
    Route::get('/ussr', [adminUsers::class,'show']);
    Route::post('/admin/users/{sNo}/status', [adminUsers::class,'updateStatus']);

    Route::get('/msg', [MsgController::class,'show']);
    Route::get('/admin/chat/sessions', [MsgController::class, 'sessions']);
    Route::get('/admin/chat/sessions/{chatToken}/messages', [MsgController::class, 'messages']);
    Route::post('/admin/chat/sessions/{chatToken}/messages', [MsgController::class, 'send']);
});

// Author Related Services
use App\Http\Controllers\AuthorAuthController;
use App\Http\Controllers\AuthorDashboardController;
use App\Http\Controllers\AuthorStoryController;
use App\Http\Controllers\AuthorProfileController;

Route::get('/author/login', [AuthorAuthController::class, 'showLogin'])->name('author.login');
Route::post('/author/login', [AuthorAuthController::class, 'login']);
Route::get('/author/signup', [AuthorAuthController::class, 'showSignup']);
Route::post('/author/signup', [AuthorAuthController::class, 'signup']);
Route::post('/author/logout', [AuthorAuthController::class, 'logout'])->name('author.logout');

Route::middleware('author.auth')->group(function () {
    Route::get('/author/dashboard', [AuthorDashboardController::class, 'index'])->name('author.dashboard');

    Route::get('/author/profile', [AuthorProfileController::class, 'show'])->name('author.profile');
    Route::post('/author/profile', [AuthorProfileController::class, 'update'])->name('author.profile.update');

    Route::get('/author/stories', [AuthorStoryController::class, 'index'])->name('author.stories.index');
    Route::get('/author/stories/create', [AuthorStoryController::class, 'create'])->name('author.stories.create');
    Route::post('/author/stories', [AuthorStoryController::class, 'store'])->name('author.stories.store');
    Route::get('/author/stories/{storyId}/edit', [AuthorStoryController::class, 'edit'])->name('author.stories.edit');
    Route::post('/author/stories/{storyId}', [AuthorStoryController::class, 'update'])->name('author.stories.update');
    Route::post('/author/stories/{storyId}/delete', [AuthorStoryController::class, 'destroy'])->name('author.stories.delete');
});
