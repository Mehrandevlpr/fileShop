<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\RequestInterface;
use App\Services\Payment\Exceptions\ProviderNotFoundException;

class  PaymentService   {

    public const ZARINPAL ='ZarinpalProvider';
    public const IDPAY ='IDPayProvider';

    private $providerName;
    private $request;
    
    public function __construct(string $providerName ,RequestInterface $request)
    {
        $this->providerName = $providerName ;
        $this->request      = $request;
    }

    public function  pay()
    {
       return $this->findProvider()->pay();
    }

    public function  verify()
    {
       return $this->findProvider()->verify();
    }

    public function findProvider()
    {
        $baseNameSpace = "App\\Services\\Payment\\Providers\\".$this->providerName ;

        if(!class_exists($baseNameSpace)){
            throw new ProviderNotFoundException('درگاه پرداخت مدنظر یافت نشد');
        }

        return new $baseNameSpace($this->request);
    }

}