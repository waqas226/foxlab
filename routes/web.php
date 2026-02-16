<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\TechlinkController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\OutlookAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\Auth\CustomTwoFactorController;
use App\Http\Controllers\CustomerController;

Route::post('/two-factor-challenge', [CustomTwoFactorController::class, 'store'])->name('two-factor.login');

Route::middleware('auth')->group(function () {
  Route::get('/clear-cache', function (Request $request) {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');

    $redirectUrl = url()->previous();
    if (!$redirectUrl || $redirectUrl === url('/clear-cache')) {
      $redirectUrl = route('manage-work-orders');
    }

    return redirect($redirectUrl)->with('hard_refresh', true);
  })->name('clear-cache');

  Route::get('/manage-customers', [CustomerController::class, 'index'])->name('manage-customers');
  Route::get('/manage-customers/delete/{id}', [CustomerController::class, 'destroy'])->name('customers.delete');
  Route::get('/manage-customers/update-status/{id}', [CustomerController::class, 'status'])->name('customers.delete');
  Route::get('/manage-customers/show', [CustomerController::class, 'show'])->name('customers.show');
  Route::get('/manage-customers/add', [CustomerController::class, 'create'])->name('customers.create');
  Route::get('/manage-customers/{id}/edit', [CustomerController::class, 'create'])->name('customers.edit');
  Route::post('/manage-customers/import', [CustomerController::class, 'import'])->name('customers.import');
  Route::post('/manage-customers/store', [CustomerController::class, 'store'])->name('customers.store');

  Route::get('manage-devices', [DeviceController::class, 'index'])->name('manage-devices');
  Route::get('manage-devices/delete/{id}', [DeviceController::class, 'destroy'])->name('manage-devices.delete');
  Route::get('manage-devices/show', [DeviceController::class, 'show'])->name('manage-devices.show');
  Route::post('manage-devices/import', [DeviceController::class, 'import'])->name('device.import');
  Route::post('manage-devices/store', [DeviceController::class, 'store'])->name('device.store');
  Route::post('manage-devices/upload-image', [DeviceController::class, 'uploadImage'])->name('device.upload-image');
  Route::post('manage-devices/delete-picture', [DeviceController::class, 'deletePicture'])->name('device.delete-picture');
  Route::get('/manage-devices/add', [DeviceController::class, 'create'])->name('device.create');
  Route::get('/manage-devices/{id}/edit', [DeviceController::class, 'create'])->name('device.edit');

  // Route::middleware(['2fa', 'force.2fa'])->group(function () {
  Route::get('/manage-users', [UserController::class, 'index'])->name('manage-user');
  Route::get('/manage-users/show', [UserController::class, 'showData'])->name('app-user-show');
  Route::get('/manage-users/add', [UserController::class, 'create'])->name('user-add');
  Route::get('/manage-users/{id}/edit', [UserController::class, 'edit'])->name('user-edit');
  Route::post('/manage-users/store', [UserController::class, 'store'])->name('user-store');
  Route::get('/manage-users/update-status/{id}', [UserController::class, 'update_status'])->name('user-status-update');
  Route::delete('/manage-users/{id}', [UserController::class, 'destroy'])->name('user-delete');

  Route::get('/manage-site-constants', [SiteController::class, 'index'])->name('manage-site-constants');
  Route::post('/manage-site-constants', [SiteController::class, 'store'])->name('site-constants-store');

  Route::get('manage-roles', [RoleController::class, 'index'])->name('manage-roles');
  Route::get('manage-roles/show', [RoleController::class, 'showData'])->name('manage-roles-show');
  Route::post('manage-roles/update-status', [RoleController::class, 'updatestatus'])->name('manage-roles.updatestatus');
  Route::post('manage-roles/store', [RoleController::class, 'store'])->name('manage-roles.store');
  Route::get('manage-roles/edit/{id}', [RoleController::class, 'edit'])->name('manage-roles.edit');
  Route::put('manage-roles/update/{id}', [RoleController::class, 'update'])->name('manage-roles.update');
  Route::get('manage-roles/delete/{id}', [RoleController::class, 'destroy'])->name('manage-roles.destroy');

  Route::get('manage-checklists', [ChecklistController::class, 'index'])->name('manage-checklists');
  Route::get('manage-checklists/show', [ChecklistController::class, 'show'])->name('manage-checklists.show');
  Route::get('manage-checklists/create', [ChecklistController::class, 'create'])->name('manage-checklists.create');
  Route::post('manage-checklists/update-status', [ChecklistController::class, 'updateStatus'])->name(
    'manage-checklists.update-status'
  );
  Route::post('manage-checklists/store', [ChecklistController::class, 'store'])->name('manage-checklists.store');
  Route::get('manage-checklists/{id}/edit', [ChecklistController::class, 'edit'])->name('manage-checklists.edit');
  Route::put('manage-checklists/update/{id}', [ChecklistController::class, 'update'])->name('manage-checklists.update');
  Route::get('manage-checklists/delete/{id}', [ChecklistController::class, 'destroy'])->name(
    'manage-checklists.destroy'
  );
  Route::get('manage-checklists/get-models/{make}/{id?}', [ChecklistController::class, 'getModels'])->name(
    'manage-checklists.get-models'
  );
  Route::get('manage-checklists/get-makes', [ChecklistController::class, 'getMakes'])->name(
    'manage-checklists.get-makes'
  );
  Route::get('manage-checklists/get-types', [ChecklistController::class, 'getTypes'])->name(
    'manage-checklists.get-types'
  );
  Route::post('manage-checklists/import', [ChecklistController::class, 'import'])->name('checklists.import');
  Route::get('manage-checklists/print/{id}', [ChecklistController::class, 'print'])->name('checklists.print');

  Route::get('/manage-work-orders', [WorkController::class, 'index'])->name('manage-work-orders');

  Route::get('/manage-work-orders/show', [WorkController::class, 'show'])->name('work-orders.show');
  Route::get('/manage-work-orders/create/{id?}', [WorkController::class, 'create'])->name('work-orders.create');
  Route::post('/manage-work-orders/store', [WorkController::class, 'store'])->name('work-orders.store');
  Route::get('/manage-work-orders/{id}/edit', [WorkController::class, 'edit'])->name('work-orders.edit');
  Route::get('/manage-work-orders/{id}', [WorkController::class, 'view'])->name('work-orders.view');
  Route::get('/manage-work-orders/delete/{id}', [WorkController::class, 'delete'])->name('work-orders.delete');
  Route::get('/manage-work-orders/print/{id}', [WorkController::class, 'print'])->name('work-orders.print');
  Route::get('/work-order-device/{id}/{device_id}', [WorkController::class, 'viewchecklist'])->name(
    'work-order-device'
  );
  Route::get('/work-order-device-view/{id}/{device_id}', [WorkController::class, 'viewChecklistReadonly'])->name(
    'work-order-device-view'
  );
  Route::get('/work-order-device-history/{id}/{device_id}', [WorkController::class, 'deviceHistory'])->name(
    'work-order-device-history'
  );
  Route::get('/device-history/{device_id}', [WorkController::class, 'deviceHistoryByDevice'])->name(
    'device-history'
  );
  Route::post('/work-order-checklist', [WorkController::class, 'updatechecklist'])->name('work-order-checklist');
  Route::post('/manage-work-orders/update', [WorkController::class, 'update'])->name('work-orders.update');
  Route::post('/manage-work-orders/sign', [WorkController::class, 'sign'])->name('work-orders.sign');
  Route::get('/manage-work-orders/update-status/{id}', [WorkController::class, 'updatestatus'])->name(
    'work-orders.updatestatus'
  );
  Route::get('/manage-work-orders/send-mail/{id}/{mailType}', [WorkController::class, 'sendMail'])->name(
    'work-orders.send-mail'
  );
  Route::get('/manage-work-orders/test-mail', [WorkController::class, 'sendMail2'])->name(
    'work-orders.send'
  );
  Route::get('/', function () {
    return redirect('manage-work-orders');
  });

  Route::get('/outlook/connect', [OutlookAuthController::class, 'redirectToProvider'])->name('outlook.connect');
  Route::get('/callback', [OutlookAuthController::class, 'handleProviderCallback'])->name('outlook.callback');

  Route::get('change-password', function () {
    return view('change-password');
  })->name('change-password');
  Route::post('change-password', [UserController::class, 'changepassword'])->name('change-password.post');
  // });
});
Route::get('/dashboard', function () {
  return redirect('manage-work-orders');
})
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

// Route::middleware('auth')->group(function () {
//   Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//   Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//   Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::get('/logout', function () {
  Auth::logout();
  return redirect('/login');
});

//php artisan config:clear
// php artisan config:cache

// php artisan route:cache
// php artisan view:cache
// php artisan cache:clear

require __DIR__ . '/auth.php';
