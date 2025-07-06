<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Finance\CashController;
use App\Http\Controllers\Finance\CashFlowController;
use App\Http\Controllers\Finance\ExpenseController;
use App\Http\Controllers\Rental\RentController;
use App\Http\Controllers\Rental\RenterController;
use App\Http\Controllers\Scaffolding\ItemController;
use App\Http\Controllers\Scaffolding\PurchaseController;
use App\Http\Controllers\Scaffolding\RepairController;
use App\Http\Controllers\Scaffolding\SetController;
use App\Http\Controllers\Scaffolding\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Utility\FileViewerController;
use App\Http\Controllers\Utility\PdfRenderController;
use App\Http\Controllers\Utility\WhatsappTemplateController;
use Illuminate\Support\Facades\Route;
use Illuminate\View\FileViewFinder;

Route::get('/', [UserController::class, 'login']);

Route::get('/login', [UserController::class, 'login']);
Route::post('/auth', [UserController::class, 'authentication']);
Route::get('/logout', [UserController::class, 'logout']);

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
    Route::middleware('can:item')->group(function(){
        Route::get('/scaffolding/item', [ItemController::class, 'item']);
        Route::get('/scaffolding/item/input', [ItemController::class, 'createFormItem']);
        Route::post('/scaffolding/item/simpan', [ItemController::class, 'createItem']);
        Route::get('/scaffolding/item/edit/{id}', [ItemController::class, 'updateFormItem']);
        Route::post('/scaffolding/item/update/{id}', [ItemController::class, 'updateItem']);
        Route::get('/scaffolding/item/hapus/{id}', [ItemController::class, 'deleteItem']);

        Route::get('/scaffolding/set', [SetController::class, 'set']);
        Route::get('/scaffolding/set/input', [SetController::class, 'createFormSet']);
        Route::post('/scaffolding/set/simpan', [SetController::class, 'createSet']);
        Route::get('/scaffolding/set/edit/{id}', [SetController::class, 'updateFormSet']);
        Route::post('/scaffolding/set/update/{id}', [SetController::class, 'updateSet']);
        Route::get('/scaffolding/set/hapus/{id}', [SetController::class, 'deleteSet']);
    });

    Route::get('/pdf/daftar-harga', [PdfRenderController::class, 'pdfPriceList'])->middleware('can:price_list');

    Route::middleware('can:purchase')->group(function(){
        Route::get('/scaffolding/pembelian', [PurchaseController::class, 'purchase']);
        Route::get('/scaffolding/pembelian/input', [PurchaseController::class, 'createFormPurchase']);
        Route::post('/scaffolding/pembelian/simpan', [PurchaseController::class, 'createPurchase']);
        Route::get('/scaffolding/pembelian/edit/{id}', [PurchaseController::class, 'updateFormPurchase']);
        Route::post('/scaffolding/pembelian/update/{id}', [PurchaseController::class, 'updatePurchase']);
        Route::get('/scaffolding/pembelian/hapus/{id}', [PurchaseController::class, 'deletePurchase']);
        Route::get('/scaffolding/pembelian/detail/{id}', [PurchaseController::class, 'detailPurchase']);
    });

    Route::middleware('can:repair')->group(function(){
        Route::get('/scaffolding/perbaikan', [RepairController::class, 'repair']);
        Route::get('/scaffolding/perbaikan/input', [RepairController::class, 'createFormDraftRepair']);
        Route::post('/scaffolding/perbaikan/simpan', [RepairController::class, 'createDraftRepair']);
        Route::get('/scaffolding/perbaikan/edit/{id}', [RepairController::class, 'updateFormDraftRepair']);
        Route::post('/scaffolding/perbaikan/update/{id}', [RepairController::class, 'updateDraftRepair']);
        Route::get('/scaffolding/perbaikan/detail/{id}', [RepairController::class, 'detailRepair']);
        Route::get('/scaffolding/perbaikan/hapus/{id}', [RepairController::class, 'deleteDraftRepair']);
        Route::post('/scaffolding/perbaikan/upload-kwitansi/{id}', [RepairController::class, 'uploadReceiptRepair']);
        Route::get('/scaffolding/perbaikan/proses/{id}', [RepairController::class, 'startRepair']);
        Route::get('/scaffolding/perbaikan/selesai/{id}', [RepairController::class, 'finishRepair']);
    });

    Route::middleware('can:stock')->group(function(){
        Route::get('/scaffolding/stok/{category?}', [StockController::class, 'stock']);
        Route::get('/scaffolding/stok/item/{itemId}', [StockController::class, 'stockItem']);

        Route::get('/pdf/stok-item', [PdfRenderController::class, 'pdfStockItem']);
    });

    Route::middleware('can:rent')->group(function(){
        Route::get('/sewa/draft', [RentController::class, 'draftRent']);
        Route::get('/sewa/draft/input/{id?}', [RentController::class, 'createFormDraftRent']);
        Route::get('/sewa/draft/penyewa/{renterId}', [RentController::class, 'createFormDraftRentExistsRenter']);
        Route::post('/sewa/draft/buat/{id?}', [RentController::class, 'createDraftRent']);
        Route::post('/sewa/draft/penyewa/buat/{renterId}', [RentController::class, 'createDraftRentExistsRenter']);
        Route::get('/sewa/draft/detail/{id}', [RentController::class, 'detailDraftRent']);
        Route::get('/sewa/draft/edit/{id}', [RentController::class, 'updateFormDraftRent']);
        Route::post('/sewa/draft/update/{id}', [RentController::class, 'updateDraftRent']);
        Route::get('/sewa/draft/hapus/{id}', [RentController::class, 'deleteDraftRent']);
        Route::post('/sewa/draft/upload-invoice/{id}', [RentController::class, 'uploadInvoiceDraftRent']);
        Route::get('/sewa/draft/setujui/{id}', [RentController::class, 'approvalRent'])->middleware('can:approve_rent');

        Route::get('/sewa/penyewaan/berjalan', [RentController::class, 'ongoingRent']);
        Route::get('/sewa/penyewaan/detail/{id}', [RentController::class, 'detailApprovedRent']);
        Route::post('/sewa/penyewaan/detail/upload-berkas/{id}', [RentController::class, 'uploadFilesRent']);

        Route::post('/sewa/pengembalian/{id}', [RentController::class, 'returnRent']);
        Route::post('/sewa/pengembalian/upload-invoice/{id}', [RentController::class, 'uploadInvoiceReturnRent']);
        Route::post('/sewa/pengembalian/upload-kwitansi/{id}', [RentController::class, 'uploadReceiptReturnRent']);
        Route::get('/sewa/penyewaan/selesai', [RentController::class, 'finishedRent']);
        Route::get('/sewa/pembukuan-sewa', [RentController::class, 'bookRent']);

        Route::get('/sewa/penyewa', [RenterController::class, 'renter']);
        Route::get('/sewa/penyewa/input', [RenterController::class, 'createFormRenter']);
        Route::post('/sewa/penyewa/buat', [RenterController::class, 'createRenter']);
        Route::get('/sewa/penyewa/edit/{renterId}', [RenterController::class, 'updateFormRenter']);
        Route::post('/sewa/penyewa/update/{renterId}', [RenterController::class, 'updateRenter']);
        Route::get('/sewa/penyewa/hapus/{renterId}', [RenterController::class, 'deleteRenter']);
        Route::get('/sewa/penyewa/detail/{renterId}', [RenterController::class, 'detailRenter']);

        Route::get('/sewa/grafik', [RentController::class, 'rentChart']);
        Route::get('/sewa/grafik/data', [RentController::class, 'rentChartData']);

        Route::get('/pdf/buku-penyewaan/{startDate}/{endDate}/{rentStatus}/{rentStatusPayment}/{rentReturnPaymentStatus}/{rentReturnReceiptStatus}/{rentReturnIsComplete}/{rentReturnStatus}', [PdfRenderController::class, 'pdfBookRent']);

    });

    Route::middleware('can:finance')->group(function(){
        Route::get('/keuangan/arus-kas', [CashFlowController::class, 'cashFlows']);
        Route::get('/keuangan/arus-kas/pemasukan', [CashFlowController::class, 'cashIncome']);
        Route::get('/keuangan/arus-kas/pengeluaran', [CashFlowController::class, 'cashExpense']);
        Route::get('/keuangan/pengeluaran', [ExpenseController::class, 'expense']);
        Route::get('/keuangan/pengeluaran/draft/input', [ExpenseController::class, 'createFormDraftExpense']);
        Route::post('/keuangan/pengeluaran/draft/buat', [ExpenseController::class, 'createDraftExpense']);
        Route::get('/keuangan/pengeluaran/draft/edit/{expenseId}', [ExpenseController::class, 'updateFormDraftExpense']);
        Route::post('/keuangan/pengeluaran/draft/update/{expenseId}', [ExpenseController::class, 'updateDraftExpense']);
        Route::get('/keuangan/pengeluaran/detail/{expenseId}', [ExpenseController::class, 'detailExpense']);
        Route::get('/keuangan/pengeluaran/draft/hapus/{expenseId}', [ExpenseController::class, 'deleteDraftExpense']);
        Route::get('/keuangan/pengeluaran/draft/post/{expenseId}', [ExpenseController::class, 'postExpense']);

        Route::get('/keuangan/grafik', [CashFlowController::class, 'cashChart']);
        Route::get('/keuangan/grafik/data', [CashFlowController::class, 'cashChartData']);
        // Route::get('/keuangan/kas-awal', [CashController::class, 'initialBalanceCash']);
        // Route::post('/keuangan/kas-awal/simpan', [CashController::class, 'saveIntialBalanceCash']);

        Route::get('/pdf/arus-kas/{startDate}/{endDate}', [PdfRenderController::class, 'pdfCashFlow']);
        Route::get('/pdf/arus-kas-pemasukan/{startDate}/{endDate}', [PdfRenderController::class, 'pdfCashFlowIncome']);
        Route::get('/pdf/arus-kas-pengeluaran/{startDate}/{endDate}', [PdfRenderController::class, 'pdfCashFlowExpense']);
    
    });
});

