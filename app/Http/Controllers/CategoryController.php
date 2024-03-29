<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryFormRequest;
use App\Http\Requests\EditCategoryFormRequest;
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
        $cat = $this->catRepo->query()
                            ->where('delete_flag', null)
                            ->get();
        
        $data = [
            'income' => config('variable.type.income'),
            'outcome' => config('variable.type.outcome'),
        ];
        $data = json_encode($data);

        return view('cat.create', compact('wallet_id', 'cat', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryFormRequest $request)
    {
        $deleted = $this->catRepo->deleted();

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
            $this->catRepo->create($data);
            return redirect('/cat');
        }
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
        $cat = $this->catRepo->find($id);

        $list[] = $id;

        $child = $this->catRepo->findChild($id) ;

        if ($child != null) {
            $list = array_merge($list, $child);
        }

        $cats = $this->catRepo->query()
                            ->whereNotIn('id', $list)
                            ->where('type', $cat->type)
                            ->get();

        if ($cat->parent_id == 0) {
            
            return back()->withErrors(['edit' => 'Can not edit default category']);

        }

        return view('cat.edit', compact('cat', 'cats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditCategoryFormRequest $request, $id)
    {
        $deleted = $this->catRepo->deleted();

        if ($deleted->count() > 0) {
            foreach ($deleted as $item) {
                if ($item->name == $request->name) {
                    return back()->withErrors(['existed' => 'Cat exists'])->withInput();
                }
            }
        }

        $data = $request->all();
        $this->catRepo->update($id, $data);
        return redirect('/cat');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->catRepo->find($id);

        if ($category->parent_id == 0) {            
            return back()->withErrors(['delete' => 'Can not delete default category']);
        }

        $cat = $this->catRepo->query()
                            ->where('delete_flag', null)
                            ->where('parent_id', $id)
                            ->get();

        if ($category->transaction()->count() == 0 && $cat->count() == 0) {
            $category->delete();
        } else {
            $data['delete_flag'] = 1;
            $this->catRepo->update($id, $data);            
            foreach ($cat as $item) {    
                $this->destroy($item->id);    
            }
        }

        return redirect('/cat');
    }

    /**
     * Restore a category and all of it's parent category
     *
     * @param int $id
     * @return void
     */
    public function restore($id)
    {
        $this->catRepo->restore($id);
        return redirect('/cat');
    }

    /**
     * Build category tree from database
     *
     * @param array $array
     * @param integer $parent_id
     * 
     * @return array $branch
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

    /**
     * Build html menu
     * 
     * @param array $array
     * @param int $parent
     * 
     * @return string $menu
     */
    public function buildMenu($array, $parent)
    {
        if ($parent != '#') {
            $menu = "<div class='collapse' id='{$parent}'>\n<ul class='menu'>\n";
        } else {
            $menu = "<div>\n<ul class='menu'>\n";
        }
        

        foreach ($array as $item) {
            switch ($item['type']) {
                case config('variable.type.income'): $color = 'text-success';
                        break;
                case config('variable.type.outcome'): $color = 'text-danger';
                        break;
                case config('variable.type.transfer'): $color = 'text-info';
                        break;
            }
            $hidden = ($item['parent_id'] == 0)?'hidden':'';
            $csrf = csrf_token();
            if (isset($item['child'])) {
                $menu .= "<li>\n";
                $menu .= "<div class='d-flex'>\n";
                $menu .= "<a href='#' class='{$color} h5 mb-1 mt-1' data-toggle='collapse' data-target='#{$item['name']}'>";
                $menu .= "<i class='fas fa-circle mr-3 {$color } fa-xs'></i>\n";
                $menu .= $item['name']."\n";
                $menu .= "</a>\n";
                $menu .= "<form id='frmCatDel_{$item['id']}' method='POST' action='cat/{$item['id']}' style='display:none'>\n";
                $menu .= "<input type='hidden' name='_token' value='{$csrf}'>\n";
                $menu .= "<input type='hidden' name='_method' value='DELETE'>\n";
                $menu .= "<input type='hidden' id='cat_{$item['id']}' value='{$item['name']}'>\n";
                $menu .= "</form>\n";
                $menu .= "<a href='cat/{$item['id']}/edit' {$hidden}>\n";
                $menu .= "<i class='fas fa-edit ml-5 text-primary fa-fw align-bottom' style='cursor:pointer'></i>\n";
                $menu .= "</a>\n";
                $menu .= "<span style='cursor:pointer' onclick='delCat({$item['id']})' {$hidden}>\n";
                $menu .= "<i class='fas fa-trash-alt ml-3 text-danger fa-fw align-bottom' style='cursor:pointer'></i>\n";
                $menu .= "</span>\n";
                $menu .= "<i class='fas fa-sort-down ml-3 {$color} fa-lg' data-toggle='collapse' data-target='#{$item['name']}' style='cursor:pointer'></i>\n";
                $menu .= "</div>";
                $sub = $this->buildMenu($item['child'], $item['name']);
                $menu .= $sub."</li>\n";
            } else {
                $menu .= "<li class='mt-1 mb-1'>\n";
                $menu .= "<div class='d-flex'>\n";
                $menu .= "<span class='{$color} h5'>\n";
                $menu .= "<i class='fas fa-circle mr-3 {$color} fa-xs'></i>\n";
                $menu .= $item['name']."\n";
                $menu .= "</span>\n";
                $menu .= "<form id='frmCatDel_{$item['id']}' method='POST' action='cat/{$item['id']}' style='display:none'>\n";
                $menu .= "<input type='hidden' name='_token' value='{$csrf}'>\n";
                $menu .= "<input type='hidden' name='_method' value='DELETE'>\n";
                $menu .= "<input type='hidden' id='cat_{$item['id']}' value='{$item['name']}'>\n";
                $menu .= "</form>\n";
                $menu .= "<a href='cat/{$item['id']}/edit' {$hidden}>\n";
                $menu .= "<i class='fas fa-edit ml-5 text-primary fa-fw' style='cursor:pointer'></i>\n";
                $menu .= "</a>\n";
                $menu .= "<span style='cursor:pointer' onclick='delCat({$item['id']})' {$hidden}>\n";
                $menu .= "<i class='fas fa-trash-alt ml-3 text-danger fa-fw ' style='cursor:pointer'></i>\n";
                $menu .= "</span>\n";
                $menu .= "</div>\n";
                $menu .= "</li>\n";
            }
        }

        return $menu."</ul>\n</div>\n";
    }
}
