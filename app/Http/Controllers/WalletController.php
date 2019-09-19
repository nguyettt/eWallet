<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WalletFormRequest;
use App\Wallet;
use App\Repository\WalletEloquentRepository;
use App\Transaction;
use App\Repository\TransactionEloquentRepository;
use App\Repository\CategoryEloquentRepository;

class WalletController extends Controller
{
    protected $walletRepo;

    protected $transactionRepo;

    public function __construct(
        WalletEloquentRepository $walletRepo,
        TransactionEloquentRepository $transactionRepo,
        CategoryEloquentRepository $catRepo
    )
    {
        $this->walletRepo = $walletRepo;
        $this->transactionRepo = $transactionRepo;
        $this->catRepo = $catRepo;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('wallet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WalletFormRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['balance'] = 0;
        $this->walletRepo->create($data);
        return redirect('/home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {   
        $inflow = 0;
        $outflow = 0;

        if ($request->time) {
            $time = $request->time;
            if (!preg_match('/^[0-9]{1,2}\\-[0-9]{4}$/', $time)) {
                return abort(404);
            }
            $month = explode('-', $time)[0];
            $year = explode('-', $time)[1];
        } else {
            $month = date('m');
            $year = date('Y');
        }
        
        $time = $month.' - '.$year;

        $wallet = $this->walletRepo->find($id);

        $transaction = $this->transactionRepo->query()
                                            ->whereMonth('created_at', $month)
                                            ->whereYear('created_at', $year)
                                            ->whereRaw("(wallet_id = {$id} or benefit_wallet = {$id})")
                                            ->orderBy('created_at', 'asc')
                                            ->get();

        $cat_id_array = $this->cat_array();

        $records = array();

        foreach ($transaction as $item) {
            if (in_array($item->cat_id, $cat_id_array['income']) || $item->benefit_wallet == $id) {
                $inflow += $item->amount;
            } else {
                $outflow += $item->amount;
            }

            $date = date('m/d/Y', strtotime($item['created_at']));
            $records[$date][] = $item;
        }

        foreach ($records as $date => $item) {
            $sum = 0;
            foreach ($item as $key => $value) {
                if (in_array($value->cat_id, $cat_id_array['income']) || $value->benefit_wallet == $id) {
                    $sum += $value->amount;
                    $records[$date][$key]['type'] = 1;
                    if ($value->benefit_wallet == $id) {
                        $from_wallet = $this->walletRepo->find($value->wallet_id)->name;
                        $records[$date][$key]['details'] = 'Transfer from '.$from_wallet;
                    }
                } else {
                    $sum = $sum - $value->amount;
                    $records[$date][$key]['type'] = 2;
                }
                $records[$date][$key]['cat_name'] = $value->category->name;
                $records[$date][$key] = $value->toArray();
            }
            $records[$date]['sum'] = $sum;
        }

        if ($month == 1) {
            $next = '02-'.$year;
            $prev = '12-'.($year - 1);
        } elseif ($month > 1 && $month < 12) {
            $next = str_pad(($month + 1), 2, '0', STR_PAD_LEFT).'-'.$year;
            $prev = str_pad(($month - 1), 2, '0', STR_PAD_LEFT).'-'.$year;
        } else {
            $next = '01-'.($year + 1);
            $prev = '11-'.$year;
        }

        return view('wallet.show', compact('wallet', 'transaction', 'inflow', 'outflow', 'records', 'time', 'next', 'prev'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Generate cat_type_id array
     * @return array
     */
    public function cat_array()
    {
        $cat = $this->catRepo->getAll();
        $array = array();
        $income = array();
        $outcome = array();

        foreach ($cat as $item) {
            switch ($item['type']) {
                case 1: {
                    $income[] = $item['id'];
                    break;
                }
                case 2: 
                case 3: {
                    $outcome[] = $item['id'];
                    break;
                }
            }
        }

        $array['income'] = $income;
        $array['outcome'] = $outcome;
        return $array;
    }

    public function buildListTransaction($id)
    {
        // $transaction = $this->transactionRepo->
    }
}