Route::middleware('can:user')->group(function(){
    Route::get('/user', [UserController::class, 'listUser']);
    Route::get('/user/input', [UserController::class, 'createFormUser']);
    Route::post('/user/simpan', [UserController::class, 'createUser']);
    Route::get('/user/edit/{id}', [UserController::class, 'updateFormUser']);
    Route::post('/user/update/{id}', [UserController::class, 'updateUser']);
    Route::get('/user/hapus/{id}', [UserController::class, 'deleteUser']);
});

Route::get('/wa/chat/{whatsappNumber}', [WhatsappTemplateController::class, 'whatsappStartChat']);
Route::get('/wa/invoice-penyewaan/{id}', [WhatsappTemplateController::class, 'whatsappInvoiceRent']);
Route::get('/wa/invoice-pengembalian/{id}', [WhatsappTemplateController::class, 'whatsappInvoiceRentReturn']);
Route::get('/wa/permintaan-persetujuan-draft-sewa/{userId}/{rentId}', [WhatsappTemplateController::class, 'whatsappRequestApprovingRent']);
Route::get('/wa/informasi-sisa-waktu-sewa/{rentId}', [WhatsappTemplateController::class, 'whatsappRemainingDurationRent']);

Route::get('/pdf/invoice-penyewaan/{id}', [PdfRenderController::class, 'pdfInvoiceRent']);
Route::get('/pdf/kwitansi-penyewaan/{id}', [PdfRenderController::class, 'pdfReceiptRent']);
Route::get('/pdf/surat-pernyataan-penyewaan/{id}', [PdfRenderController::class, 'pdfStatementLetterRent']);
Route::get('/pdf/berita-acara-penyewaan/{id}', [PdfRenderController::class, 'pdfEventReportRent']);
Route::get('/pdf/surat-jalan-penyewaan/{id}', [PdfRenderController::class, 'pdfTransportLetterRent']);
Route::get('/pdf/invoice-pengembalian-penyewaan/{id}', [PdfRenderController::class, 'pdfInvoiceRentReturn']);
Route::get('/pdf/kwitansi-pengembalian-penyewaan/{id}', [PdfRenderController::class, 'pdfReceiptRentReturn']);

Route::get('/file/{filePath}', [FileViewerController::class, 'viewFile']);



