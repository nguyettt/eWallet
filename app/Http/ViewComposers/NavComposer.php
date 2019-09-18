<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repository\UserEloquentRepository;
use App\Repository\WalletEloquentRepository;
use App\Repository\CategoryEloquentRepository;

class NavComposer
{
    protected $user;

    protected $wallet;

    protected $cat;

    public function __construct(
        WalletEloquentRepository $walletRepo,
        CategoryEloquentRepository $catRepo
    )
    {
        $this->user = auth()->user();
        $this->wallet = $walletRepo->getAll();
        $this->cat = $catRepo->getAll();
    }

    public function compose(View $view)
    {
        $cat_data = $this->cat;
        $cat_tree = $this->buildTree($cat_data);

        foreach ($cat_tree as $cat) {
            if ($cat['type'] == 1) {
                $income_data = $cat['child'];
            } elseif ($cat['type'] == 2) {
                $outcome_data = $cat['child'];
            } else {
                $transfer_data = ($cat['child']) ? $cat['child'] : null;
            }
        }

        $cat_income = $this->buildMenu($income_data, 1);
        $cat_outcome = $this->buildMenu($outcome_data, 2);
        if ($transfer_data != null) {
            $cat_transfer = $this->buildMenu($transfer_data, 3);
        } else {
            $cat_transfer = '';
        }        

        $view->with('user', $this->user)
             ->with('wallet', $this->wallet)
             ->with('cat', $this->cat)
             ->with('income', $cat_income)
             ->with('outcome', $cat_outcome)
             ->with('transfer', $cat_transfer);
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

    public function buildMenu($array, $type)
    {
        switch ($type) {
            case 1: $color = 'text-success';
                    break;
            case 2: $color = 'text-danger';
                    break;
            case 3: $color = 'text-info';
                    break;
        }

        $menu = "<ul class='navv flex-column bg-white mb-0'>\n";

        foreach ($array as $item) {
            if (isset($item['child'])) {
                $menu .= "<li class='nav-item'>\n
                            <a href='cat/{$item['id']}' class='nav-link text-dark font-italic pl-0'>
                                <i class='fas fa-circle mr-3 {$color } fa-fw'></i>\n
                                ".$item['name']."\n
                            </a>\n";
                $sub = $this->buildMenu($item['child'], $type);
                $menu .= $sub."</li>\n";
            } else {
                $menu .= "<li class='nav-item'>\n
                            <a href='cat/{$item['id']}' class='nav-link text-dark font-italic pl-0'>
                                <i class='fas fa-circle mr-3 {$color} fa-fw'></i>\n
                                ".$item['name']."\n
                            </a>\n
                        </li>\n";
            }
        }

        return $menu."</ul>\n";
    }
}
