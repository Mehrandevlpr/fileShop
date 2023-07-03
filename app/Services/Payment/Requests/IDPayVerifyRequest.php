<?php

namespace App\Services\Payment\Requests;

use App\Services\Payment\Contracts\RequestInterface;

class  IDPayVerifyRequest implements RequestInterface   {
    
    private $id ;
    private $orderId ;
    private $apiKey ;


    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->orderId = $data['orderId'];
        $this->apiKey = $data['apiKey'];
    }

    public function getId() : string {
       return $this->id;
    }
    public function getApiKey() : string {
       return $this->apiKey;
    }
    public function getOrderId() : string {
       return $this->orderId;
    }

}