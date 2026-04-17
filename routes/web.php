<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestBookingController;
use App\Http\Controllers\GuestMessageController;
use App\Http\Controllers\Staff\GuestMessageController as StaffGuestMessageController;
use App\Http\Controllers\EventDetailController;
use App\Http\Controllers\Staff\GuestBookingController as StaffGuestBookingController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EventReservationController as AdminEventReservationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\SpecialOfferController as AdminSpecialOfferController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TrashController as AdminTrashController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Staff\EventReservationController as StaffEventReservationController;
use App\Http\Controllers\Staff\ReportController as StaffReportController;
use App\Http\Controllers\Staff\MessageController as StaffMessageController;
use App\Http\Controllers\User\EventReservationController as UserEventReservationController;
use App\Http\Controllers\User\MessageController as UserMessageController;

// ==================
// PUBLIC ROUTES
// ==================
Route::get('/events/{slug}', [EventDetailController::class, 'show'])->name('event.detail');

Route::get('/book', [GuestBookingController::class, 'show'])->name('guest.book');
Route::get('/book/{event}', [GuestBookingController::class, 'show'])->name('guest.book.event');
Route::post('/book', [GuestBookingController::class, 'store'])->name('guest.book.store');
Route::post('/guest-message', [GuestMessageController::class, 'store'])->name('guest.message.store');
Route::post('/guest-message/check', [GuestMessageController::class, 'checkReply'])->name('guest.message.check');
Route::get('/book-success', [GuestBookingController::class, 'success'])->name('guest.book.success');

Route::get('/staff-login', [AuthController::class, 'showStaffLogin'])->name('staff.login');
Route::post('/staff-login', [AuthController::class, 'staffLogin'])->name('staff.login.post');

Route::get('/', function () {
    $events = \App\Models\Event::where('is_active', true)->with('packages')->get();
    $offers = \App\Models\SpecialOffer::where('is_active', true)->latest()->get();
    return view('welcome', compact('events', 'offers'));
})->name('home');

