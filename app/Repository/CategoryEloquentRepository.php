<?php
namespace App\Repository;

use App\Repository\EloquentRepository;

class CategoryEloquentRepository extends EloquentRepository {
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Category::class;
    }

    public function getAttributes() {
        return [
            'user_id',
            'type',
            'name',
            'parent_id',
        ];
    }

    /**
     * Call the _findChild method to build an array of cat_id that belong to the specified id
     *
     * @param int $parent
     * @return array $child
     */
    public function findChild($parent)
    {
        $list = $this->getAll();
        return $this->_findChild($parent, $list);
    }

    /**
     * Build an array of category id that belong to the $parent id
     * 
     * @param int  $id
     * @param collection $list
     * @return array $child
     */
    public function _findChild ($parent, $list)
    {
        $_child = $list->where('parent_id', $parent);

        if ($_child->count() > 0) {
            foreach ($_child as $item) {
                $child[] = $item->id;
                $new = $this->_findChild($item->id, $list);
                if ($new != null) {
                    $child = array_merge($child, $new);
                }
            }
        } else {
            $child = null;
        }

        return $child;
    }
}