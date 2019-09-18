<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail; 
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Category;
use App\Transaction;
use App\Repository\WalletEloquentRepository;
use App\Repository\CategoryEloquentRepository;
use App\Repository\UserEloquentRepository;
use App\Repository\TransactionEloquentRepository;
use App\Http\Controllers\WalletController;

class TestController extends Controller
{
    protected $walletRepo;

    protected $catRepo;

    protected $transactionRepo;

    public function __construct(
        TransactionEloquentRepository $transactionRepo,
        CategoryEloquentRepository $catRepo,
        WalletEloquentRepository $walletRepo
    )
    {
        $this->walletRepo = $walletRepo;
        $this->catRepo = $catRepo;
        $this->transactionRepo = $transactionRepo;
    }

    public function test(Request $request)
    {
        // $wallet = $this->walletRepo->findWithRelationship(1, ['transaction']);
        // $wallet = $this->walletRepo->find(1);
        // $trans = $wallet->transaction()->get();
        // foreach ($trans as $item) {
        //     echo $item['amount'].'<br>';
        // }

        // $trans = $this->transactionRepo->getAll();
        // $in = $trans->where('benefit_wallet', '<>', null)->all();
        // // echo count($in);
        // foreach ($in as $item) {
        //     // echo $in['amount'];
        //     // print_r($item);
        //     echo $item['amount'];
        // }

        $wallet = $this->walletRepo->find(2);
        $transaction = $wallet->transaction()
                              ->whereMonth('created_at', 9)
                              ->get();
        $in = $transaction->where('benefit_wallet', '<>', null)->get();
        print_r($in);
        // foreach ($transaction as $item) {
        //     echo $item['amount'].'<br>';
        // }
    }
}
