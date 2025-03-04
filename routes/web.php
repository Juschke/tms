<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    KundeController,
    AuftragController,
    RechnungController,
    PartnerController,
    MitarbeiterController,
    EinstellungenController,
	DashboardController
};


Route::post('/admin/auftraege/preview', [AuftragController::class, 'preview'])->name('admin.auftraege.preview');
Route::get('/', fn() => redirect()->route('admin.auftraege.index'))->middleware('auth');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->get('/dashboard', fn() => view('dashboard'))->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function() {
    Route::resources([
        'kunden'        => KundeController::class,
        'auftraege'     => AuftragController::class,
		'dashboard'     => DashboardController::class,
        'rechnungen'    => RechnungController::class,
        'mitarbeiter'   => MitarbeiterController::class,
        'partner'       => PartnerController::class,
        'einstellungen' => EinstellungenController::class,
    ]);

    Route::post('auftraege/{auftrag}/assign-partner', [AuftragController::class, 'assignPartner'])->name('auftraege.assignPartner');
    Route::get('/admin/einstellungen', [EinstellungenController::class, 'index'])->name('admin.einstellungen.index');
    Route::put('/admin/einstellungen', [EinstellungenController::class, 'update'])->name('admin.einstellungen.update');
    Route::post('auftraege/{auftrag}/assign-partner', [AuftragController::class, 'assignPartner'])->name('auftraege.assignPartner');

});

