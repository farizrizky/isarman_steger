<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    public function cashFlows(Request $request){
        $cashFlowStartDate = $request->cash_flow_start_date;
        $cashFlowEndDate = $request->cash_flow_end_date;

        if(is_null($cashFlowStartDate) || is_null($cashFlowEndDate)){
            $cashFlow = CashFlow::orderBy('created_at', 'asc')->get();
            $cashBalance = $cashFlow->last()->cash_flow_balance_after ?? 0;
        }else{
            $cashFlow = CashFlow::whereBetween('created_at', [$cashFlowStartDate.' 00:00:00', $cashFlowEndDate.' 23.59.59'])->orderBy('created_at', 'asc')->get();
            $cashBalance = $cashFlow->last()->cash_flow_balance_after ?? 0;
        }
        
        $data = [
            'cash_flow' => $cashFlow,
            'cash_flow_start_date' => $cashFlowStartDate,
            'cash_flow_end_date' => $cashFlowEndDate,
            'cash_balance' => $cashBalance,
        ];
        return view('finance.cash-flow-list', $data);
    }

    public function cashIncome(Request $request){
        $cashFlowStartDate = $request->cash_flow_start_date;
        $cashFlowEndDate = $request->cash_flow_end_date;

        if(is_null($cashFlowStartDate) || is_null($cashFlowEndDate)){
            $cashFlow = CashFlow::whereNotNull('cash_flow_income_category')->orderBy('created_at', 'asc')->get();
            $cashIncomeTotal = $cashFlow->last()->cash_flow_income_total_after ?? 0;
        }else{
            $cashFlow = CashFlow::whereNotNull('cash_flow_income_category')
                ->whereBetween('created_at', [$cashFlowStartDate.' 00:00:00', $cashFlowEndDate.' 23.59.59'])
                ->orderBy('created_at', 'asc')->get();
            $cashIncomeTotal = $cashFlow->last()->cash_flow_income_total_after ?? 0;
        }

        $data = [
            'cash_flow' => $cashFlow,
            'cash_income_total' => $cashIncomeTotal,
            'cash_flow_start_date' => $cashFlowStartDate,
            'cash_flow_end_date' => $cashFlowEndDate,
        ];
        return view('finance.cash-flow-income-list', $data);
    }

    public function cashExpense(Request $request){
        $cashFlowStartDate = $request->cash_flow_start_date;
        $cashFlowEndDate = $request->cash_flow_end_date;

        if(is_null($cashFlowStartDate) || is_null($cashFlowEndDate)){
            $cashFlow = CashFlow::whereNotNull('cash_flow_expense_category')->orderBy('created_at', 'asc')->get();
            $cashExpenseTotal = $cashFlow->last()->cash_flow_expense_total_after ?? 0;
        }else{
            $cashFlow = CashFlow::whereNotNull('cash_flow_expense_category')
                ->whereBetween('created_at', [$cashFlowStartDate.' 00:00:00', $cashFlowEndDate.' 23.59.59'])
                ->orderBy('created_at', 'asc')->get();
            $cashExpenseTotal = $cashFlow->last()->cash_flow_expense_total_after ?? 0;
        }

        $data = [
            'cash_flow' => $cashFlow,
            'cash_expense_total' => $cashExpenseTotal,
            'cash_flow_start_date' => $cashFlowStartDate,
            'cash_flow_end_date' => $cashFlowEndDate,
        ];
        return view('finance.cash-flow-expense-list', $data);
    }

    public static function createCashFlow(Array $dataCashFlow){
        $cashFlow = [
            'cash_flow_category' => $dataCashFlow['cash_flow_category'],
            'cash_flow_description' => $dataCashFlow['cash_flow_description'],
            'cash_flow_amount' => $dataCashFlow['cash_flow_amount'],
            'cash_flow_reference_id' => $dataCashFlow['cash_flow_reference_id'],
        ];

        if(isset($dataCashFlow['cash_flow_income_category']) && isset($dataCashFlow['cash_flow_expense_category'])){
            return false;
        }else{
            if(isset($dataCashFlow['cash_flow_income_category'])){
                $cashFlow['cash_flow_income_category'] = $dataCashFlow['cash_flow_income_category'];
                $cashBalance = CashController::getCashInfo('balance');
                $cashIncomeTotal = CashController::getCashInfo('income_total');
                $cashFlow['cash_flow_balance_before'] = $cashBalance;
                $cashFlow['cash_flow_income_total_before'] = $cashIncomeTotal;
                $cashBalance += $dataCashFlow['cash_flow_amount'];
                $cashIncomeTotal += $dataCashFlow['cash_flow_amount'];
                CashController::updateCash('income_total', $cashIncomeTotal);
                CashController::updateCash('balance', $cashBalance);
                $cashFlow['cash_flow_income_total_after'] = $cashIncomeTotal;
                $cashFlow['cash_flow_balance_after'] = $cashBalance;
            }

            if(isset($dataCashFlow['cash_flow_expense_category'])){
                $cashFlow['cash_flow_expense_category'] = $dataCashFlow['cash_flow_expense_category'];
                $cashBalance = CashController::getCashInfo('balance');
                $cashExpenseTotal = CashController::getCashInfo('expense_total');
                $cashFlow['cash_flow_expense_total_before'] = $cashExpenseTotal;
                $cashFlow['cash_flow_balance_before'] = $cashBalance;
                $cashBalance -= $dataCashFlow['cash_flow_amount'];
                $cashExpenseTotal += $dataCashFlow['cash_flow_amount'];
                CashController::updateCash('expense_total', $cashExpenseTotal);
                CashController::updateCash('balance', $cashBalance);
                $cashFlow['cash_flow_balance_after'] = $cashBalance;
                $cashFlow['cash_flow_expense_total_after'] = $cashExpenseTotal;
            }

            CashFlow::create($cashFlow);
        }
    }

    public function cashChart(){
        return view('finance.cash-flow-chart');
    }

    public function cashChartData(Request $request){
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $dataType = $request->data_type;

        if(is_null($startDate) || is_null($endDate)){
            $cashFlow = CashFlow::orderBy('created_at', 'asc')->get();
        }

        $dataBalance = [];
        $dataIncome = [];
        $dataExpense = [];
        $label = [];
        
        if($dataType == "Per Day"){
            $cashIncome = CashFlow::whereNotNull('cash_flow_income_category')
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23.59.59'])
                ->selectRaw('DATE(created_at) as date, SUM(cash_flow_amount) as total')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
            foreach($cashIncome as $income){
                $dataIncome[] = $income->total;
                $label[] = date('d M Y', strtotime($income->date));
            }

            $cashExpense = CashFlow::whereNotNull('cash_flow_expense_category')
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23.59.59'])
                ->selectRaw('DATE(created_at) as date, SUM(cash_flow_amount) as total')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
            foreach($cashExpense as $expense){
                $dataExpense[] = $expense->total;
            }

            foreach($label as $key => $value){
                $dataBalance[] = $dataIncome[$key] - $dataExpense[$key];
            }
        }else if($dataType == "Per Month"){
           $cashIncome = CashFlow::whereNotNull('cash_flow_income_category')
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23.59.59'])
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(cash_flow_amount) as total')
                ->groupBy('month', 'year')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
            foreach($cashIncome as $income){
                $dataIncome[] = $income->total;
                $label[] = date('M Y', strtotime($income->year.'-'.$income->month.'-01'));
            }

            $cashExpense = CashFlow::whereNotNull('cash_flow_expense_category')
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23.59.59'])
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(cash_flow_amount) as total')
                ->groupBy('month', 'year')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
            foreach($cashExpense as $expense){
                $dataExpense[] = $expense->total;
            }

            foreach($label as $key => $value){
                $dataBalance[] = $dataIncome[$key] - $dataExpense[$key];
            }
        }else if($dataType == "Per Year"){
            $cashIncome = CashFlow::whereNotNull('cash_flow_income_category')
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23.59.59'])
                ->selectRaw('YEAR(created_at) as year, SUM(cash_flow_amount) as total')
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->get();
            foreach($cashIncome as $income){
                $dataIncome[] = $income->total;
                $label[] = date('Y', strtotime($income->year.'-01-01'));
            }

            $cashExpense = CashFlow::whereNotNull('cash_flow_expense_category')
                ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23.59.59'])
                ->selectRaw('YEAR(created_at) as year, SUM(cash_flow_amount) as total')
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->get();
            foreach($cashExpense as $expense){
                $dataExpense[] = $expense->total;
            }

            foreach($label as $key => $value){
                $dataBalance[] = $dataIncome[$key] - $dataExpense[$key];
            }
        }

        $data = [
            'data_balance' => $dataBalance,
            'data_income' => $dataIncome,
            'data_expense' => $dataExpense,
            'label' => $label,
        ];

        return response()->json($data);
    }
}
