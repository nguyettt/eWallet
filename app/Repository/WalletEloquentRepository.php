<?php
namespace App\Repository;

use App\Repository\EloquentRepository;

class WalletEloquentRepository extends EloquentRepository {
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return \App\Wallet::class;
    }

    public function getAttributes() {
        return [
            'user_id',
            'name',
            'balance',
        ];
    }
}