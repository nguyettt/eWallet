<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\User;
use App\Wallet;
use App\Category;

class NavComposer
{
    protected $user;

    protected $wallet;

    protected $cat;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->wallet = Wallet::where('user_id', $this->user->id)->get();
        $this->cat = Category::where('user_id', $this->user->id)->get();
    }

    public function compose(View $view)
    {
        $cat_data = $this->cat->all();
        $cat_tree = $this->buildTree($cat_data);

        foreach ($cat_tree as $cat) {
            if ($cat['type'] == 'income') {
                $income_data = $cat['child'];
            } else {
                $outcome_data = $cat['child'];
            }
        }

        $cat_income = $this->buildMenu($income_data);
        $cat_outcome = $this->buildMenu($outcome_data);

        $view->with('user', $this->user)
             ->with('wallet', $this->wallet)
            //  ->with('cat', $cat_tree);
             ->with('income', $cat_income)
             ->with('outcome', $cat_outcome);
    }

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

    public function buildMenu($array)
    {
        $menu = "<ul class='navv flex-column bg-white mb-0'>\n";
        foreach ($array as $item) {
            if (isset($item['child'])) {
                $menu .= "<li class='nav-item'>\n
                            <a href='#' class='nav-link text-dark font-italic bg-light'>
                                <i class='fas fa-circle ml-3 mr-3 text-success fa-fw'></i>\n
                                ".$item['name']."\n
                            </a>\n";
                $sub = $this->buildMenu($item['child']);
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
