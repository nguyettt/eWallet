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
        $wallet = $request->wallet;
        $cat = $request->cat;

        if (isset($request->time)) {
            $time = $request->time;
            $month = explode('-', $time)[0];
            $year = explode('-', $time)[1];
            $start = $year.'-'.$month.'-01 00:00:00';
            if ($month == 12) {
                $end = ($year + 1).'-01-01 00:00:00';
            } else {
                $end = $year.'-'.($month + 1).'-01 00:00:00';
            }
        } else {
            $start = $request->start;
            $end = $request->end;
        }

        $transaction = $this->transactionRepo->query()
                                            ->whereBetween('created_at', [$start, $end]);

        if ($wallet != 'all') {
            $transaction = $transaction->where('wallet_id', $wallet);
        }

        if ($cat != 'all') {
            $transaction = $transaction->where('cat_id', $cat);
        }

        $transaction = $transaction->get();
        $data = [];

        foreach ($transaction as $item) {
            $array = [
                'id' => $item->id,
                'wallet' => $item->wallet->name,
                'cat' => $item->category->name,
                'details' => $item->details,
                'amount' => $item->amount,
                'benefit_wallet' => ($item->benefit_wallet != null) ? $item->benefit_wallet_id->name : '',
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'delete_flag' => ($item->delete_flag == null) ? '' : 'Deleted'
            ];

            switch ($item->type) {
                case 1: {
                    $array['type'] = 'Income';
                    break;
                }
                case 2: {
                    $array['type'] = 'Outcome';
                    break;
                }
                case 3: {
                    $array['type'] = 'Transfer';
                    break;
                }
            }
            
            $data[] = $array;
        }

        return Excel::download(new TransactionExport($data), 'transaction.xlsx');
    }
}
