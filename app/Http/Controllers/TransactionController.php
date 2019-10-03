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
        $data = [
            'income' => config('variable.type.income'),
            'outcome' => config('variable.type.outcome'),
            'transfer' => config('variable.type.transfer'),
        ];
        $data = json_encode($data);

        return view('transaction.create', compact('cat', 'wallet', 'data'));
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
        $id = $data['wallet_id'];
        $type = $data['type'];
        $amount = $data['amount'];

        $this->updateWallet($id, $type, $amount);

        if ($type == config('variable.type.transfer')) {
            $benefit_id = $data['benefit_wallet'];
            $this->updateWallet($benefit_id, config('variable.type.income'), $amount);
        } else {
            unset($data['benefit_wallet']);
        }
        
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
        // $url = preg_replace('/(\/[0-9]+)/', '', url()->current());

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
        $url = str_replace('/edit', '', url()->current());
        $data = [
            'income' => config('variable.type.income'),
            'outcome' => config('variable.type.outcome'),
            'transfer' => config('variable.type.transfer'),
        ];
        $data = json_encode($data);
        return view('transaction.edit', compact('cat', 'wallet', 'trans', 'type', 'url', 'data'));
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
        // Roll back old transaction
        $transaction = $this->transactionRepo->find($id);
        $old_wallet = $transaction->wallet_id;
        $old_type = $transaction->category->type;
        $old_amount = $transaction->amount;

        $this->transactionRollback($old_wallet, $old_type, $old_amount);

        if ($old_type == config('variable.type.transfer')) {
            $old_benefit = $transaction->benefit_wallet;
            $this->transactionRollback($old_benefit, config('variable.type.income'), $old_amount);
        }

        // Create new transaction
        $data = $request->all();
        $new_wallet = $data['wallet_id'];
        $new_type = $data['type'];
        $new_amount = $data['amount'];

        $this->updateWallet($new_wallet, $new_type, $new_amount);

        if ($new_type == config('variable.type.transfer')) {
            $new_benefit = $data['benefit_wallet'];
            $this->updateWallet($new_benefit, config('variable.type.income'), $new_amount);
        } else {
            $data['benefit_wallet'] = null;
        }

        $this->transactionRepo->update($id, $data);

        return redirect('/wallet/'.$new_wallet);

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
        $wallet = $transaction->wallet_id;
        $type = $transaction->type;
        $amount = $transaction->amount;

        $this->transactionRollback($wallet, $type, $amount);

        if ($type == config('variable.type.transfer')) {
            $benefit = $transaction->benefit_wallet;
            $this->transactionRollback($benefit, config('variable.type.income'), $amount);
        }

        $transaction->delete_flag = 1;
        $transaction->save();

        return redirect('/wallet/'.$wallet);
    }

    public function search (Request $request)
    {
        $wallet = $request->wallet;
        $cat = $request->cat;
        $include = $request->include;
        $start = ($request->start < $request->end) ? $request->start : $request->end;
        $end = ($start == $request->start) ? $request->end : $request->start;
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end.' 24:00:00'));
        $transaction = $this->transactionRepo->query()
                                    ->where('delete_flag', null)
                                    ->where('created_at', '>', $start)
                                    ->where('created_at', '<', $end);

        if ($wallet != 'all') {
            $transaction = $transaction->where('wallet_id', $wallet);
            $_transaction = $this->transactionRepo->query()
                                    ->where('delete_flag', null)
                                    ->where('created_at', '>', $start)
                                    ->where('created_at', '<', $end)
                                    ->where('benefit_wallet', $wallet);
        }

        $cat_transfer = $this->catRepo->query()
                                    ->where('type', config('variable.type.transfer'))
                                    ->first()
                                    ->id;

        if ($cat != 'all' && $cat != $cat_transfer) {
            if ($include != null) {
                $cat_list[] = $cat;
                $child = $this->catRepo->findChild($cat);
                if ($child) {
                    $cat_list = array_merge($cat_list, $child);
                }
                $transaction = $transaction->whereIn('cat_id', $cat_list);
            } else {
                $transaction = $transaction->where('cat_id', $cat);
            }            
            $transaction = $transaction->get();
        } else {
            if ($cat == $cat_transfer) {
                $transaction = $transaction->where('cat_id', $cat_transfer);
            }
            if (isset($_transaction)) {
                $transaction = $transaction->union($_transaction);
            }
            $transaction = $transaction->get();
        }
        
        $transaction = $transaction->sortBy(function ($item) {
            return $item->created_at;
        });
        
        return response()->json($transaction);
    }

    /**
     * Update wallet's balance after a successful transaction
     * 
     * @param int $id
     * @param int $type
     * @param int $amount
     * 
     * @return void
     */
    public function updateWallet($id, $type, $amount)
    {
        $wallet = $this->walletRepo->find($id);
        switch ($type) {
            case config('variable.type.income'): {
                $wallet->balance += $amount;
                break;
            }
            case config('variable.type.outcome'):
            case config('variable.type.transfer'): {
                $wallet->balance -= $amount;
                break;
            }
        }
        $wallet->save();
    }

    /**
     * Rollback transaction on wallet
     * 
     * @param int $id
     * @param int $type
     * @param int $amount
     * 
     * @return void
     */
    public function transactionRollback($id, $type, $amount)
    {
        $wallet = $this->walletRepo->find($id);
        switch ($type) {
            case config('variable.type.income'): {
                $wallet->balance -= $amount;
                break;
            }
            case config('variable.type.outcome'):
            case config('variable.type.transfer'): {
                $wallet->balance += $amount;
                break;
            }
        }
        $wallet->save();
    }
}
