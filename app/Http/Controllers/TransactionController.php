<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransactionFormRequest;
use App\Http\Requests\EditTransactionFormRequest;
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

        return redirect('/wallet/'.$data['wallet_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trans = $this->transactionRepo->find($id);

        return view('transaction.show', compact('trans'));
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
        $wallet = $this->walletRepo->getAll();
        $trans = $this->transactionRepo->find($id);
        $type = $trans->category->type;
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

        $transaction = $this->transactionRepo->find($id);

        $wallet = $transaction->wallet;

        $type = $transaction->category->type;

        if ($transaction->benefit_wallet) {
            $benefit_wallet = $transaction->benefit_wallet_id;
        }

        if ($data['wallet_id'] != $transaction->wallet_id) {

            $new_wallet = $this->walletRepo->find($data['wallet_id']);
        
            switch ($type) {
                case 1: {
                    $wallet->balance -= $transaction->amount;
                    break;
                }
                case 2:
                case 3: {
                    $wallet->balance += $transaction->amount;
                    break;
                }
            }
            $wallet->save();

            switch($data['type']) {
                case 1: {
                    $new_wallet->balance += $data['amount'];
                    break;
                }
                case 2: 
                case 3: {
                    $new_wallet->balance -= $data['amount'];
                    break;
                }
            }
            $new_wallet->save();

            if (isset($data['benefit_wallet'])) {
                if ($transaction->benefit_wallet) {
                    $benefit_wallet = $transaction->benefit_wallet_id;
                    if ($benefit_wallet->id == $data['benefit_wallet']) {
                        $benefit_wallet->balance -= $transaction->amount;
                        $benefit_wallet->balance += $data['amount'];
                    } else {
                        $benefit_wallet->balance -= $transaction->amount;
                        $new_benefit_wallet = $this->walletRepo->find($data['benefit_wallet']);
                        $new_benefit_wallet->balance += $data['amount'];
                    }
                    $benefit_wallet->save();
                } else {
                    $new_benefit_wallet = $this->walletRepo->find($data['benefit_wallet']);
                    $new_benefit_wallet->balance += $data['amount'];
                }
                $new_benefit_wallet->save();
            }

        } else {

            switch ($type) {
                case 1: {
                    $wallet->balance -= $transaction->amount;
                    break;
                }
                case 2:
                case 3: {
                    $wallet->balance += $transaction->amount;
                    break;
                }
            }

            switch($data['type']) {
                case 1: {
                    $wallet->balance += $data['amount'];
                    $data['benefit_wallet'] = null;
                    break;
                }
                case 2: {
                    $wallet->balance -= $data['amount'];
                    $data['benefit_wallet'] = null;
                    break;
                }
                case 3: {
                    $wallet->balance -= $data['amount'];
                    break;
                }
            }

            $wallet->save();

            if (isset($data['benefit_wallet'])) {
                if ($transaction->benefit_wallet) {
                    $benefit_wallet = $transaction->benefit_wallet_id;
                    if ($benefit_wallet->id == $data['benefit_wallet']) {
                        $benefit_wallet->balance -= $transaction->amount;
                        $benefit_wallet->balance += $data['amount'];
                    } else {
                        $benefit_wallet->balance -= $transaction->amount;
                        $new_benefit_wallet = $this->walletRepo->find($data['benefit_wallet']);
                        $new_benefit_wallet->balance += $data['amount'];
                    }
                    $benefit_wallet->save();
                } else {
                    $new_benefit_wallet = $this->walletRepo->find($data['benefit_wallet']);
                    $new_benefit_wallet->balance += $data['amount'];
                }
                $new_benefit_wallet->save();
            }

        }

        $this->transactionRepo->update($id, $data);

        return redirect('/transaction/'.$id.'/edit');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = $this->transactionRepo->find($id);
        $wallet = $transaction->wallet;
        $type = $transaction->category->type;

        switch ($type) {
            case 1: {
                $wallet->balance = $wallet->balance - $transaction->amount;
                $wallet->save();
                break;
            }
            case 2: {
                $wallet->balance = $wallet->balance - $transaction->amount;
                $wallet->save();
                break;
            }
            case 3: {
                $benefit_wallet = $transaction->benefit_wallet_id;
                $wallet->balance += $transaction->amount;
                $benefit_wallet->balance = $benefit_wallet->balance - $transaction->amount;
                $wallet->save();
                $benefit_wallet->save();
                break;
            }
        }

        $transaction->delete_flag = 1;
        $transaction->save();

        return redirect('/wallet/'.$wallet->id);
    }
}
