<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Repository\WalletEloquentRepository;
use App\Repository\CategoryEloquentRepository;
use App\Repository\TransactionEloquentRepository;

class ExportController extends Controller
{
    protected $walletRepo;
    protected $catRepo;
    protected $transactionRepo;

    public function __construct(
        TransactionEloquentRepository $transactionRepo,
        WalletEloquentRepository $walletRepo,
        CategoryEloquentRepository $catRepo
    )
    {
        $this->transactionRepo = $transactionRepo;
        $this->catRepo = $catRepo;
        $this->walletRepo = $walletRepo;
    }

    public function export(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $wallet = $request->wallet;
        $cat = $request->cat;
        // return Excel::download(new TransactionExport, '');
    }

    public function test()
    {
        $transaction = $this->transactionRepo->getAll();
        foreach ($transaction as $item) {
            $data['id'] = $item->id;
            $data['wallet'] = $item->wallet->name;
            $data['cat'] = $item->category->name;
            switch ($item->type) {
                case 1: {
                    $data['type'] = 'Income';
                    break;
                }
                case 2: {
                    $data['type'] = 'Outcome';
                    break;
                }
                case 3: {
                    $data['type'] = 'Transfer';
                    break;
                }
            }
            $data['details'] = $item->details;
            $data['amount'] = $item->amount;
            $data['benefit_wallet_name'] = isset($item->benefit_wallet_id) ? $item->benefit_wallet_id->name : '';
            $data['created_at'] = $item->created_at;
            $data['updated_at'] = $item->updated_at;
            $data['delete_flag'] = ($item->delete_flag == null) ? '' : 'Deleted';
            $array[] = $data;
        }
        return Excel::download(new TransactionExport($array), 'transaction.csv');
    }
}
