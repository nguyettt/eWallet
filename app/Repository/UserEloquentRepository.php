<?php
namespace App\Repository;

use App\Repository\EloquentRepository;

class UserEloquentRepository extends EloquentRepository {
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\User::class;
    }

    public function getAttributes() {
        return [
            'username',
            'email',
            'password',
            'gender',
            'dob',
            'firstName',
            'lastName'
        ];
    }
}