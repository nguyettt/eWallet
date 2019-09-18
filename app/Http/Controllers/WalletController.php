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
        } else {
            $time = date('m');
        }

        if ($id == 'all') {
            $wallet['name'] = 'All';
            $transaction = $this->transactionRepo->query()
                                                 ->whereMonth('created_at', $time)
                                                 ->where('benefit_wallet', null)
                                                 ->get();
        } else {
            $wallet = $this->walletRepo->find($id);
            $transaction = $wallet->transaction()
                                  ->whereMonth('created_at', $time)
                                  ->get();
            $transfer_in = $this->transactionRepo->query()
                                                 ->whereMonth('created_at', $time)
                                                 ->where('benefit_wallet', $id)
                                                 ->get();

            foreach ($transfer_in as $item) {
                $inflow += $item['amount'];
            }
        }

        $cat_array = $this->cat_array();

        foreach ($transaction as $item) {
            if (in_array($item['cat_id'], $cat_array['income'])) {
                $inflow += $item['amount'];
            } else {
                $outflow += $item['amount'];
            }
        }

        return view('wallet.show', compact('wallet', 'transaction', 'inflow', 'outflow'));
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
