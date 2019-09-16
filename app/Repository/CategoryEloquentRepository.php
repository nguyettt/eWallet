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
}