<?php
namespace App\Repository;

use App\Repository\EloquentRepository;

class TransactionEloquentRepository extends EloquentRepository {
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Transaction::class;
    }

    public function getAttributes() {
        return [
            'user_id',
            'wallet_id',
            'cat_id',
            'amount',
            'details',
        ];
    }
}