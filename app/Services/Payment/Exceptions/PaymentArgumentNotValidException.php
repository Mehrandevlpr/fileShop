<?php 

namespace App\Services\Payment\Exceptions;

use Exception;

class PaymentArgumentNotValidException extends Exception {

    public function __construct(string $message)
    {
        throw new Exception($message);
    }
}