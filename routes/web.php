<?php

// use Illuminate\Foundation\Application;
// use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Illuminate\Support\Carbon;
use App\Services\QuotationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\ProductCategoryController;

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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('clients')->name('clients')->group(function () {
        Route::get('', [ClientController::class, 'index']);
        Route::post('', [ClientController::class, 'store'])->name('-store');
        Route::patch('', [ClientController::class, 'update'])->name('-update');
        Route::delete('{client}', [ClientController::class, 'destroy'])->name('-delete');
        Route::get('download/{id}', [ClientController::class, 'downloadClient'])->name('-download');
        Route::get('download-all', [ClientController::class, 'downloadClients'])->name('-clients-download');
    });

    Route::name('accounts')->prefix('accounts')->group(function () {
        Route::get('/', [AccountController::class, 'index']);
        // Route::get('create/{client_id?}', [AccountController::class, 'create'])->name('-create');
        // Route::get('edit/{id}', [AccountController::class, 'edit'])->name('-edit');
        // Route::get('view/{id}', [AccountController::class, 'show'])->name('-view');
        Route::get('download/{id?}', [AccountController::class, 'downloadAccount'])->name('-download');
        Route::post('create', [AccountController::class, 'store'])->name('-store');
        Route::patch('update/{account}', [AccountController::class, 'update'])->name('-update');
        Route::prefix('transactions')
            ->name('-transactions')
            ->group(
                function () {
                    Route::post('transaction', [AccountController::class, 'postTransaction'])->name('-transaction-post');
                    Route::get('{account_id?}', [TransactionsController::class, 'getTransactions']);
                    Route::get('download/{transaction_id}', [TransactionsController::class, 'downloadTransaction'])->name('-download');
                    Route::post('update', [TransactionsController::class, 'update'])->name('-update');
                }
            );
    });

    Route::name('invoices')->prefix('invoices')->group(function () {
        Route::get('', [InvoiceController::class, 'index']);
        Route::get('create/{client_id?}', [InvoiceController::class, 'create'])->name('-create');
        Route::get('edit/{id}', [InvoiceController::class, 'edit'])->name('-edit');
        Route::get('view/{id}', [InvoiceController::class, 'show'])->name('-view');
        Route::post('create', [InvoiceController::class, 'store'])->name('-store');
        Route::patch('{invoice}', [InvoiceController::class, 'update'])->name('-update');
        Route::post('merge', [InvoiceController::class, 'postMerge'])->name('-merge-post');
        Route::get('{id}/download', [InvoiceController::class, 'downloadInvoice'])->name('-download');
        Route::get('{id}/delivery', [InvoiceController::class, 'downloadDelivery'])->name('-delivery');
        Route::post('{id}/pay', [InvoiceController::class, 'pay'])->name('-pay');
        Route::get('{receipt_id}/receipt', [InvoiceController::class, 'receipt'])->name('-receipt');
    });

    Route::name('product_categories')->prefix('product_categories')->group(function () {
        Route::post('create', [ProductCategoryController::class, 'store'])->name('-create');
        Route::post('update', [ProductCategoryController::class, 'update'])->name('-update');
        Route::post('delete', [ProductCategoryController::class, 'destroy'])->name('-delete');
    });

    Route::name('products')->prefix('products')->group(function () {
        Route::get('list/{category_id?}', [ProductController::class, 'index'])->name('-list');
        Route::get('edit/{id}', [ProductController::class, 'edit'])->name('-edit');
        Route::get('view/{id}', [ProductController::class, 'show'])->name('-view');
        Route::get('download/{id}', [ProductController::class, 'downloadProduct'])->name('-download');
        Route::post('create', [ProductController::class, 'store'])->name('-create');
        Route::post('update', [ProductController::class, 'update'])->name('-update');
        Route::post('delete', [ProductController::class, 'destroy'])->name('-delete');
    });

    Route::name('services')->prefix('services')->group(function () {
        Route::get('list/{client_id?}', [ServiceController::class, 'index']);
        Route::get('view/{id}', [ServiceController::class, 'show'])->name('-view');
        Route::post('update/{id}', [ServiceController::class, 'update'])->name('-update');
        Route::post('create', [ServiceController::class, 'store'])->name('-create');
        Route::post('delete', [ServiceController::class, 'destroy'])->name('-delete');
    });

    Route::group(['prefix' => 'setup', 'as' => 'setup'], function () {
        Route::get('', [SetupController::class, 'getSetup']);
        Route::post('migrate', [SetupController::class, 'postMigrate'])->name('-migrate');
    });

    Route::get('/products', function () {
        return view('products.index');
    })->name('products');

    Route::get('/domains', function () {
        return view('domains.index');
    })->name('domains');

    Route::prefix('quotations')->name('quotations')->group(function () {
        Route::get('', [QuotationController::class, 'index']);
        Route::post('', [QuotationController::class, 'store'])->name('-store');
        Route::patch('{quotation}', [QuotationController::class, 'update'])->name('-update');
        Route::delete('{quotation}', [QuotationController::class, 'destroy'])->name('-destroy');
        Route::get("download/{id}", function (int $id, QuotationService $service) {
            return $service->download($id)->download('Quotation#' . str_pad($id, 4, '0', 0) . '.pdf');
        })->name('-download');
    });
});

