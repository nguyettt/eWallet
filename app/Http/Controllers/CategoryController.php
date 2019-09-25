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

        $tree = $this->buildTree($cat->toArray(), 0);

        $menu = $this->buildMenu($tree, '#');

        return view('cat.index', compact('menu'));
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
        return redirect('/cat');
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

        $cat = $this->catRepo->query()
                            ->where('delete_flag', null)
                            ->where('parent_id', $id)
                            ->get();
        
        foreach ($cat as $item) {

            $this->destroy($item->id);

        }

        return redirect('/cat');
    }

    /**
     * Build category tree from database
     *
     * @param array $array
     * @param integer $parent_id
     * @return branch
     */
    public function buildTree($array, $parent_id = 0)
    { 
        $branch = array();

        foreach ($array as $item) {
            if ($item['parent_id'] == $parent_id) {
                $child = $this->buildTree($array, $item['id']);
                if ($child) {
                    $item['child'] = $child;
                }
                $branch[] = $item;
            }
        }
        
        return $branch;
    }

    public function buildMenu($array, $parent)
    {
        if ($parent != '#') {
            $menu = "<div class='collapse' id='{$parent}'>\n<ul class='menu'>\n";
        } else {
            $menu = "<div>\n<ul class='menu'>\n";
        }
        

        foreach ($array as $item) {
            switch ($item['type']) {
                case 1: $color = 'text-success';
                        break;
                case 2: $color = 'text-danger';
                        break;
                case 3: $color = 'text-info';
                        break;
            }
            $csrf = csrf_token();
            if (isset($item['child'])) {
                $menu .= "<li>\n
                            <div class='d-flex'>\n
                                <a href='#' class='{$color} h5 mb-1 mt-1' data-toggle='collapse' data-target='#{$item['name']}'>
                                    <i class='fas fa-circle mr-3 {$color } fa-xs'></i>\n
                                    ".$item['name']."\n
                                </a>\n
                                <form id='frmCatDel_{$item['id']}' method='POST' action='cat/{$item['id']}' style='display:none'>\n
                                    <input type='hidden' name='_token' value='{$csrf}'>\n
                                    <input type='hidden' name='_method' value='DELETE'>\n
                                    <input type='hidden' id='cat_{$item['id']}' value='{$item['name']}'>\n
                                </form>\n
                                <a href='cat/{$item['id']}/edit'>\n
                                    <i class='fas fa-edit ml-5 text-primary fa-fw align-bottom' style='cursor:pointer'></i>\n
                                </a>\n
                                <span style='cursor:pointer' onclick='delCat({$item['id']})'>\n
                                    <i class='fas fa-trash-alt ml-3 text-danger fa-fw align-bottom' style='cursor:pointer'></i>\n
                                </span>\n
                                <i class='fas fa-sort-down ml-3 {$color} fa-lg' data-toggle='collapse' data-target='#{$item['name']}' style='cursor:pointer'></i>\n
                            </div>";
                $sub = $this->buildMenu($item['child'], $item['name']);
                $menu .= $sub."</li>\n";
            } else {
                $menu .= "<li class='mt-1 mb-1'>\n
                            <div class='d-flex'>\n
                                <span class='{$color} h5'>\n
                                    <i class='fas fa-circle mr-3 {$color} fa-xs'></i>\n
                                    ".$item['name']."\n
                                </span>\n
                                <form id='frmCatDel_{$item['id']}' method='POST' action='cat/{$item['id']}' style='display:none'>\n
                                    <input type='hidden' name='_token' value='{$csrf}'>\n
                                    <input type='hidden' name='_method' value='DELETE'>\n
                                    <input type='hidden' id='cat_{$item['id']}' value='{$item['name']}'>\n
                                </form>\n
                                <a href='cat/{$item['id']}/edit'>\n
                                    <i class='fas fa-edit ml-5 text-primary fa-fw' style='cursor:pointer'></i>\n
                                </a>\n
                                <span style='cursor:pointer' onclick='delCat({$item['id']})'>\n
                                    <i class='fas fa-trash-alt ml-3 text-danger fa-fw ' style='cursor:pointer'></i>\n
                                </span>\n
                            </div>\n
                        </li>\n";
            }
        }

        return $menu."</ul>\n</div>\n";

    }
}
