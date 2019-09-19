<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransactionFormRequest;
use App\Transaction;
use App\Repository\TransactionEloquentRepository;
use App\Repository\WalletEloquentRepository;
use App\Wallet;
use App\Category;

class TransactionController extends Controller
{
    protected $transactionRepo;

    protected $walletRepo;

    public function __construct(
        TransactionEloquentRepository $transactionRepo, 
        WalletEloquentRepository $walletRepo
    )
    {
        $this->transactionRepo = $transactionRepo;
        $this->walletRepo = $walletRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cat = Category::where('user_id', auth()->user()->id)->get();
        $wallet = $this->walletRepo->getAll();
        return view('transaction.create', compact('cat', 'wallet'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TransactionFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionFormRequest $request)
    {    
        $data = $request->all();

        $wallet = $this->walletRepo->find($data['wallet_id'])
                                   ->toArray();

        switch ($data['type']) {
            case 1: {
                $wallet['balance'] += $data['amount'];
                unset($data['benefit_wallet']);
                break;
            }
            case 2: {
                $wallet['balance'] = $wallet['balance'] - $data['amount'];
                unset($data['benefit_wallet']);
                break;
            }
            case 3: {
                $benefit_wallet = $this->walletRepo->find($data['benefit_wallet'])
                                                   ->toArray();
                $wallet['balance'] = $wallet['balance'] - $data['amount'];
                $benefit_wallet['balance'] += $data['amount'];
                $this->walletRepo->update($benefit_wallet['id'], $benefit_wallet);
                break;
            }
        }

        $this->walletRepo->update($wallet['id'], $wallet);
        
        $data['user_id'] = auth()->user()->id;
        $this->transactionRepo->create($data);

        return redirect('/home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cat = Category::where('user_id', auth()->user()->id)->get();
        $wallet = Wallet::where('user_id', auth()->user()->id)->get();
        $trans = $this->transactionRepo->find($id);
        $type = $cat->find($trans->cat_id)->type;
        return view('transaction.edit', compact('cat', 'wallet', 'trans', 'type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionFormRequest $request, $id)
    {
        $data = $request->all();
        // $this->transactionRepo->update($data['id'], $data);

        $trans = $this->transactionRepo->find($id)->toArray();
        // if ($trans['wallet_id'] != $data['wallet_id']) {
        //     $old_wallet = $this->walletRepo->find($trans['wallet_id'])->toArray();
        //     $new_wallet = $this->walletRepo->find($data['wallet_id'])->toArray();
        //     // if ($trans['type'] == 'income') 
        // }
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
}
