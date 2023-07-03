<?php

namespace App\Services\Providers;

use App\Services\Payment\Contracts\PayInterface;
use App\Services\Payment\Contracts\AbstractProviderInterface;
use App\Services\Payment\Contracts\VerifyInterface;

class  Zarinpalprovider extends AbstractProviderInterface implements PayInterface , VerifyInterface {
    
    public function pay(){

    }

    public function verify(){

    }
}