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
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);
            $end->modify('+1 day');
            $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);

            $index = 0;
            foreach ($period as $date) {
                $label[] = $date->format('d M Y');
                $dataIncome[] = CashFlow::whereNotNull('cash_flow_income_category')
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->sum('cash_flow_amount');
                $dataExpense[] = CashFlow::whereNotNull('cash_flow_expense_category')
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->sum('cash_flow_amount');
                $getLatestBalance = CashFlow::whereBetween('created_at', ['1970-1-1 00:00:00', $date->format('Y-m-d').' 23:59:59'])
                    ->orderBy('created_at', 'desc')->first()->cash_flow_balance_after ?? 0;
                $dataBalance[] = CashFlow::whereDate('created_at', $date->format('Y-m-d'))
                    ->select('cash_flow_balance_after')->get()->last()->cash_flow_balance_after ?? $getLatestBalance;
                $index++;
            }
        }else if($dataType == "Per Month"){
           $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);
            $end->modify('last day of this month');
            $period = new \DatePeriod($start, new \DateInterval('P1M'), $end);
            
            foreach ($period as $date) {
                $label[] = $date->format('M Y');
                $dataIncome[] = CashFlow::whereNotNull('cash_flow_income_category')
                    ->whereYear('created_at', $date->format('Y'))
                    ->whereMonth('created_at', $date->format('m'))
                    ->sum('cash_flow_amount');
                $dataExpense[] = CashFlow::whereNotNull('cash_flow_expense_category')
                    ->whereYear('created_at', $date->format('Y'))
                    ->whereMonth('created_at', $date->format('m'))
                    ->sum('cash_flow_amount');
                $getLatestBalance = CashFlow::whereBetween('created_at', ['1970-1-1 00:00:00', $date->format('Y-m-t').' 23:59:59'])
                    ->orderBy('created_at', 'desc')->first()->cash_flow_balance_after ?? 0;
                $dataBalance[] = CashFlow::whereYear('created_at', $date->format('Y'))
                    ->whereMonth('created_at', $date->format('m'))
                    ->select('cash_flow_balance_after')->get()->last()->cash_flow_balance_after ?? $getLatestBalance;
            }
        }else if($dataType == "Per Year"){
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);
            $end->modify('last day of December');
            $period = new \DatePeriod($start, new \DateInterval('P1Y'), $end);

            foreach ($period as $date) {
                $label[] = $date->format('Y');
                $dataIncome[] = CashFlow::whereNotNull('cash_flow_income_category')
                    ->whereYear('created_at', $date->format('Y'))
                    ->sum('cash_flow_amount');
                $dataExpense[] = CashFlow::whereNotNull('cash_flow_expense_category')
                    ->whereYear('created_at', $date->format('Y'))
                    ->sum('cash_flow_amount');
                $getLatestBalance = CashFlow::whereBetween('created_at', ['1970-1-1 00:00:00', $date->format('Y-12-31').' 23:59:59'])
                    ->orderBy('created_at', 'desc')->first()->cash_flow_balance_after ?? 0;
                $dataBalance[] = CashFlow::whereYear('created_at', $date->format('Y'))
                    ->select('cash_flow_balance_after')->get()->last()->cash_flow_balance_after ?? $getLatestBalance;
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
