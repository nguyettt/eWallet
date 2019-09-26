<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\WalletEloquentRepository;
use App\Repository\CategoryEloquentRepository;
use App\Repository\TransactionEloquentRepository;
use Illuminate\Support\Arr;

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
    )
    {
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

        $transaction = $this->transactionRepo->query()
                                            ->where('delete_flag', null)
                                            ->where('type', '<>', 3)
                                            ->whereMonth('created_at', $month)
                                            ->whereYear('created_at', $year)
                                            ->get();
        
        foreach ($transaction as $item) {
            if ($item->type == 1) {
                $inflow += $item->amount;
            } else {
                $outflow += $item->amount;
            }
        }
        
        switch ($month) {
            case 1: {
                $before = ($year-1).'-01-01 00:00:00';
                break;
            }
            default: {
                $before = $year.'-'.$month.'-01 00:00:00';
                break;
            }
        }
        
        $income = $transaction->where('type', 1);
        $income = $income->sortByDesc(function ($item) {
            return $item->amount;
        })->values();
        
        if (count($income) >= 5) {
            for ($i = 0; $i < 5; $i++) {
                $top_income[] = $income[$i];
            }
        } else $top_income = $income;

        $outcome = $transaction->where('type', 2);
        $outcome = $outcome->sortByDesc(function ($item) {
            return $item->amount;
        })->values();

        if (count($outcome) >= 5) {
            for ($i = 0; $i < 5; $i++) {
                $top_outcome[] = $outcome[$i];
            }
        } else $top_outcome = $outcome;

        $_transaction = $this->transactionRepo->query()
                                            ->where('created_at', '<', $before)
                                            ->where('delete_flag', null)
                                            ->where('type', '<>', 3)
                                            ->get();
        $startingBalance = 0;

        foreach ($_transaction as $item) {
            if ($item->type == 1) {
                $startingBalance += $item->amount;
            } else {
                $startingBalance -= $item->amount;
            }
        }
        
        return view('dashboard.dashboard', compact('balance', 'startingBalance', 'inflow', 'outflow', 'top_income', 'top_outcome'));
    }
}
