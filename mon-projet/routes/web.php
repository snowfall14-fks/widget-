<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Http\Controllers\DocumentUploadController;
use App\Http\Controllers\ConversationWebhookController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/admin/documents/upload', [DocumentUploadController::class, 'store'])
    ->middleware('auth')
    ->name('documents.upload');

Route::delete('/admin/documents/{document}', [DocumentUploadController::class, 'destroy'])
    ->middleware('auth')
    ->name('documents.destroy');

Route::post('/webhook/conversation', [ConversationWebhookController::class, 'store'])
    ->name('webhook.conversation');