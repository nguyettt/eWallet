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
    public function exportMonthlyReport(Request $request)
    {
        $month = date('m');
        $year = date('Y');
        $transaction = $this->transactionRepo->query()
                                            ->whereMonth('created_at', $month)
                                            ->whereYear('created_at', $year)
                                            ->get();
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

        return Excel::download(new TransactionExport($data), $month.'-'.$year.' report.xlsx');
    }

    /**
     * Received json POST from client and generate an excel file, save in 'storage/app/$user_id/'
     *
     * @param Request $request
     * @return void
     */
    public function ajax(Request $request)
    {
        $transaction = $request->data;

        $wallet = $this->walletRepo->getAll();

        $cat = $this->catRepo->getAll();

        foreach ($transaction as $key => $item) {
            $transaction[$key]['wallet'] = $wallet->where('id', $item['wallet_id'])->first()->name;
            $transaction[$key]['cat'] = $cat->where('id', $item['cat_id'])->first()->name;
            switch ($item['type']) {
                case config('variable.type.income'): {
                    $transaction[$key]['type'] = 'Income';
                    break;
                }
                case config('variable.type.outcome'): {
                    $transaction[$key]['type'] = 'Outcome';
                    break;
                }
                case config('variable.type.transfer'): {
                    $transaction[$key]['type'] = 'Transfer';
                    break;
                }
            }
        }

        $filename = 'transaction-'.date('Ymd').'-'.time().'.xlsx';

        Excel::store(new TransactionExport($transaction), $filename);

        return $path = '/export/download/'.auth()->user()->id.'/'.$filename;
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
        $url = explode('/', $url)[3];
        $path = base_path().'/storage/app/'.$url;

        return response()->download($path)->deleteFileAfterSend();
    }
}
 