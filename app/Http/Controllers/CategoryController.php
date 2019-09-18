<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryFormRequest;
use App\Wallet;
use App\Category;
use App\Repository\CategoryEloquentRepository;

class CategoryController extends Controller
{

    protected $catEloqentRepository;

    public function __construct(CategoryEloquentRepository $catEloqentRepository)
    {
        $this->catEloqentRepository = $catEloqentRepository;
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
        $income_id = $cat->where('name', 'Income')->first()->id;
        $outcome_id = $cat->where('name', 'Expense')->first()->id;
        $transfer_id = $cat->where('name', 'Transfer to another wallet')->first()->id;
        $cat = $cat->whereIn('parent_id', [0, $income_id, $outcome_id, $transfer_id]);
        return view('cat.create', compact('wallet_id', 'cat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryFormRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $this->catEloqentRepository->create($data);
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
        echo $id;
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
