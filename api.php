<?php


use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::match(['get', 'post'], '/', [\App\Http\Controllers\Controller::class, 'index']);


Route::post('/user/{id}', [\App\Http\Controllers\Controller::class, 'insert'])->where('id', '[0-9]+');


Route::post('/user/post/{postID}/comment/{commentText?}', [\App\Http\Controllers\Controller::class, 'insertToPost'])->whereNumber('postID')->whereAlphaNumeric('commentText');

//group of routes
 Route::controller(\App\Http\Controllers\Controller::class)->group(function (){
    Route::get('/order/{id}' , 'showOrder') ;
    Route::get('/orders' , 'showOrders') ;
 });

//Subdomain Routing
Route::domain('{account}.hemndev.com')->group(function (){
   Route::get('/user/{id}' , function ($account , $id){
      return response()->json(['subdomain'=>$account , 'id'=>$id],200);
   });
});

Route::prefix('admin')->group(function (){
    Route::get('/hemn' , function (){
        return 'welcome' ;
    });
});




//Implicit Enum Binding
use App\Enums\Category;
//use Illuminate\Support\Facades\Route;

Route::get('/categories/{category}', function (Category $category) {
    return $category->value;
});


Route::match(['get' , 'post' , 'put' , 'delete'] , '/match' , function(){
    return "Hello Match" ;
}) ;

Route::any('/any' , function (){
    return 'Hello any'  ;
});

Route::redirect('/welcome' , '/' , 301) ;

Route::get('/id/{id}/stage/{stage}' ,  function ($id , $stage){
    return response()->json(['id'=>$id , 'stage'=>$stage],200);
}) ;

 Route::get('/bodyRequest/{id}' , function(Request $request , $id){
     return response()->json(['id'=>$id , 'name'=>$request->name],200);
 });

 Route::get('/optional/{name?}' , function ($name = null){
     return response()->json(['name'=>$name],200) ;
 }) ;

 #regular expression
 Route::get('/rgx/{name}' , function ($name){
return  response()->json(['name' => $name],200) ;
 })->where(['name'=>'[a-z]+']) ;

 Route::get('/rgx/{name}/{id}' , function ($name , $id){
    return  response()->json(['name' => $name , 'id' =>$id],200) ;
})->whereNumber('id')->whereAlpha('name') ;

Route::get('global/{id}' , function ($id){
   return response()->json(['id'=>$id],200) ;
});

Route::get('/search/{search}', function ($search) {
    return $search;
})->where('search', '.*');

//esh nakat
Route::get('/namedRoute' , function (){
    return response()->json([ 'named' => 'route'],200) ;
})->named('named_route') ;

Route::get('/named' , function (){
    return redirect()->route('named_route');
}) ;

Route::prefix('admin')->group(function(){
    Route::get('/hemn' , function(){return 'hello admin'  ; }) ;
});

//Route Model Binding  {{ Very Important }} lerada ema ba nardny id boy hamww data i DB dageretawa zor ba asany
///posts/{user:name}  ama bakar bhena agar wistt ba pey name begeritawa
Route::get('/account/{user}' , function (App\Models\User $user){
    return response()->json([
        'name' => $user->name  ,
        'email' => $user->email ,
        'password' => $user->password ,

    ],200);
}) ->missing(function (Request $request) {
    return response()->json(['status'=>'Not Found gwlla bax'],404);
});


Route::get('/customizeKey/{user:name}' , function (User $user){
    return response()->json(['name' => $user->id],200)  ;
})->missing(function (){return response()->json(['status'=>'nadozrayawa gyana'],404);}) ;



Route::get('/users/{user}/posts/{post}', function (User $user, Post $post) {
    return $post;
}) ;

Route::get('/categories/{category}', function (Category $category) {
    return $category->value;
});

// la ha
Route::fallback(function (){
    return response()->json(['status'=>'not found gwla baybwn'],404);
});
//\Illuminate\Support\Facades\Redirect::route('/') ;  awtwanyt lasarawa ama bakar bhenit bo redirect krdn bo pagek
//https://www.google.com/
