<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PayRequest;
use App\Mail\SendOrderImages;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cookie;
use App\Services\Payment\PaymentService;
use App\Services\Payment\Requests\IDPayRequest;
use App\Services\Payment\Exceptions\PaymentArgumentNotValidException;
use App\Services\Payment\Requests\IDPayVerifyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use function PHPSTORM_META\map;

class PaymentsController extends Controller
{
   public function pay(PayRequest $request){

    $validatedData = $request->validated();


    $user = User::firstOrCreate([
        'email' => $validatedData['email'],
    ],[
        'mobile' => $validatedData['mobile'],
        'name' => $validatedData['name']
    ]);


    try{

        $orderItems = json_decode(Cookie::get('basket'),true);

        if(count($orderItems) <= 0){
         throw new PaymentArgumentNotValidException('سبد خرید شما خالیست');
        }
        
        $products = Product::findMany(array_keys($orderItems));

        $productsPrice = $products->sum('price');

        $refCode = Str::random(30);

        $createOrder = Order::create([
            'amount' => $productsPrice,
            'ref_code' => $refCode,
            'status' => 'unpaid',
            'user_id' => $user->id
        ]);

        $orderItemsForCreateOrder = $products->map(function($product){
         
            $currentProduct = $product->only('price','id');
            $currentProduct['product_id'] = $currentProduct['id'];
            unset($currentProduct['id']);
            return $currentProduct;
        });

        $createOrder->orderItems()->createMany($orderItemsForCreateOrder->toArray());
        $refId = rand(1111,9999);

        $createPayment = Payment::create([
         'gateway' => 'idpay',
         'ref_code' => $refCode,
         'status' => 'unpaid',
         'order_id' => $createOrder->id
        ]);

        $idPayRequest = new IDPayRequest([
           'amount' => $productsPrice,
           'user' => $user,
           'orderId' => $refCode,
           'apiKey' =>config('services.gateway.id_pay_key.api_key')
        ]);

        $paymentService = new PaymentService(PaymentService::IDPAY,$idPayRequest);

        return $paymentService->pay();

      }catch(Exception $e){
         return back()->with('failed',$e->getMessage());
      }

   }

   public function callback(Request $request){

      $payInfo = $request->all();

      $idPayVerifyRequest = new IDPayVerifyRequest([
        'id' =>  $payInfo['id'],
        'orderId' => $payInfo['order_id'],
        'apiKey' =>config('services.gateway.id_pay_key.api_key')
     ]);

     $paymentService = new PaymentService(PaymentService::IDPAY,$idPayVerifyRequest);

     $result = $paymentService->verify();

     if(!$result['status']){
        return redirect()->route('frontend.baskets.checkout')->with('failed','پرداخت شما ناموفق بود');
     }

     $currentPayment = Payment::where('ref_code',$result['data']['order_id'])->first();
     $currentPayment->update([
        'status' => 'paid',
        'res_id' => $result['data']['track_id']
     ]);

     $currentPayment->order()->update([
        'status' => 'paid'
     ]);

     $productsImages =$currentPayment->order->orderItems->map(function($orderItem){

         return $orderItem->product->source_url;
     });

     $currentUser =$currentPayment->order->user;
     
     Cookie::queue('basket', null);

     Mail::to($currentUser)->send(new SendOrderImages($productsImages->toArray(),$currentUser));
     return redirect()->route('frontend.products.all')->with('success','پرداخت موفق فایل ها برای شما ارسال شدند');

   }
}
