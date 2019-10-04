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
                            ->where('benefit_wallet', null)
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->get();

        // Calculate inflow, outflow
        $flow = $this->flowCalculate($month, $year, $transaction);
        $inflow = $flow['in'];
        $outflow = $flow['out'];

        // Select top transaction
        $top_income = $this->topTransaction($month, $year, $transaction, config('variable.type.income'));
        $top_outcome = $this->topTransaction($month, $year, $transaction, config('variable.type.outcome'));

        $startingBalance = $this->startingBalance($month, $year);
        return view('dashboard.dashboard', compact('balance', 'startingBalance', 'inflow', 'outflow', 'top_income', 'top_outcome'));
    }

    /**
     * Calculate inflow, outflow
     * 
     * @param int $month
     * @param int $year
     * @param Collection $transaction
     * 
     * @return array $flow
     */
    public function flowCalculate($month, $year, $transaction)
    {
        $flow['in'] = $transaction->where('type', config('variable.type.income'))
                            ->sum('amount');
        $flow['out'] = $transaction->where('type', config('variable.type.outcome'))
                            ->sum('amount');
        return $flow;
    }

    /**
     * Return top transaction of the month
     * 
     * @param int $month
     * @param int $year
     * @param Collection $transaction
     * @param int type
     * 
     * @return array $result
     */
    public function topTransaction($month, $year, $transaction, $type)
    {
        $filtered = $transaction->where('type', $type);
        $filtered = $filtered->sortByDesc(function ($item) {
            return $item->amount;
        })->values();
        if (count($filtered) >= 5) {
            for ($i = 0; $i < 5; $i++) {
                $result[] = $filtered[$i];
            }
        } else {
            $result = $filtered;
        }
        return $result;
    }

    /**
     * Calculate starting balance
     * 
     * @param int $month
     * @param int $year
     * 
     * @return int $startingBalance
     */
    public function startingBalance($month, $year)
    {
        $before = $year . '-' . $month . '-01 00:00:00';
        $transaction = $this->transactionRepo->query()
            ->where('created_at', '<', $before)
            ->where('delete_flag', null)
            ->where('type', '<>', config('variable.type.transfer'))
            ->get();
        $startingBalance = 0;

        foreach ($transaction as $item) {
            if ($item->type == config('variable.type.income')) {
                $startingBalance += $item->amount;
            } else {
                $startingBalance -= $item->amount;
            }
        }

        return $startingBalance;
    }
}
