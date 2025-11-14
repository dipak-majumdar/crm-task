<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactsController;

Route::get('/', [ContactsController::class, 'index'])->name('list');
Route::get('/contact-list', [ContactsController::class, 'getList'])->name('contact-list');
Route::get('/trashed-contacts', [ContactsController::class, 'getTrashedList'])->name('trashed-contacts');


Route::get('/get-contact/{id}', [ContactsController::class, 'getContact'])->name('getcontact');
Route::put('/update-contact/{id}', [ContactsController::class, 'updateContact'])->name('updateContact');

Route::post('/store', [ContactsController::class, 'store'])->name('store');

Route::put('/merge/{id}', [ContactsController::class, 'mergeContacts'])->name('mergeContact');
Route::delete('/delete/{id}', [ContactsController::class, 'deleteContact'])->name('delete');
