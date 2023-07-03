<?php

namespace App\Services\Requests;

use App\Services\Payment\Contracts\RequestInterface;

class  ZarinpalRequest   implements RequestInterface  {
    private $user ;

    private $amount ;


    public function __construct(array $data)
    {
        $this->user = $data['user'];
        $this->amount = $data['amount'];
    }

    public function getAmount() : int {
       return $this->amount;
    }
    public function getUser() : object {
        return $this->user;
    }
}