<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WalletFormRequest;
use App\Http\Requests\WalletEditFormRequest;
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
        $deleted = $this->walletRepo->deleted();

        if ($deleted->count() > 0) {
            foreach ($deleted as $item) {
                if ($item->name == $request->name) {
                    $restore = $item->id;
                    break;
                }
            }
        }

        if (isset($restore)) {
            return back()->withErrors(['restore' => 'Can restore'])
                        ->withInput()
                        ->with('restore_id', $restore);
        } else {
            $data = $request->all();
            $data['user_id'] = auth()->user()->id;
            $data['balance'] = 0;
            $this->walletRepo->create($data);
            return redirect('/dashboard');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {   
        $flow['in'] = 0;
        $flow['out'] = 0;

        if ($request->time) {
            $time = $request->time;
            if (!preg_match('/^([0-9]{1,2}\\-)?[0-9]{1,2}\\-[0-9]{4}$/', $time)) {
                return abort(404);
            }
            $time = explode('-', $time);
            if (count($time) == 2) {
                $month = $time[0];
                $year = $time[1];
            } else {
                $day = $time[0];
                $month = $time[1];
                $year = $time[2];
            }
        } else {
            $month = date('m');
            $year = date('Y');
        }
        if ($id == 'undefined') {
            $deleted = $this->walletRepo->deleted()->pluck('id');
        } else {
            $wallet = $this->walletRepo->find($id);
        }
        
        if (!isset($day)) {

            $time = str_pad($month, 2, '0', STR_PAD_LEFT).' - '.$year;
            
            $transaction = $this->transactionRepo->query()
                                                ->whereMonth('created_at', $month)
                                                ->whereYear('created_at', $year)
                                                ->where('delete_flag', null);

            if (isset($wallet)) {
                $transaction = $transaction->whereRaw("(wallet_id = {$id} or benefit_wallet = {$id})")
                                            ->get();
            } else {
                $transaction = $transaction->whereIn('wallet_id', $deleted)
                                            ->get();
            }
    
            $records = array();
    
            $date_arr = [];
    
            foreach ($transaction as $item) {
                $date = date('m/d/Y', strtotime($item->created_at));
                if (!in_array($date, $date_arr)) {
                    $date_arr[] = $date;
                    $records[$date]['sum'] = 0;
                }
                
                if ($item->type == 1 || $item->benefit_wallet == $id) {
                    $flow['in'] += $item->amount;
                    $records[$date]['sum'] += $item->amount;
                } else {
                    $flow['out'] += $item->amount;
                    $records[$date]['sum'] -= $item->amount;
                }
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

            return view('wallet.show', compact('wallet', 'flow', 'records', 'time', 'next', 'prev', 'id'));

        } else {

            $date = str_pad($day, 2, '0', STR_PAD_LEFT).' - '.str_pad($month, 2, '0', STR_PAD_LEFT).' - '.$year;

            $transaction = $this->transactionRepo->query()
                                                ->whereDate('created_at', $year.'-'.$month.'-'.$day)
                                                ->where('delete_flag', null);

            if (isset($wallet)) {
                $transaction = $transaction->whereRaw("(wallet_id = {$id} or benefit_wallet = {$id})")
                                            ->get();
            } else {
                $transaction = $transaction->whereIn('wallet_id', $deleted)
                                            ->get();
            }

            foreach ($transaction as $item) {
                if ($item->type == 1 || $item->benefit_wallet == $id) {
                    $flow['in'] += $item->amount;
                } else {
                    $flow['out'] -= $item->amount;
                }
            }

            return view('wallet.day', compact('transaction', 'flow', 'wallet', 'date', 'id'));

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $wallet = $this->walletRepo->find($id);
        return view('wallet.edit', compact('wallet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WalletEditFormRequest $request, $id)
    {
        $deleted = $this->walletRepo->deleted();

        if ($deleted->count() > 0) {
            foreach ($deleted as $item) {
                if ($item->name == $request->name) {
                    return back()->withErrors(['existed' => 'Wallet exists'])
                                ->withInput();
                }
            }
        }

        $data = $request->all();
        $this->walletRepo->update($id, $data);
        return redirect('/wallet/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wallet = $this->walletRepo->find($id);

        if ($wallet->name == 'Main Wallet') {
            return back()->withErrors(['main_wallet' => 'Can not delete Main Wallet']);
        }

        if ($wallet->transaction()->count() == 0 && $wallet->transferTransaction()->count() == 0) {
            $wallet->delete();
        } else {
            $data['delete_flag'] = 1;
            $data['balance'] = 0;

            $this->walletRepo->update($id, $data);

            if ($wallet->balance > 0) {
                $main_wallet = $this->walletRepo->query()
                                                ->where('name', 'Main Wallet')
                                                ->first();
                $main_wallet->balance += $wallet->balance;
                $main_wallet->save();

                $transaction['user_id'] = auth()->user()->id;
                $transaction['wallet_id'] = $id;
                $transaction['cat_id'] = $this->catRepo->query()->where('type', 3)->first()->id;
                $transaction['type'] = 3;

                $transaction = [
                    'user_id' => auth()->user()->id,
                    'wallet_id' => $id,
                    'cat_id' => $this->catRepo->query()->where('type', 3)->first()->id,
                    'details' => 'Transfer from '.$wallet->name.' to Main Wallet',
                    'type' => 3,
                    'amount' => $wallet->balance,
                    'benefit_wallet' => $main_wallet->id
                ];

                $this->transactionRepo->create($transaction);
            }
        }
        
        return redirect('/dashboard');
    }

    /**
     * Restore the wallet with id = $id
     *
     * @param int $id
     * @return void
     */
    public function restore($id)
    {
        $this->walletRepo->restore($id);

        return redirect('/dashboard');
    }

    public function getBalance(Request $request)
    {
        $wallet = $this->walletRepo->find($request->id);
        return response()->json(number_format($wallet->balance));
    }
}
