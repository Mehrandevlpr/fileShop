<?php 

namespace App\Services\Payment\Exceptions;

use Exception;

class ProviderNotFoundException extends Exception {

    public function __construct(string $message)
    {
        throw new Exception($message);
    }
}