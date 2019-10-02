<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TransactionFormRequest;
use App\Http\Requests\EditTransactionFormRequest;
use App\Transaction;
use App\Repository\TransactionEloquentRepository;
use App\Repository\WalletEloquentRepository;
use App\Repository\CategoryEloquentRepository;
use App\Wallet;
use App\Category;

class TransactionController extends Controller
{
    protected $transactionRepo;

    protected $walletRepo;

    public function __construct(
        TransactionEloquentRepository $transactionRepo, 
        WalletEloquentRepository $walletRepo,
        CategoryEloquentRepository $catRepo
    )
    {
        $this->transactionRepo = $transactionRepo;
        $this->walletRepo = $walletRepo;
        $this->catRepo = $catRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
        $cat = $this->catRepo->query()
                                ->where('delete_flag', null)
                                ->get();

        $wallet = $this->walletRepo->query()
                                ->where('delete_flag', null)
                                ->get();
        
        return view('transaction.index', compact('cat', 'wallet'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cat = Category::where('user_id', auth()->user()->id)->get();
        $wallet = $this->walletRepo->query()
                                    ->where('delete_flag', null)
                                    ->get();
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
            case config('variable.type.income'): {
                $wallet['balance'] += $data['amount'];
                unset($data['benefit_wallet']);
                break;
            }
            case config('variable.type.outcome'): {
                $wallet['balance'] = $wallet['balance'] - $data['amount'];
                unset($data['benefit_wallet']);
                break;
            }
            case config('variable.type.transfer'): {
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
        $cat = $this->catRepo->query()
                            ->where('delete_flag', null)
                            ->get();
        $wallet = $this->walletRepo->query()
                                    ->where('delete_flag', null)
                                    ->get();
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
                case config('variable.type.income'): {
                    $wallet->balance -= $transaction->amount;
                    break;
                }
                case config('variable.type.outcome'):
                case config('variable.type.transfer'): {
                    $wallet->balance += $transaction->amount;
                    break;
                }
            }
            $wallet->save();

            switch($data['type']) {
                case config('variable.type.income'): {
                    $new_wallet->balance += $data['amount'];
                    $data['benefit_wallet'] = null;
                    break;
                }
                case config('variable.type.outcome'): {
                    $new_wallet->balance -= $data['amount'];
                    $data['benefit_wallet'] = null;
                    break;
                }
                case config('variable.type.transfer'): {
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
                        $benefit_wallet->save();
                    } else {
                        $benefit_wallet->balance -= $transaction->amount;
                        $new_benefit_wallet = $this->walletRepo->find($data['benefit_wallet']);
                        $new_benefit_wallet->balance += $data['amount'];
                        $new_benefit_wallet->save();
                    }
                    $benefit_wallet->save();
                } else {
                    $new_benefit_wallet = $this->walletRepo->find($data['benefit_wallet']);
                    $new_benefit_wallet->balance += $data['amount'];
                    $new_benefit_wallet->save();
                }
            }

        } else {

            switch ($type) {
                case config('variable.type.income'): {
                    $wallet->balance -= $transaction->amount;
                    break;
                }
                case config('variable.type.outcome'):
                case config('variable.type.transfer'): {
                    $wallet->balance += $transaction->amount;
                    break;
                }
            }

            switch($data['type']) {
                case config('variable.type.income'): {
                    $wallet->balance += $data['amount'];
                    $data['benefit_wallet'] = null;
                    break;
                }
                case config('variable.type.outcome'): {
                    $wallet->balance -= $data['amount'];
                    $data['benefit_wallet'] = null;
                    break;
                }
                case config('variable.type.transfer'): {
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
                        $benefit_wallet->save();
                    } else {
                        $benefit_wallet->balance -= $transaction->amount;
                        $new_benefit_wallet = $this->walletRepo->find($data['benefit_wallet']);
                        $new_benefit_wallet->balance += $data['amount'];
                        $new_benefit_wallet->save();
                    }
                    $benefit_wallet->save();
                } else {
                    $new_benefit_wallet = $this->walletRepo->find($data['benefit_wallet']);
                    $new_benefit_wallet->balance += $data['amount'];
                    $new_benefit_wallet->save();
                }
            }

        }

        $this->transactionRepo->update($id, $data);

        return redirect('/transaction/'.$id);

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
            case config('variable.type.income'): {
                $wallet->balance = $wallet->balance - $transaction->amount;
                break;
            }
            case config('variable.type.outcome'): {
                $wallet->balance = $wallet->balance - $transaction->amount;
                break;
            }
            case config('variable.type.transfer'): {
                $benefit_wallet = $transaction->benefit_wallet_id;
                $wallet->balance += $transaction->amount;
                $benefit_wallet->balance = $benefit_wallet->balance - $transaction->amount;
                $benefit_wallet->save();
                break;
            }
        }
        
        $wallet->save();

        $transaction->delete_flag = 1;
        $transaction->save();

        return redirect('/wallet/'.$wallet->id);
    }

    public function search (Request $request)
    {
        $wallet = $request->wallet;
        $cat = $request->cat;
        if ($request->start > $request->end) {
            $start = $request->end;
            $end = $request->start;
        } else {
            $start = $request->start;
            $end = $request->end;
        }
        $transaction = $this->transactionRepo->query()
                                            ->where('delete_flag', null)
                                            ->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)))
                                            ->where('created_at', '<', date('Y-m-d H:i:s', strtotime($end.' 24:00:00')));

        $_transaction = $this->transactionRepo->query()
                                            ->where('delete_flag', null)
                                            ->where('created_at', '>', date('Y-m-d H:i:s', strtotime($start)))
                                            ->where('created_at', '<', date('Y-m-d H:i:s', strtotime($end.' 24:00:00')));

        if ($wallet != 'all') {
            $transaction = $transaction->where('wallet_id', $wallet);
            $_transaction = $_transaction->where('benefit_wallet', $wallet);
        }

        $transfer = $this->catRepo->query()
                                ->where('type', config('variable.type.transfer'))
                                ->first()
                                ->id;
        if ($cat != 'all') {
            $cat_list[] = $cat;
            if ($this->catRepo->findChild($cat)) {
                $cat_list = array_merge($cat_list, $this->catRepo->findChild($cat));
            }
            $transaction = $transaction->whereIn('cat_id', $cat_list);
        }

        if ($cat == 'all' || $cat == $transfer) {
            $_transaction = $_transaction->where('benefit_wallet', '<>', null);
            $transaction = $transaction->union($_transaction)->get();
        } else {
            $transaction = $transaction->get();
        }

        $transaction = $transaction->sortBy(function ($item) {
            return $item->created_at;
        });
        
        return response()->json($transaction);
    }
}