Route::get('test', function () {
    $pdf = PDF::loadView('test');

    return $pdf->download('test.pdf');
});

// Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');
});

Route::get('sms', [App\Http\Controllers\HomeController::class, 'sendSms']);

Route::middleware('auth')->name('messages')->prefix('messages')->group(function () {
    Route::get('', [MessagesController::class, 'index']);
    Route::get('create', [MessagesController::class, 'create'])->name('-create');
    Route::post('store', [MessagesController::class, 'store'])->name('-store');
});

Route::get('icons', function () {
    $icons = collect(['icon-alert-circle-exc', 'icon-align-center', 'icon-align-left-2', 'icon-app', 'icon-atom', 'icon-attach-87', 'icon-badge', 'icon-bag-16', 'icon-bank', 'icon-basket-simple', 'icon-bell-55', 'icon-bold', 'icon-book-bookmark', 'icon-bulb-63', 'icon-bullet-list-67', 'icon-bus-front-12', 'icon-button-pause', 'icon-button-power', 'icon-calendar-60', 'icon-camera-18', 'icon-caps-small', 'icon-cart', 'icon-chart-bar-32', 'icon-chart-pie-36', 'icon-chat-33', 'icon-check-2', 'icon-cloud-download-93', 'icon-cloud-upload-94', 'icon-coins', 'icon-compass-05', 'icon-controller', 'icon-credit-card', 'icon-delivery-fast', 'icon-double-left', 'icon-double-right', 'icon-email-85', 'icon-gift-2', 'icon-globe-2', 'icon-headphones', 'icon-heart-2', 'icon-html5', 'icon-image-02', 'icon-istanbul', 'icon-key-25', 'icon-laptop', 'icon-light-3', 'icon-link-72', 'icon-lock-circle', 'icon-map-big', 'icon-minimal-down', 'icon-minimal-left', 'icon-minimal-right', 'icon-minimal-up', 'icon-mobile', 'icon-molecule-40', 'icon-money-coins', 'icon-notes', 'icon-palette', 'icon-paper', 'icon-pencil', 'icon-pin', 'icon-planet', 'icon-puzzle-10', 'icon-satisfied', 'icon-scissors', 'icon-send', 'icon-settings-gear-63', 'icon-settings', 'icon-simple-add', 'icon-simple-delete', 'icon-simple-remove', 'icon-single-02', 'icon-single-copy-04', 'icon-sound-wave', 'icon-spaceship', 'icon-square-pin', 'icon-support-17', 'icon-tablet-2', 'icon-tag', 'icon-tap-02', 'icon-tie-bow', 'icon-time-alarm', 'icon-trash-simple', 'icon-triangle-right-17', 'icon-trophy', 'icon-tv-2', 'icon-upload', 'icon-user-run', 'icon-vector', 'icon-video-66', 'icon-volume-98', 'icon-wallet-43', 'icon-watch-time', 'icon-wifi', 'icon-world', 'icon-zoom-split', 'icon-refresh-01', 'icon-refresh-02', 'icon-shape-star', 'icon-components']);
    return view('icons', compact('icons'));
});
