<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail; 
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Category;

class TestController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function test(Request $request)
    {
        $cat = Category::where('user_id', auth()->user()->id)->get();
        $data = $cat->all();
        $tree = $this->buildTree($data);
        // print_r ($this->printTree($tree));
        foreach ($tree as $cat) {
            if ($cat['type'] == 'income') {
                $income_data = $cat['child'];
            } else {
                $outcome_data = $cat['child'];
            }
        }
        // $test = array(
        //     0 => array('name' => 'abc'),
        //     1 => array('name' => 'def'),
        //     2 => array(
        //         'name' => 'ghi',
        //         'child' => array(
        //             0 => array('name' => '123'),
        //             1 => array('name' => '456')
        //         )
        //     )
        // );
        // $menu = $this->printTree($tree);
        $income = $this->printTree($income_data);
        $outcome = $this->printTree($outcome_data);
        // echo User::all()->count();
        return view('test', compact('income', 'outcome'));
    }

    public function buildTree($category, $parent_id = 0)
    {
        $branch = array();
        foreach ($category as $cat) {
            if ($cat['parent_id'] == $parent_id) {
                $child = $this->buildTree($category, $cat['id']);
                if ($child) {
                    $cat['child'] = $child;
                }
                $branch[] = $cat;
            }
        }
        return $branch;
    }

    public function printTree($tree)
    {
        $menu = "<ul class='navv flex-column bg-white mb-0'>\n";
        foreach ($tree as $item) {
            if (isset($item['child'])) {
                $menu .= "<li class='nav-item'>\n
                            <a href='#' class='nav-link text-dark font-italic bg-light'>
                                <i class='fas fa-circle ml-3 mr-3 text-success fa-fw'></i>\n
                                ".$item['name']."\n
                            </a>\n";
                $sub = $this->printTree($item['child']);
                $menu .= $sub."</li>\n";
            } else {
                $menu .= "<li class='nav-item'>\n
                            <a href='#' class='nav-link text-dark font-italic bg-light'>
                                <i class='fas fa-circle ml-3 mr-3 text-success fa-fw'></i>\n
                                ".$item['name']."\n
                            </a>\n
                        </li>\n";
            }
        }
        return $menu."</ul>\n";
    }
}
