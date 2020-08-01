<?php

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



/*Route::group(['middleware' => 'auth'], function(){
    Route::get('test', function () {
    	$user = \Auth::user();
    	dd($user->role);
    });
});*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('email', function () {
  $user = \Auth::user();
  $customer = App\Customer::where('user_id', $user->id)->first();
  $invoice = App\Order::find(1);
  // Illuminate\Support\Facades\Mail::to($user)->send(new App\Mail\OrderShipped($invoice, $user, $customer));
  // if( count(Illuminate\Support\Facades\Mail::failures()) > 0 ) {
  //     return Illuminate\Support\Facades\Response::json(['status' => 'error', 'msg' => 'Email Not send!']);
  // }else {
  //     \Session::put('success','Your order is confirmed & Order details sent to your email, Enjoy!!');
  //     return Illuminate\Support\Facades\Response::json(['status' => 'success', 'url' => route('home')]);
  // }

  return new App\Mail\OrderShipped($invoice, $user, $customer);
});

// New Route
Route::get('/', 'FrontendControllers\ViewController@index')->name('home');
Route::get('/product-details/{code}', 'FrontendControllers\ViewController@productDetails');
Route::get('/package-details/{code}', 'FrontendControllers\ViewController@packageDetails');
Route::post('/submit-review', 'FrontendControllers\ViewController@submitReview');
Route::get('/store-location', 'FrontendControllers\ViewController@storeLocation');
Route::get('/signin', 'FrontendControllers\ViewController@signin');
Route::get('/signup', 'FrontendControllers\ViewController@signup');
Route::post('/user-login', 'FrontendControllers\ViewController@userSignin');
Route::get('/signout', 'FrontendControllers\ViewController@signout');
Route::post('/user-register', 'FrontendControllers\ViewController@userSignUp');
Route::post('/add-to-cart-product', 'CartController@storeProduct');
Route::get('/cart', 'FrontendControllers\ViewController@cart');
Route::get('/checkout', 'FrontendControllers\ViewController@checkout');
Route::post('/place-order', 'CheckoutController@checkout');

//Route::get('/home', 'HomeController@index');

//Route::get('/', 'ViewController@index')->name('home');

Auth::routes();

Route::get('/admin', 'AdminController@admin')->name('admin')->middleware('admin');
Route::get('/buyer', 'BuyerController@index')->name('buyer')->middleware('buyer');
Route::get('/seller', 'AdminController@seller')->middleware('seller');

//User Access Pages Route
Route::get('admin/access', 'AccessController@index')->name('access')->middleware('admin');
Route::post('admin/userRoll', 'AccessController@userRoll');

Route::get('admin/commition', 'AdminController@commition')->name('commition')->middleware('admin');
Route::post('admin/commition', 'AdminController@calculateCommition');

Route::get('admin/pages', 'PagesController@index')->name('pages')->middleware('admin');
Route::post('admin/pages', 'PagesController@update');
Route::post('admin/showContent', 'PagesController@show');

Route::get('admin/blog', 'BlogController@index')->name('blog')->middleware('admin');
Route::post('admin/blog', 'BlogController@update');
Route::post('admin/showBlog', 'BlogController@show');
Route::post('admin/deleteBlog', 'BlogController@destroy');

//Banner Details Pages Route
Route::get('admin/banner', 'BannerController@index')->name('banner')->middleware('admin');
Route::post('admin/addBanner', 'BannerController@store')->name('add_banner');
Route::post('admin/deleteBanner', 'BannerController@delete');

//State Pages Route
Route::get('admin/state', 'StateController@index')->name('state')->middleware('admin');
Route::post('admin/showState', 'StateController@showState');
Route::post('admin/addState', 'StateController@store');
Route::post('admin/saveState', 'StateController@update');
Route::post('admin/deleteState', 'StateController@delete');

//Level Details Pages Route
Route::get('admin/level', 'LevelController@index')->name('level')->middleware('admin');
Route::post('admin/addLevel', 'LevelController@store');
Route::post('admin/editLevel', 'LevelController@edit');
Route::post('admin/saveLevel', 'LevelController@update');
Route::post('admin/deleteLevel', 'LevelController@delete');

//Seller Details Pages Route
Route::get('admin/seller', 'SellerController@index')->name('seller')->middleware('admin');
Route::post('admin/addSeller', 'SellerController@store');
Route::post('admin/editSeller', 'SellerController@edit');
Route::post('admin/saveSeller', 'SellerController@update');
Route::post('admin/deleteSeller', 'SellerController@delete');

//Category Pages Route
Route::get('admin/category', 'CategoryController@index')->name('category')->middleware('admin');
Route::get('admin/category/{cat_id?}', 'CategoryController@show');
Route::post('admin/addCategory', 'CategoryController@store')->name('add-category');
Route::post('admin/saveCategory', 'CategoryController@update');
Route::post('admin/deleteCategory', 'CategoryController@delete');

Route::post('admin/editSubCategoryImage', 'CategoryController@editSub');
Route::post('admin/addSubCategory', 'CategoryController@storeSub');
Route::post('admin/saveSubCategory', 'CategoryController@updateSub');
Route::post('admin/deleteSubCategory', 'CategoryController@deleteSub');

//Brand Pages Route
/*Route::get('admin/brand', 'BrandController@index')->name('brand');
Route::post('admin/addBrand', 'BrandController@store');
Route::post('admin/saveBrand', 'BrandController@update');
Route::post('admin/deleteBrand', 'BrandController@delete');*/

