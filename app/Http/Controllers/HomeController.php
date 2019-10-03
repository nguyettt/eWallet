<?php

namespace App\Http\Controllers;

use App\Repository\CategoryEloquentRepository;
use App\Repository\TransactionEloquentRepository;
use App\Repository\WalletEloquentRepository;

class HomeController extends Controller
{
    protected $walletRepo;
    protected $catRepo;
    protected $transactionRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        WalletEloquentRepository $walletRepo,
        CategoryEloquentRepository $catRepo,
        TransactionEloquentRepository $transactionRepo
    ) {
        $this->walletRepo = $walletRepo;
        $this->catRepo = $catRepo;
        $this->transactionRepo = $transactionRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $wallets = $this->walletRepo->getAll();

        $balance = $wallets->sum('balance');
        $inflow = 0;
        $outflow = 0;
        $month = date('m');
        $year = date('Y');

        // Calculate inflow, outflow
        $transaction = $this->transactionRepo->query()
                            ->where('delete_flag', null)
                            ->where('benefit_wallet', null)
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->get();

        $inflow = $transaction->where('type', config('variable.type.income'))
                            ->sum('amount');
        $outflow = $transaction->where('type', config('variable.type.outcome'))
                            ->sum('amount');

        $income = $transaction->where('type', config('variable.type.income'));
        $income = $income->sortByDesc(function ($item) {
            return $item->amount;
        })->values();

        if (count($income) >= 5) {
            for ($i = 0; $i < 5; $i++) {
                $top_income[] = $income[$i];
            }
        } else {
            $top_income = $income;
        }

        $outcome = $transaction->where('type', config('variable.type.outcome'));
        $outcome = $outcome->sortByDesc(function ($item) {
            return $item->amount;
        })->values();

        if (count($outcome) >= 5) {
            for ($i = 0; $i < 5; $i++) {
                $top_outcome[] = $outcome[$i];
            }
        } else {
            $top_outcome = $outcome;
        }

        $before = $year . '-' . $month . '-01 00:00:00';
        $_transaction = $this->transactionRepo->query()
            ->where('created_at', '<', $before)
            ->where('delete_flag', null)
            ->where('type', '<>', config('variable.type.transfer'))
            ->get();
        $startingBalance = 0;

        foreach ($_transaction as $item) {
            if ($item->type == config('variable.type.income')) {
                $startingBalance += $item->amount;
            } else {
                $startingBalance -= $item->amount;
            }
        }

        return view('dashboard.dashboard', compact('balance', 'startingBalance', 'inflow', 'outflow', 'top_income', 'top_outcome'));
    }
}
