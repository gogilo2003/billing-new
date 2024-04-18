<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    function index()
    {
        $today = new \DateTime(date('Y-M-j'));
        $start = date_sub($today, new \DateInterval('P11M'));
        $today = new \DateTime(date('Y-M-j'));
        return Inertia::render('Dashboard', [
            "start" => Carbon::parse($start)->isoFormat('MMM Y'),
            "end" => Carbon::parse($today)->isoFormat('MMM Y'),
            "summary" => $this->getAccounts(),
            "months" => $this->getMonths(),
        ]);
    }

    protected function getAccounts()
    {
        $transactions = Transaction::all();

        $cr = 0;
        $dr = 0;
        $balance = 0;

        // foreach ($transactions as $transaction) {
        $cr += $transactions->sum('cr');
        $dr += $transactions->sum('dr');
        $balance += $transactions->sum('balance');
        // }

        $balance = $balance * -1;

        // $total = $cr + $dr + $balance;

        // $pcr = round($cr / $total * 100);
        // $pdr = round($dr / $total * 100);
        // $pbalance = round($balance / $total * 100);


        // $lCr = $pcr . '% (' . number_format($cr, 2) . ')';
        // $lDr = $pdr . '% (' . number_format($dr, 2) . ')';
        // $lBalance = $pbalance . '% (' . number_format($balance, 2) . ')';

        $data = array(
            'labels' => ["Paid", "Invoice", "Balance"],
            'series' => [$cr, $dr, $balance]
        );
        return $data;
    }

    public function getMonths()
    {
        // $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $start = new \DateTime(date('Y-M-j'));

        $start->sub(new \DateInterval('P11M'));

        $end = new \DateTime(date('Y-M-j'));
        $end->add(new \DateInterval('P1M'));

        $s = (int) $start->format('n');

        $crs = [];
        $drs = [];
        $bls = [];

        for ($i = $start; $i < $end; $i->add(new \DateInterval('P1M'))) {

            $labels[] = $i->format('Y-M');

            $ms = new \DateTime($i->format('Y-M-01'));
            $me = new \DateTime($i->format('Y-M-t'));

            $transactions = Transaction::where('created_at', '>=', $ms)->where('created_at', '<=', $me)->get();

            $crs[] = $transactions->sum('cr');
            $drs[] = $transactions->sum('dr');
            try {
                $bls[] = $transactions->count() && $transactions->sum('balance') ? (($transactions->sum('balance') < 0) ? ($transactions->sum('balance') * -1) : $$transactions->sum('balance')) : 0;
            } catch (\Exception $e) {
                $bls[] = 0; //$e->getMessage();
            }
        }

        $data = array(
            'labels' => $labels,
            'series' => [
                [
                    "label" => "Paid",
                    "data" => $crs,
                ],
                [
                    "label" => "Invoice",
                    "data" => $drs,
                ],
                [
                    "label" => "Balance",
                    "data" => $bls,
                ]
            ],
        );

        return $data;
    }
}