//Attribute Pages Route
Route::get('admin/attribute', 'AttributeController@index')->name('attribute')->middleware('admin');
Route::get('admin/getatt/{catId}/{subId}','AttributeController@getAttribute')->middleware('admin');
Route::get('admin/attribute/{attribute_id?}', 'AttributeController@show');
Route::post('admin/addAttribute', 'AttributeController@store');
Route::post('admin/saveAttribute', 'AttributeController@update');
Route::post('admin/deleteAttribute', 'AttributeController@delete');

Route::post('admin/addTermAttribute', 'AttributeController@storeTerm');
Route::post('admin/saveTermAttribute', 'AttributeController@updateTerm');
Route::post('admin/deleteTermAttribute', 'AttributeController@deleteTerm');

//Seller Product Pages Route
//Route::get('seller/product', 'ProductController@index')->name('sellerproduct');

Route::get('pro/{size_wet?}', 'ProductController@showProDesc');




Route::middleware(['auth'])->group(function () {
  
  Route::middleware(['admin'])->group(function () {
    //Items Pages Route
    Route::resource('admin/department', 'DepartmentController');
    Route::resource('admin/items', 'ItemController');

    // Route::get('admin/items', 'ItemController@index')->name('item')->middleware('admin');
    // Route::post('admin/addItem', 'ItemController@store');
    // Route::post('admin/editItems', 'ItemController@edit');
    // Route::post('admin/saveItems', 'ItemController@update');
    // Route::post('admin/deleteItems', 'ItemController@destroy')->name('item-del');
  });
    Route::resource('admin/product', 'ProductController');
    Route::post('admin/showProduct', 'ProductController@showProduct');
    Route::post('admin/addMoreAttribute', 'ProductController@addMoreAttribute');
    Route::post('admin/mainProductImg', 'ProductController@imgMain');
    Route::post('admin/deleteProductImg', 'ProductController@imgDelete');
    Route::post('admin/searchProduct', 'ProductController@search');

    //Admin Package Pages Route
    Route::resource('admin/package', 'PackageController');
    Route::post('admin/showPackage', 'PackageController@showPackage');
    Route::post('admin/mainPackageImg', 'PackageController@imgMain');
    Route::post('admin/deletePackageImg', 'PackageController@imgDelete');
    Route::post('admin/showPrices', 'PackageController@showPrices');
    Route::post('admin/searchPackage', 'PackageController@search');
    Route::post('admin/addMorProRow', 'PackageController@addMorProRow');
});






//Bookings Pages Route for admin
Route::get('admin/booking', 'BookingController@index')->name('booking')->middleware('admin');
Route::post('admin/addBooking', 'BookingController@store');
Route::post('admin/editBooking', 'BookingController@edit');
Route::post('admin/statusBooking', 'BookingController@status');
Route::post('admin/mainBookingImg', 'BookingController@imgMain');
Route::post('admin/deleteBookingImg', 'BookingController@imgDelete');
Route::post('admin/saveBooking', 'BookingController@update');
Route::post('admin/deleteBooking', 'BookingController@destroy');

//Postcode Pages Route for admin
Route::get('admin/postcode', 'PostcodeController@index')->name('postcode')->middleware('admin');
Route::post('admin/addPincode', 'PostcodeController@store');
Route::post('admin/editPincode', 'PostcodeController@edit');
Route::post('admin/deletePincode', 'PostcodeController@destroy');
Route::post('admin/savePincode', 'PostcodeController@update');

Route::get('admin/order', 'AdminController@allOrder')->name('admin-order')->middleware('admin');
Route::post('admin/showInvoice', 'AdminController@showInvoice');
Route::post('admin/shippingStatus', 'AdminController@shippingStatus');
Route::post('admin/paymentStatus', 'AdminController@paymentStatus');
Route::post('admin/deleteOrder', 'AdminController@destroy');

//Bookings Pages Route for buyer
Route::get('buyer/category/{cat_id?}', 'CategoryController@show');
Route::get('buyer/booking', 'BookingController@index')->name('buyer-booking')->middleware('buyer');
Route::post('buyer/addBooking', 'BookingController@store');
Route::post('buyer/editBooking', 'BookingController@edit');
Route::post('buyer/mainBookingImg', 'BookingController@imgMain');
Route::post('buyer/deleteBookingImg', 'BookingController@imgDelete');
Route::post('buyer/saveBooking', 'BookingController@update');
Route::post('buyer/deleteBooking', 'BookingController@destroy');
Route::post('buyer/deleteOrder', 'BuyerController@destroy');