Route::get('/login', function () { return redirect('/'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', function () { return redirect('/'); })->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==================
// CHANGE PASSWORD + PROFILE (all roles)
// ==================
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
    Route::put('/change-password', [PasswordController::class, 'update'])->name('password.update');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ==================
// ADMIN ROUTES
// ==================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/event-reservations', [AdminEventReservationController::class, 'index'])->name('event-reservations.index');
    Route::get('/event-reservations/{id}', [AdminEventReservationController::class, 'show'])->name('event-reservations.show');
    Route::patch('/event-reservations/{id}', [AdminEventReservationController::class, 'update'])->name('event-reservations.update');
    Route::delete('/event-reservations/{id}', [AdminEventReservationController::class, 'destroy'])->name('event-reservations.destroy');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');

    // Trash
    Route::get('/trash', [AdminTrashController::class, 'index'])->name('trash.index');
    Route::patch('/trash/{id}/restore', [AdminTrashController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/{id}', [AdminTrashController::class, 'destroy'])->name('trash.destroy');

    // Users
    Route::resource('users', AdminUserController::class);

    // Events
    Route::resource('events', AdminEventController::class);
    Route::patch('/events/{event}/toggle', [AdminEventController::class, 'toggle'])->name('events.toggle');

    // Packages
    Route::get('/events/{eventId}/packages', [AdminPackageController::class, 'packages'])->name('events.packages');
    Route::get('/events/{eventId}/packages/create', [AdminPackageController::class, 'create'])->name('events.packages.create');
    Route::post('/events/{eventId}/packages', [AdminPackageController::class, 'store'])->name('events.packages.store');
    Route::get('/events/{eventId}/packages/{packageId}/edit', [AdminPackageController::class, 'edit'])->name('events.packages.edit');
    Route::put('/events/{eventId}/packages/{packageId}', [AdminPackageController::class, 'update'])->name('events.packages.update');
    Route::delete('/events/{eventId}/packages/{packageId}', [AdminPackageController::class, 'destroy'])->name('events.packages.destroy');
    Route::patch('/events/{eventId}/packages/{packageId}/toggle', [AdminPackageController::class, 'toggle'])->name('events.packages.toggle');

    // Special Offers
    Route::resource('offers', AdminSpecialOfferController::class);
    Route::patch('/offers/{offer}/toggle', [AdminSpecialOfferController::class, 'toggle'])->name('offers.toggle');
});

// ==================
// STAFF ROUTES
// ==================
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');

    Route::get('/event-reservations', [StaffEventReservationController::class, 'index'])->name('event-reservations.index');
    Route::get('/event-reservations/{id}', [StaffEventReservationController::class, 'show'])->name('event-reservations.show');
    Route::patch('/event-reservations/{id}/confirm', [StaffEventReservationController::class, 'confirm'])->name('event-reservations.confirm');
    Route::patch('/event-reservations/{id}/complete', [StaffEventReservationController::class, 'complete'])->name('event-reservations.complete');
    Route::get('/reports', [StaffReportController::class, 'index'])->name('reports.index');
    Route::get('/event-reservations/{id}/receipt', [StaffEventReservationController::class, 'receipt'])->name('event-reservations.receipt');

    // Guest Messages
    Route::get('/guest-messages', [StaffGuestMessageController::class, 'index'])->name('guest-messages.index');
    Route::post('/guest-messages/{guestMessage}/reply', [StaffGuestMessageController::class, 'reply'])->name('guest-messages.reply');

    // Guest Bookings
    Route::get('/bookings', [StaffGuestBookingController::class, 'index'])->name('guest-bookings.index');
    Route::get('/bookings/{guestBooking}', [StaffGuestBookingController::class, 'show'])->name('guest-bookings.show');
    Route::patch('/bookings/{guestBooking}/confirm', [StaffGuestBookingController::class, 'confirm'])->name('guest-bookings.confirm');
    Route::patch('/bookings/{guestBooking}/cancel', [StaffGuestBookingController::class, 'cancel'])->name('guest-bookings.cancel');
    Route::patch('/bookings/{guestBooking}/complete', [StaffGuestBookingController::class, 'complete'])->name('guest-bookings.complete');
    Route::patch('/bookings/{guestBooking}/payment', [StaffGuestBookingController::class, 'updatePayment'])->name('guest-bookings.payment');

    // Messages
    Route::get('/messages', [StaffMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/unread', [StaffMessageController::class, 'unread'])->name('messages.unread');
    Route::get('/messages/{userId}', [StaffMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{userId}/reply', [StaffMessageController::class, 'reply'])->name('messages.reply');
});

// ==================
// USER ROUTES
// ==================
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

    // Browse Events
    Route::get('/events', [UserEventReservationController::class, 'index'])->name('events.index');
    Route::get('/events/{eventId}/venues', [UserEventReservationController::class, 'selectVenue'])->name('events.venues');
    Route::get('/events/{eventId}/venues/{venueId}/packages', [UserEventReservationController::class, 'selectPackage'])->name('events.packages');
    Route::get('/events/book/{packageId}', [UserEventReservationController::class, 'create'])->name('events.create');
    Route::post('/events/book', [UserEventReservationController::class, 'store'])->name('events.store');

    // My Reservations
    Route::get('/reservations', [UserEventReservationController::class, 'myReservations'])->name('reservations.index');
    Route::get('/reservations/{id}', [UserEventReservationController::class, 'show'])->name('reservations.show');
    Route::get('/reservations/{id}/edit', [UserEventReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{id}', [UserEventReservationController::class, 'update'])->name('reservations.update');
    Route::patch('/reservations/{id}/cancel', [UserEventReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::delete('/reservations/{id}', [UserEventReservationController::class, 'delete'])->name('reservations.delete');

    // Messages (AJAX)
    Route::get('/messages', [UserMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [UserMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/unread', [UserMessageController::class, 'unread'])->name('messages.unread');
});