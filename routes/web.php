<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\CampusLifeController;
use App\Http\Controllers\CgpaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PortalStaffController;
use App\Http\Controllers\QueryBoardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\TransportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('landing'))->name('home');

Route::get('/guest', fn () => view('guest'))->name('guest');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('login.admin');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('login.admin.submit');
    Route::get('/cafe/admin/login', [AuthController::class, 'showCafeAdminLogin'])->name('login.cafe');
    Route::post('/cafe/admin/login', [AuthController::class, 'cafeAdminLogin'])->name('login.cafe.submit');
    Route::get('/library/admin/login', [AuthController::class, 'showLibraryAdminLogin'])->name('login.library');
    Route::post('/library/admin/login', [AuthController::class, 'libraryAdminLogin'])->name('login.library.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
Route::get('/transport', [TransportController::class, 'index'])->name('transport.index');
Route::get('/cafe', [CafeController::class, 'index'])->name('cafe.index');
Route::get('/cafe/tables/availability', [CafeController::class, 'tableAvailability'])->name('cafe.tables.availability');
Route::post('/cafe/orders', [CafeController::class, 'placeOrder'])->name('cafe.orders.store');

Route::middleware(['auth', 'role:student,teacher,admin,cafe_admin,library_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dress-code', [CampusLifeController::class, 'dressCode'])
        ->middleware('role:student,teacher,admin')
        ->name('campus.dress_code');
    Route::get('/calendar', [CampusLifeController::class, 'academicCalendar'])
        ->middleware('role:student,teacher,admin')
        ->name('campus.calendar');

    Route::get('/resources', [ResourceController::class, 'index'])
        ->middleware('role:student,teacher,admin')
        ->name('resources.index');
    Route::post('/resources/store', [ResourceController::class, 'store'])
        ->middleware('role:teacher,admin')
        ->name('resources.store');
    Route::put('/resources/{resource}', [ResourceController::class, 'update'])
        ->middleware('role:teacher,admin')
        ->name('resources.update');
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])
        ->middleware('role:teacher,admin')
        ->name('resources.destroy');
    Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])
        ->middleware('role:student,teacher,admin')
        ->name('resources.download');

    Route::post('/notices/store', [NoticeController::class, 'store'])
        ->middleware('role:student,teacher,admin')
        ->name('notices.store');

    Route::post('/library/borrow/{book}', [LibraryController::class, 'borrow'])
        ->middleware('role:student,teacher')
        ->name('library.borrow');
    Route::post('/library/return/{borrow}', [LibraryController::class, 'returnBook'])
        ->middleware('role:library_admin,admin')
        ->name('library.return');
    Route::post('/library/books/store', [LibraryController::class, 'storeBook'])
        ->middleware('role:library_admin,admin')
        ->name('library.books.store');
    Route::put('/library/books/{book}', [LibraryController::class, 'updateBook'])
        ->middleware('role:library_admin,admin')
        ->name('library.books.update');
    Route::delete('/library/books/{book}', [LibraryController::class, 'destroyBook'])
        ->middleware('role:library_admin,admin')
        ->name('library.books.destroy');
    Route::put('/library/settings', [LibraryController::class, 'updateSettings'])
        ->middleware('role:library_admin,admin')
        ->name('library.settings.update');

    Route::post('/transport/store', [TransportController::class, 'store'])
        ->middleware('role:student,teacher,admin')
        ->name('transport.store');

    Route::post('/cafe/store', [CafeController::class, 'store'])
        ->middleware('role:cafe_admin')
        ->name('cafe.store');
    Route::delete('/cafe/items/{item}', [CafeController::class, 'destroyItem'])
        ->middleware('role:cafe_admin')
        ->name('cafe.items.destroy');
    Route::post('/cafe/staff', [PortalStaffController::class, 'storeCafeStaff'])
        ->middleware('role:cafe_admin')
        ->name('cafe.staff.store');
    Route::delete('/cafe/staff/{staff}', [PortalStaffController::class, 'destroyCafeStaff'])
        ->middleware('role:cafe_admin')
        ->name('cafe.staff.destroy');
    Route::post('/cafe/{item}/toggle', [CafeController::class, 'toggleAvailability'])
        ->middleware('role:cafe_admin')
        ->name('cafe.toggle');
    Route::post('/cafe/orders/{order}/status', [CafeController::class, 'updateOrderStatus'])
        ->middleware('role:cafe_admin')
        ->name('cafe.orders.status');

    Route::post('/library/staff', [PortalStaffController::class, 'storeLibraryStaff'])
        ->middleware('role:library_admin,admin')
        ->name('library.staff.store');
    Route::delete('/library/staff/{staff}', [PortalStaffController::class, 'destroyLibraryStaff'])
        ->middleware('role:library_admin,admin')
        ->name('library.staff.destroy');

    Route::middleware('role:student,teacher,admin,cafe_admin,library_admin')->group(function () {
        Route::get('/queries', [QueryBoardController::class, 'index'])->name('queries.index');
        Route::get('/queries/{post}', [QueryBoardController::class, 'show'])->name('queries.show');
        Route::post('/queries/store', [QueryBoardController::class, 'store'])->name('queries.store');
        Route::post('/queries/{post}/reply', [QueryBoardController::class, 'reply'])->name('queries.reply');
        Route::post('/queries/{post}/resolve', [QueryBoardController::class, 'toggleResolve'])->name('queries.resolve');
        Route::delete('/queries/{post}', [QueryBoardController::class, 'destroy'])->name('queries.destroy');

    });

    Route::middleware('role:student,teacher,admin')->group(function () {
        Route::get('/cgpa', [CgpaController::class, 'index'])->name('cgpa.index');
        Route::post('/cgpa/save', [CgpaController::class, 'saveRecord'])->name('cgpa.save');
    });

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/admin/users/{user}/approval', [AdminController::class, 'toggleUserApproval'])->name('admin.users.approval');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::post('/admin/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });
});