//Show Product / Package / Booking as par category
Route::get('items/{url?}', 'ViewController@showItemsByCategory')->where('url', '[a-z0-9-]+')->name('itemsShowByCategory');
Route::get('items/{url?}/{sub?}', 'ViewController@showItemsBySubCategory')->where('url', '[a-z0-9-]+')->where('sub', '[a-z0-9-]+')->name('itemsShowBySubCategory');
Route::get('items/{url?}/{sub?}/{attr?}/{term?}', 'ViewController@itemsShowByTerms')->where('url', '[a-z0-9-]+')->where('sub', '[a-z0-9-]+')->where('attr', '[a-z0-9-]+')->where('term', '[a-z0-9-]+')->name('itemsShowByTerms');

//Location change
Route::post('changeLocation', 'ViewController@changeLocation');
Route::post('items/changeLocation', 'ViewController@changeLocation');
Route::post('product/changeLocation', 'ViewController@changeLocation');
Route::post('package/changeLocation', 'ViewController@changeLocation');
Route::post('booking/changeLocation', 'ViewController@changeLocation');

//Front end Single Product/Package/Booking Pages
Route::get('product/{code?}', 'ViewController@singleProduct')->name('code');
Route::get('package/{code?}', 'ViewController@singlePackage')->name('packag');
Route::get('booking/{code?}', 'ViewController@singleBooking')->name('bookin');

//Front end Booking Pages
Route::post('booking/video_thumbnail', 'ViewController@showBookingVideo');
Route::post('booking/book_now', 'ViewController@takeBooking');

//Front end Packages Booking
Route::post('package/book_now', 'ViewController@packageBooking');
//Review
//Route::post('product/review', 'ViewController@reviewProduct');
//Route::post('package/review', 'ViewController@reviewPackage');
//Route::post('booking/review', 'ViewController@reviewBooking');

Route::post('package/modify_package', 'ViewController@packageModify');

Route::post('/custom_login', 'ViewController@customLogin')->name('custom-login');

//Front end Cart Pages



Route::post('package/add-package-cart', 'CartController@storePackage');

//Route::post('add-booking-cart', 'CartController@storeBooking');
Route::post('update-cart', 'CartController@update');
Route::post('remove-cart', 'CartController@remove');
Route::post('clear-cart', 'CartController@destroy');

//Front end Checkout Pages

Route::post('/smsSend', 'BuyerController@phoneVerification')->name('sms');
Route::post('/session_delete', 'BuyerController@stopValidation');
Route::post('/varify_code', 'BuyerController@varifyCode');
Route::post('/storeDeliveryTime', 'BuyerController@deliveryTime');
Route::post('/addCustomer', 'BuyerController@addCustomer');

Route::get('buyer/order', 'BuyerController@myOrder')->name('buyer-order')->middleware('buyer');
Route::post('buyer/showInvoice', 'BuyerController@showInvoice');



//Paypal
//Route::get('payment', 'PaymentController@payment')->name('checkout.payment');
Route::get('paypalreturn', 'PaymentController@store')->name('payment.store');
Route::get('pay-with-paypal', 'PaymentController@payWithPaypal')->name('payment.paypal');


Route::post('/cashOnDelivery', 'PaymentController@cashOnDelivery');
//Card Payment With instamojo.com
Route::post('buyer/payWithCard', 'PaymentController@payWithCard')->name('cardPayment');
Route::get('returnurl', 'PaymentController@returnurl')->name('returnurl');

//All search
Route::post('{any}/search', 'SearchController@short');
Route::post('{any}/{url?}/search', 'SearchController@short');
Route::post('items/{url?}/{sub?}/{term?}', 'SearchController@short');

Route::post('/searchSuggation', 'SearchController@searchSuggation');
Route::post('{any}/searchSuggation', 'SearchController@searchSuggation');
Route::post('{any}/{url?}/searchSuggation', 'SearchController@searchSuggation');
Route::post('/pinSuggation', 'SearchController@pinSuggation');
Route::get('search/{term?}', 'SearchController@mainSearch')->where('term', '[a-z0-9-]+')->name('search');

//Show Contact Page
Route::get('contact', 'ViewController@contact')->name('contact');
Route::post('contact', 'ViewController@sendEmail');
//Show About Us Page
Route::get('about', 'ViewController@showAbout')->name('about');
//Show Privacy Page
Route::get('privacy', 'ViewController@showPrivacy')->name('privacy');
//Show Privacy Page
Route::get('return', 'ViewController@showReturn')->name('return');
//Show Privacy Page
Route::get('terms', 'ViewController@showTerms')->name('terms');
//Show Blog Page
Route::get('blogs', 'ViewController@showBlogs')->name('blogs');
Route::get('blog/{url?}', 'ViewController@singleBlog')->where('url', '[a-z0-9-]+')->name('blg');



// Route made on 24/06/2020
// Route::get('admin/getsubcategory/{cat_id}','CategoryController@getSub');
