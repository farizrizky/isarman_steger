<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{   
    public function expense(){
        $expense = Expense::orderBy('created_at', 'asc')->get();
        $data = [
            'expense' => $expense,
        ];
        return view('finance.expense-list', $data);
    }

    public function createFormDraftExpense(){
        return view('finance.expense-draft-create-form');
    }

    public function createDraftExpense(Request $request){
        $expenseDate = $request->expense_date;
        $expenseMonth = date('m', strtotime($expenseDate));
        $expenseYear = date('Y', strtotime($expenseDate));

        if($expenseMonth == date('m') && $expenseYear == date('Y')){
            $expense = Expense::create($request->all());
            $expenseId = $expense->expense_id;

            $pathFile = $request->file('expense_file_file');
            $fileExtension = $pathFile->getClientOriginalExtension();
            $fileName = 'expense_'.$expenseId.'.'.$fileExtension;
            $pathFile = $pathFile->storeAs('expense', $fileName, 'public');

            Expense::where('expense_id', $expenseId)->update([
                'expense_file' => 'public/'.$pathFile,
            ]);

            $sweetalert = [
                'state' => "success",
                'title' => "Berhasil",
                'message' => "Draft Pengeluaran berhasil dibuat"
            ];
            return redirect('/keuangan/pengeluaran/detail/'.$expenseId)->with('sweetalert', $sweetalert);
        }else{
            $sweetalert = [
                'state' => "error",
                'title' => "Terjadi Kesalahan",
                'message' => "Pengeluaran hanya dapat dibuat untuk bulan dan tahun saat ini"
            ];
            return back()->with('sweetalert', $sweetalert);
        }
    }

    public function updateFormDraftExpense($expenseId){
        $expense = Expense::where('expense_status', 'Draft')->find($expenseId);
        if(is_null($expense)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pengeluaran tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $expenseDate = $expense->created_at;
        $expenseMonth = date('m', strtotime($expenseDate));
        $expenseYear = date('Y', strtotime($expenseDate));

        if($expenseMonth == date('m') && $expenseYear == date('Y')){
            $data = [
                'expense' => $expense,
            ];

            return view('finance.expense-draft-update-form', $data);
        }else{
            $sweetalert = [
                'state' => "error",
                'title' => "Terjadi Kesalahan",
                'message' => "Draft pengeluaran bulan sebelumnya tidak dapat diubah"
            ];
            return back()->with('sweetalert', $sweetalert);
        }
    }

    public function updateDraftExpense(Request $request, $expenseId){
        $expense = Expense::where('expense_status', 'Draft')->find($expenseId);
        if(is_null($expense)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pengeluaran tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $expenseDate = $request->expense_date;
        $expenseMonth = date('m', strtotime($expenseDate));
        $expenseYear = date('Y', strtotime($expenseDate));

        if($expenseMonth == date('m') && $expenseYear == date('Y')){
            if($request->hasFile('expense_file_file')){
                if($expense->expense_file != null){
                    Storage::disk('public')->delete($expense->expense_file);
                }

                $pathFile = $request->file('expense_file_file');
                $fileExtension = $pathFile->getClientOriginalExtension();
                $fileName = 'expense_'.$expenseId.'.'.$fileExtension;
                $pathFile = $pathFile->storeAs('expense', $fileName, 'public');
                $request->merge(['expense_file' => 'public/'.$pathFile]);
            }
            $expense->update($request->all());

            $sweetalert = [
                'state' => "success",
                'title' => "Berhasil",
                'message' => "Draft Pengeluaran berhasil diubah"
            ];
            return redirect('/keuangan/pengeluaran/detail/'.$expenseId)->with('sweetalert', $sweetalert);
        }else{
            $sweetalert = [
                'state' => "error",
                'title' => "Terjadi Kesalahan",
                'message' => "Pengeluaran hanya dapat dibuat untuk bulan dan tahun saat ini"
            ];
            return back()->with('sweetalert', $sweetalert);
        }
    }

    public function deleteDraftExpense($expenseId){
        $expense = Expense::where('expense_status', 'Draft')->find($expenseId);
        if(is_null($expense)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pengeluaran tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $expense->delete();

        $sweetalert = [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Draft Pengeluaran berhasil dihapus"
        ];
        return redirect('/keuangan/pengeluaran')->with('sweetalert', $sweetalert);
    }

    public function postExpense($expenseId){
        $expense = Expense::where('expense_status', 'Draft')->find($expenseId);
        if(is_null($expense)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pengeluaran tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $expenseDate = $expense->expense_date;
        $expenseMonth = date('m', strtotime($expenseDate));
        $expenseYear = date('Y', strtotime($expenseDate));

        if($expenseMonth == date('m') && $expenseYear == date('Y')){
            $expense->update([
                'expense_status' => 'Diposting',
                'expense_posted_at' => now(),
            ]);
        }else{
            $sweetalert = [
                'state' => "error",
                'title' => "Terjadi Kesalahan",
                'message' => "Pengeluaran hanya dapat diposting untuk bulan dan tahun saat ini"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        CashFlowController::createCashFlow([
            'cash_flow_category' => 'Pengeluaran',
            'cash_flow_expense_category' => $expense->expense_category,
            'cash_flow_description' => $expense->expense_description,
            'cash_flow_amount' => $expense->expense_amount,
            'cash_flow_reference_id' => $expense->expense_id,
        ]);

        $sweetalert = [
            'state' => "success",
            'title' => "Berhasil",
            'message' => "Pengeluaran berhasil diposting"
        ];
        return redirect('/keuangan/pengeluaran/detail/'.$expenseId)->with('sweetalert', $sweetalert);
    }

    public function detailExpense($expenseId){
        $expense = Expense::find($expenseId);
        if(is_null($expense)){
            $sweetalert = [
                'state' => "error",
                'title' => "Data Tidak Ditemukan",
                'message' => "Pengeluaran tidak ditemukan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $data = [
            'expense' => $expense,
        ];

        return view('finance.expense-detail', $data);
    }
}
