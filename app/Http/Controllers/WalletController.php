<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WalletFormRequest;
use App\Wallet;
use App\Repository\WalletEloquentRepository;

class WalletController extends Controller
{
    protected $walletEloquentRepository;

    public function __construct(WalletEloquentRepository $walletEloquentRepository)
    {
        $this->walletEloquentRepository = $walletEloquentRepository;
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
        $this->walletEloquentRepository->create($data);
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
        session(['wallet' => $id]);
        if($id != 'all') {
            $wallet = Wallet::findOrFail($id);
        } else {
            $wallet = new Wallet;
            $wallet->name = 'All';
        }        
        return view('wallet.show', compact('wallet'));
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
}
