<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Contest;
use App\Models\Transaction;
use App\Models\DetailContestCriteriaContent;
use App\Models\ContestJury;
use App\Models\CashFlow;

use App\Helper\ResponseHelper as JsonHelper;

class AnalyzerController extends Controller
{
    public function analyzer (Request $request) {
        $res = new JsonHelper;

        $filter = $request->input('filter');
        $idContest = $request->input('id_contest');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        $data = [];

        if ($filter == 'contest') {
            $data = Contest::where('id', $idContest)->where('soft_delete', 0)->get();
        } else if ($filter == 'date') {
            $data = Contest::whereBetween('contest_date', [$dateStart, $dateEnd])->where('soft_delete', 0)->get();
        } else {
            return $res->responseGet(false, 400, 'Please fill the enter', '');
        }

        $contestantTotal = 0;
        $juryTotal = 0;
        $contestTotal = 0;

        $ticketTotal = 0;
        $ticketSold = 0;
        $ticketIncome = 0;

        $incomeTotal = 0;
        $outcomeTotal = 0;
        $profitTotal = 0;

        foreach ($data as $k => $v) {
            //1
            $contestantTotalData = Transaction::select('id_user')
                                                ->where('id_contest', $v->id)
                                                ->where('status', 'done')
                                                ->groupBy('id_user')
                                                ->where('soft_delete', 0)
                                                ->get();
            $contestantTotal += count($contestantTotalData);

            //2
            $juryTotalData = ContestJury::where('id_contest', $v->id)
                                                ->where('soft_delete', 0)
                                                ->get();
            $juryTotal += count($juryTotalData);

            // 3
            $contestTotalData = Contest::where('id', $v->id)
                                                ->where('soft_delete', 0)
                                                ->get();
            $contestTotal += count($contestTotalData);

            //4
            $ticketTotalData = DetailContestCriteriaContent::where('id_contest', $v->id)
                                                            ->where('soft_delete', 0)
                                                            ->get();
            if (count($ticketTotalData) > 0) {
                foreach ($ticketTotalData as $k1 => $v1) {
                    $ticketTotal += (int)$v1->participants;
                }
            }
            
            //5
            $ticketSoldData = Transaction::where('id_contest', $v->id)
                                                ->where('status', 'done')
                                                ->where('soft_delete', 0)
                                                ->get();
            $ticketSold += count($ticketSoldData);

            //6
            $ticketIncomeData = Transaction::where('id_contest', $v->id)
                                                ->where('status', 'done')
                                                ->where('soft_delete', 0)
                                                ->get();

            if (count($ticketIncomeData) > 0) {
                foreach ($ticketIncomeData as $k1 => $v1) {
                    $ticketIncome += (int)$v1->price;
                }
            }

            //7
            $incomeTotalData = CashFlow::where('id_contest', $v->id)
                                        ->where('type', 'in')
                                        ->where('soft_delete', 0)
                                        ->get();

            if (count($incomeTotalData) > 0) {
                foreach ($incomeTotalData as $k1 => $v1) {
                    $incomeTotal += (int)$v1->nominal * (int)$v1->amount;
                }
            }

            //8
            $outcomeTotalData = CashFlow::where('id_contest', $v->id)
                                        ->where('type', 'out')
                                        ->where('soft_delete', 0)
                                        ->get();

            if (count($outcomeTotalData) > 0) {
                foreach ($outcomeTotalData as $k1 => $v1) {
                    $outcomeTotal += (int)$v1->nominal * (int)$v1->amount;
                }
            }

        }

        $arrRes = [
            'contestant_total' => (int)$contestantTotal,
            'jury_total' => (int)$juryTotal,
            'contest_total' => (int)$contestTotal,

            'ticket_total' => (int)$ticketTotal,
            'ticket_sold' => (int)$ticketSold,
            'ticket_income' => (int)$ticketIncome,

            'income_total' => (int)$incomeTotal,
            'outcome_total' => (int)$outcomeTotal,
            'profit_total' => (int)$incomeTotal + (int)$ticketIncome - (int)$outcomeTotal
        ];

        return $res->responseGet(true, 200, $arrRes, '');
    }
}
