<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Models\Rent;
use App\Models\Stock;
use App\Models\Item;
use App\Models\Set;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfRenderController extends Controller
{
    public function pdfInvoiceRent($id){
        $id = decrypt($id);
        $rent = Rent::with('renter', 'rentSet', 'rentItem')->find($id);

        if($rent->rent_is_extension==1){
            $rent_extend = Rent::with('renter')->find($rent->rent_id_extend);
        } else {
            $rent_extend = null;
        }

        $data = [
            'rent' => $rent,
            'rent_extend' => $rent_extend,
        ];

        $draft = Pdf::loadView('pdf.rent-invoice', $data);
        $draft->setPaper('A4', 'portrait');

        return $draft->stream();
    }

    public function pdfReceiptRent($id){
        $id = decrypt($id);
        $rent = Rent::with('renter', 'rentSet', 'rentItem', 'set', 'item')->find($id);

        if($rent->rent_is_extension==1){
            $rent_extend = Rent::with('renter')->find($rent->rent_id_extend);
        } else {
            $rent_extend = null;
        }

        $data = [
            'rent' => $rent,
            'rent_extend' => $rent_extend
        ];

        $draft = Pdf::loadView('pdf.rent-receipt', $data);
        $draft->setPaper('A4', 'portrait');

        return $draft->stream();
    }

    public function pdfStatementLetterRent($id){
        $id = decrypt($id);
        $rent = Rent::with('renter', 'rentSet', 'rentItem', 'set', 'item', 'rentReturn', 'rentReturnItem')->find($id);

        if($rent->rent_is_extension==1){
            $rent_extend = Rent::with('renter')->find($rent->rent_id_extend);
        } else {
            $rent_extend = null;
        }

        $data = [
            'rent' => $rent,
            'rent_extend' => $rent_extend
        ];

        $draft = Pdf::loadView('pdf.rent-statement-letter', $data);
        $draft->setPaper('A4', 'portrait');

        return $draft->stream();
    }

    public function pdfEventReportRent($id){
        $id = decrypt($id);
        $rent = Rent::with('renter', 'rentSet', 'rentItem', 'set', 'item', 'rentReturn', 'rentReturnItem')->find($id);

        if($rent->rent_is_extension==1){
            $rent_extend = Rent::with('renter')->find($rent->rent_id_extend);
        } else {
            $rent_extend = null;
        }

        $data = [
            'rent' => $rent,
            'rent_extend' => $rent_extend
        ];

        $draft = Pdf::loadView('pdf.rent-event-report', $data);
        $draft->setPaper('A4', 'portrait');

        return $draft->stream();
    }

    public function pdfTransportLetterRent($id){
        $id = decrypt($id);
        $rent = Rent::with('renter', 'rentSet', 'rentItem', 'set', 'item', 'rentReturn', 'rentReturnItem')->find($id);

        if($rent->rent_is_extension==1){
            $rent_extend = Rent::with('renter')->find($rent->rent_id_extend);
        } else {
            $rent_extend = null;
        }

        $item = $rent->rentItem->groupBy('item_id');
        $deposit = $rent->rent_deposit ? 1 : 0;
        $discount = $rent->rent_discount ? 1 : 0;
        $item = $item->map(function($item) use ($rent) {
            return [
                'item_id' => \App\Helpers\IDHelper::genId($item[0]->item_id),
                'item_name' => $item[0]->item->item_name,
                'item_unit' => $item[0]->item->item_unit,
                'rent_item_quantity' => $item->sum('rent_item_quantity'),
            ];
        });
        $item = $item->sortBy('item_id')->values()->all();

        $data = [
            'rent' => $rent,
            'rent_extend' => $rent_extend,
            'item' => $item,
        ];

        $draft = Pdf::loadView('pdf.rent-transport-letter', $data);
        $draft->setPaper('A4', 'portrait');

        return $draft->stream();
    }

    public function pdfInvoiceRentReturn($id){
        $id = decrypt($id);
        $rent = Rent::with('renter', 'rentReturn')->find($id);

        if($rent->rent_is_extension==1){
            $rent_extend = Rent::with('renter')->find($rent->rent_id_extend);
        } else {
            $rent_extend = null;
        }

        $item = $rent->rentItem->groupBy('item_id');
        $returnItem = $rent->rentReturnItem;
        foreach ($returnItem as $r) {
            $returnItems[$r->item_id]['item_lost'] = $r->rent_return_item_lost;
            $returnItems[$r->item_id]['item_damaged'] = $r->rent_return_item_damaged;
            $returnItems[$r->item_id]['total_fine'] = $r->rent_return_item_total_fine;
        }
        $item = $item->map(function($item) use ($rent, $returnItems) {
            return [
                'item_id' => \App\Helpers\IDHelper::genID($item[0]->item_id),
                'item_name' => $item[0]->item->item_name,
                'item_unit' => $item[0]->item->item_unit,
                'item_quantity' => $item->sum('rent_item_quantity'),
                'item_lost' => $returnItems[$item[0]->item_id]['item_lost'],
                'item_damaged' => $returnItems[$item[0]->item_id]['item_damaged'],
                'total_fine' => $returnItems[$item[0]->item_id]['total_fine'],
            ];
        });
        $item = $item->sortBy('item_id')->values()->all();
        $data = [
            'rent' => $rent,
            'item' => $item,
            'rent_extend' => $rent_extend
        ];

        $draft = Pdf::loadView('pdf.rent-return-invoice', $data);
        $draft->setPaper('A4', 'portrait');

        return $draft->stream();
    }

    public function pdfReceiptRentReturn($id){
        $id = decrypt($id);
        $rent = Rent::with('renter', 'rentSet', 'rentItem', 'set', 'item', 'rentReturn', 'rentReturnItem')->find($id);

        if($rent->rent_is_extension==1){
            $rent_extend = Rent::with('renter')->find($rent->rent_id_extend);
        } else {
            $rent_extend = null;
        }

        $item = $rent->rentItem->groupBy('item_id');
        $returnItem = $rent->rentReturnItem;
        foreach ($returnItem as $r) {
            $returnItems[$r->item_id]['item_lost'] = $r->rent_return_item_lost;
            $returnItems[$r->item_id]['item_damaged'] = $r->rent_return_item_damaged;
            $returnItems[$r->item_id]['total_fine'] = $r->rent_return_item_total_fine;
        }
        $item = $item->map(function($item) use ($rent, $returnItems) {
            return [
                'item_id' => \App\Helpers\IDHelper::genID($item[0]->item_id),
                'item_name' => $item[0]->item->item_name,
                'item_unit' => $item[0]->item->item_unit,
                'item_quantity' => $item->sum('rent_item_quantity'),
                'item_lost' => $returnItems[$item[0]->item_id]['item_lost'],
                'item_damaged' => $returnItems[$item[0]->item_id]['item_damaged'],
                'total_fine' => $returnItems[$item[0]->item_id]['total_fine'],
            ];
        });
        $item = $item->sortBy('item_id')->values()->all();
        $data = [
            'rent' => $rent,
            'item' => $item,
            'rent_extend' => $rent_extend
        ];

        $draft = Pdf::loadView('pdf.rent-return-receipt', $data);
        $draft->setPaper('A4', 'portrait');

        return $draft->stream();
    }

    public function pdfBookRent($rentStartDate, $rentEndDate, $rentStatus, $rentStatusPayment, $rentReturnPaymentStatus, $rentReturnReceiptStatus, $rentReturnIsComplete, $rentReturnStatus){

        $rentStatus = ($rentStatus === 'Semua' || is_null($rentStatus)) ? ['Berjalan', 'Selesai'] : [$rentStatus];

        $rentStatusPayment = ($rentStatusPayment === 'Semua' || is_null($rentStatusPayment)) ? ['Lunas', 'Belum Bayar'] : [$rentStatusPayment];

        $rentReturnPaymentStatus = ($rentReturnPaymentStatus === 'Semua' || is_null($rentReturnPaymentStatus)) ? ['Lunas', 'Belum Bayar', 'Pending'] : [$rentReturnPaymentStatus];

        $rentReturnReceiptStatus = ($rentReturnReceiptStatus === 'Semua' || is_null($rentReturnReceiptStatus)) ? ['Nihil', 'Pengembalian Deposit', 'Klaim Ganti Rugi'] : [$rentReturnReceiptStatus];

        $rentReturnStatus = ($rentReturnStatus === 'Semua' || is_null($rentReturnStatus)) ? ['Selesai', 'Lanjut'] : [$rentReturnStatus];

        $rentReturnIsComplete = ($rentReturnIsComplete === 'Semua' || is_null($rentReturnIsComplete)) ? [0, 1] : [$rentReturnIsComplete];

        // Mulai Query
        $rent = Rent::whereIn('rent_status', $rentStatus)
        ->whereIn('rent_status_payment', $rentStatusPayment)
        ->with(['renter','rentItem','rentReturn'])
        ->where(function($query) use ($rentReturnStatus, $rentReturnPaymentStatus, $rentReturnReceiptStatus, $rentReturnIsComplete) {
            $query->whereHas('rentReturn', function($q) use ($rentReturnStatus, $rentReturnPaymentStatus, $rentReturnReceiptStatus, $rentReturnIsComplete) {
                $q->whereIn('rent_return_status', $rentReturnStatus)
                    ->whereIn('rent_return_payment_status', $rentReturnPaymentStatus)
                    ->whereIn('rent_return_receipt_status', $rentReturnReceiptStatus)
                    ->whereIn('rent_return_is_complete', $rentReturnIsComplete);
            })
            // tambahkan orWhereDoesntHave
            ->orWhereDoesntHave('rentReturn');
        })
        ->orderBy('rent_number', 'asc');


        // Filter berdasarkan tanggal
        if (!is_null($rentStartDate) && !is_null($rentEndDate)) {
            $rent->where(function ($query) use ($rentStartDate, $rentEndDate) {
                $query->whereBetween('rent_start_date', [$rentStartDate, $rentEndDate])
                    ->orWhereBetween('rent_end_date', [$rentStartDate, $rentEndDate]);
            });
        }

        $rent = $rent->get();

        $data = [
            'rent' => $rent,
            'rent_start_date' => $rentStartDate,
            'rent_end_date' => $rentEndDate,
            'rent_status' => $rentStatus,
            'rent_status_payment' => $rentStatusPayment,
            'rent_return_payment_status' => $rentReturnPaymentStatus,
            'rent_return_receipt_status' => $rentReturnReceiptStatus,
            'rent_return_is_complete' => $rentReturnIsComplete,
            'rent_return_status' => $rentReturnStatus,
        ];

        $draft = Pdf::loadView('pdf.rent-book', $data);
        $draft->setPaper('A4', 'landscape');

        return $draft->stream();
    }

    public function pdfCashFlow($cashFlowStartDate, $cashFlowEndDate){

        if(!$cashFlowStartDate || !$cashFlowEndDate){
            $cashFlow = CashFlow::orderBy('created_at', 'desc')->get();
            $cashBalanceBefore = $cashFlow->first()->cash_flow_balance_before ?? 0;
            $cashBalanceAfter = $cashFlow->last()->cash_flow_balance_after ?? 0;
        } else {
            $cashFlow = CashFlow::whereBetween('created_at', [$cashFlowStartDate.' 00:00:00', $cashFlowEndDate.' 23.59.59'])
                ->orderBy('created_at', 'asc')
                ->get();
            $cashBalanceBefore = $cashFlow->first()->cash_flow_balance_before ?? 0;
            $cashBalanceAfter = $cashFlow->last()->cash_flow_balance_after ?? 0;
        }

        $data = [
            'cash_flow' => $cashFlow,
            'cash_balance_before' => $cashBalanceBefore,
            'cash_balance_after' => $cashBalanceAfter,
            'cash_flow_start_date' => $cashFlowStartDate,
            'cash_flow_end_date' => $cashFlowEndDate,
        ];

        $draft = Pdf::loadView('pdf.cash-flow', $data);
        $draft->setPaper('A4', 'landscape');

        return $draft->stream();
    }

    public function pdfCashFlowIncome($cashFlowStartDate, $cashFlowEndDate){
        if(!$cashFlowStartDate || !$cashFlowEndDate){
            $cashFlow = CashFlow::whereNotNull('cash_flow_income_category')
                ->orderBy('created_at', 'asc')
                ->get();
            $incomeTotalBefore = $cashFlow->first()->cash_flow_income_total_before ?? 0;
            $incomeTotalAfter = $cashFlow->last()->cash_flow_income_total_after ?? 0;
        } else {
            $cashFlow = CashFlow::whereNotNull('cash_flow_income_category')
                ->whereBetween('created_at', [$cashFlowStartDate.' 00:00:00', $cashFlowEndDate.' 23.59.59'])
                ->orderBy('created_at', 'asc')
                ->get();
            $incomeTotalBefore = $cashFlow->first()->cash_flow_income_total_before ?? 0;
            $incomeTotalAfter = $cashFlow->last()->cash_flow_income_total_after ?? 0;
        }

        $data = [
            'cash_flow' => $cashFlow,
            'income_total_before' => $incomeTotalBefore,
            'income_total_after' => $incomeTotalAfter,
            'cash_flow_start_date' => $cashFlowStartDate,
            'cash_flow_end_date' => $cashFlowEndDate,
        ];

        $draft = Pdf::loadView('pdf.cash-flow-income', $data);
        $draft->setPaper('A4', 'landscape');

        return $draft->stream();
    }

    public function pdfCashFlowExpense($cashFlowStartDate, $cashFlowEndDate){
        if(!$cashFlowStartDate || !$cashFlowEndDate){
            $cashFlow = CashFlow::whereNotNull('cash_flow_expense_category')
                ->orderBy('created_at', 'asc')
                ->get();
            $expenseTotalBefore = $cashFlow->first()->cash_flow_expense_total_before ?? 0;
            $expenseTotalAfter = $cashFlow->last()->cash_flow_expense_total_after ?? 0;
        } else {
            $cashFlow = CashFlow::whereNotNull('cash_flow_expense_category')
                ->whereBetween('created_at', [$cashFlowStartDate.' 00:00:00', $cashFlowEndDate.' 23.59.59'])
                ->orderBy('created_at', 'asc')
                ->get();
            $expenseTotalBefore = $cashFlow->first()->cash_flow_expense_total_before ?? 0;
            $expenseTotalAfter = $cashFlow->last()->cash_flow_expense_total_after ?? 0;
        }

        $data = [
            'cash_flow' => $cashFlow,
            'expense_total_before' => $expenseTotalBefore,
            'expense_total_after' => $expenseTotalAfter,
            'cash_flow_start_date' => $cashFlowStartDate,
            'cash_flow_end_date' => $cashFlowEndDate,
        ];

        $draft = Pdf::loadView('pdf.cash-flow-expense', $data);
        $draft->setPaper('A4', 'landscape');

        return $draft->stream();
    }

    public function pdfStockItem(){
        $stock = Stock::with('item')->get();
        $data = [
            'stock' => $stock,
        ];

        $draft = Pdf::loadView('pdf.stock-item', $data);
        $draft->setPaper('A4', 'landscape');

        return $draft->stream();
    }

    public function pdfPriceList(){
        $item = Item::with('itemSet')->get();
        $set = Set::with('item', 'itemSet')->get();
        $data = [
            'item' => $item,
            'set' => $set,
        ];

        $draft = Pdf::loadView('pdf.price-list', $data);
        $draft->setPaper('A4', 'portrait');

        return $draft->stream();
    }
}
