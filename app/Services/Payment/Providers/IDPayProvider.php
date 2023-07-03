<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\AbstractProviderInterface;
use App\Services\Payment\Contracts\PayInterface;
use App\Services\Payment\Contracts\VerifyInterface;
use App\Services\Payment\Exceptions\PaymentArgumentNotValidException;
use App\Services\Payment\Exceptions\PaymentNotPaidException;

class  IDPayProvider extends AbstractProviderInterface implements PayInterface , VerifyInterface {
    
    private const STATUS = [
      // "1" => 'پرداخت انجام نشده است',
      // "2" => 'پرداخت ناموفق بوده است',
      // "3" => 'خطا رخ داده است',
      // "4" => 'بلوکه شده ',
      // "5" => 'برگشت به پرداخت کننده',
      // "6" => 'برگشت خورده سیستمی',
      // "7" => 'انصراف از پرداخت',
      // "8" => 'به درگاه پرداخت منتقل شد',
      "10" => 'در انتظار تایید پرداخت',
      "100" => 'پرداخت تایید شده است',
      "101" => 'پرداخت قبلا تایید شده است',
      "200" => 'به دریافت کننده واریز شد',
    ];

    public function pay(){
 
        $params = array(
            'order_id' => $this->request->getOrderId(),
            'amount' => $this->request->getAmount(),
            'name' => $this->request->getUser()->name,
            'phone' =>  $this->request->getUser()->mobile,
            'mail' =>  $this->request->getUser()->email,
            'desc' => 'توضیحات پرداخت کننده',
            'callback' => route('payment.callback'),
          );
          
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: '. $this->request->getApiKey() .'',
            'X-SANDBOX: 1'
          ));
          
          $result = curl_exec($ch);
          curl_close($ch);
          

          $result =json_decode($result,true);
          if(isset($result['error_code'])){
            throw new PaymentArgumentNotValidException($result['error_code']);
          }

          return redirect()->away($result['link']);
    }


    public function verify(){

      $params = array(
        'id' => $this->request->getId(),
        'order_id' =>$this->request->getOrderId(),
      );
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/inquiry');
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'X-API-KEY: '.$this->request->getApiKey().'',
        'X-SANDBOX: 1',
      ));
      
      $result = curl_exec($ch);
      // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      $result =json_decode($result,true);

      if(isset($result['error_code'])){
        return [
          'status'     => false,
          'error_code' => $result['error_message']
        ];
      }

      if(in_array($result['status'],array_keys(self::STATUS))){
        return [
          'status'     => true,
          'error_code' => $result['status'],
          'data' => $result
        ];
      }

      return [
        'status' => false,
        'msg'    => $result['status']
      ];

    }
}