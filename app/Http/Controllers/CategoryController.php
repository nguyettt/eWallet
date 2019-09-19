<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryFormRequest;
use App\Wallet;
use App\Category;
use App\Repository\CategoryEloquentRepository;

class CategoryController extends Controller
{

    protected $catRepo;

    public function __construct(CategoryEloquentRepository $catRepo)
    {
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

        // print_r($cat->all());
        return view('cat.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $cat = Category::where('user_id', auth()->user()->id)->get();
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
        $this->catRepo->create($data);
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
        $data['delete_flag'] = 1;

        $this->catRepo->update($id, $data);

        return redirect('/cat');
    }
}
