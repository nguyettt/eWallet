<?php

namespace App\Exports;

use App\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class TransactionExport implements FromView
{
    protected $transaction;

    public function __construct(array $transaction)
    {
        $this->transaction = $transaction;
    }

    public function view(): View
    {
        return view('export.transaction', ['transaction' => $this->transaction]);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::all();
    }
}
