<?php

namespace App\Services\Payment\Requests;

use App\Services\Payment\Contracts\RequestInterface;

class  IDPayRequest implements RequestInterface   {
    
    private $user ;
    private $amount ;
    private $orderId ;
    private $apiKey ;


    public function __construct(array $data)
    {
        $this->user = $data['user'];
        $this->amount = $data['amount'];
        $this->orderId = $data['orderId'];
        $this->apiKey = $data['apiKey'];
    }

    public function getAmount() : int {
       return $this->amount;
    }
    public function getApiKey() : string {
       return $this->apiKey;
    }
    public function getOrderId() : string {
       return $this->orderId;
    }
    public function getUser() : object {
        return $this->user;
    }
}