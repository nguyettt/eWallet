<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Repository\WalletEloquentRepository;
use App\Repository\CategoryEloquentRepository;
use App\Repository\TransactionEloquentRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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

    /**
     * Directly export to excel and send a response to download it
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\Response
     */
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
                case config('variable.type.income'): {
                    $array['type'] = 'Income';
                    break;
                }
                case config('variable.type.outcome'): {
                    $array['type'] = 'Outcome';
                    break;
                }
                case config('variable.type.transfer'): {
                    $array['type'] = 'Transfer';
                    break;
                }
            }
            
            $data[] = $array;
        }

        return Excel::download(new TransactionExport($data), 'transaction.xlsx');
    }

    /**
     * Received json POST from client and generate an excel file, save in 'storage/app/$user_id/'
     *
     * @param Request $request
     * @return void
     */
    public function exportJSON(Request $request)
    {
        $transaction = $request->data;

        $wallet = $this->walletRepo->getAll();

        $cat = $this->catRepo->getAll();

        foreach ($transaction as $key => $item) {
            $transaction[$key]['wallet'] = $wallet->where('id', $item['wallet_id'])->first()->name;
            $transaction[$key]['cat'] = $cat->where('id', $item['cat_id'])->first()->name;
        }

        $filename = 'transaction-'.date('Ymd').'-'.time().'.xlsx';

        Excel::store(new TransactionExport($transaction), $filename);

        return $path = '/export/'.auth()->user()->id.'/'.$filename;
    }

    /**
     * Download the generated excel file
     *
     * @param Request $request
     * @param int $id
     * @param string $name
     * 
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $id, $name)
    {
        if (auth()->user()->id != $id) {
            abort(404);
        }

        $url = $request->path();
        $url = explode('/', $url)[2];
        $path = base_path().'/storage/app/'.$url;

        return response()->download($path)->deleteFileAfterSend();
    }
}
 