<?php

namespace App\Services\Payment\Contracts;


abstract class AbstractProviderInterface {
    

    protected $request;
    /**
     * Class constructor.
     */
    public function __construct(RequestInterface $request)
    {
      $this->request = $request;
    }
}