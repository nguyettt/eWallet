<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\WalletEloquentRepository;
use App\Repository\CategoryEloquentRepository;
use App\Repository\TransactionEloquentRepository;

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
        $wallets = $this->walletRepo->query()
                                    ->where('delete_flag', null)
                                    ->get();

        $balance = $wallets->sum('balance');
        $inflow = 0;
        $outflow = 0;
        
        foreach ($wallets as $item) {
            $transaction[] = $item->transaction()
                                ->where('delete_flag', null)
                                ->where('type', '<>', 3)
                                ->whereMonth('created_at', date('m'))
                                ->whereYear('created_at', date('Y'))
                                ->get()
                                ->toArray();
        }

        foreach ($transaction as $item) {
            foreach ($item as $i) {
                if ($i['type'] == 1) {
                    $inflow += $i['amount'];
                } else {
                    $outflow += $i['amount'];
                }
            }
        }

        $_transaction = $this->transactionRepo->query()
                                            ->where('created_at', '<', date('Y').'-'.date('m').'-1 00:00:00')
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
        // dump($startingBalance);
        return view('dashboard.dashboard', compact('balance', 'startingBalance', 'inflow', 'outflow'));
    }
}
