<?php
namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\City;
use Image;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestPassword;
use App\Models\Token;
use App\Models\Product;
use App\Models\Order;
use App\Models\Restaurant;
use App\Http\Resources\OrderResource;
use App\Models\Review;
use App\Http\Resources\ProductResource;
use App\Http\Resources\OfferResource;

class OrderController extends Controller
{

public function allOrders(Request $request)
{
    $products = $request->user()->orders()->paginate(20);
    return resposeJson(1,' تم تحميل قائمة الطلبات الخاصة بالمطعم ',[
        "products" => OrderResource::collection($products),
        "pagination" => getPagination($products)
    ]);
}
public function acceptOrder(Request $request)
{
    $order= $request->user()->orders()->find($request->order_id);
   
    
    $order->update(['status' => 'accepted']);
    $client = $order->client;
    $client->notifications()->create([
        'title' => 'تم قبول طلبك',
        'body' => 'تم قبول  طلبك رقم  '.$request->order_id,
        'order_id' => $request->order_id,
    ]);

    
    return resposeJson(1, 'تم تأكيد قبول الطلب ');
}
public function cancelledOrder(Request $request)
{
    $order= $request->user()->orders()->find($request->order_id);
   
    
    $order->update(['status' => 'cancelled']);
    $client = $order->client;
    $client->notifications()->create([
        'title' => 'تم رفض  طلبك',
        'body' => 'تم رفض  طلبك رقم  '.$request->order_id,
        'order_id' => $request->order_id,
    ]);

    
    return resposeJson(1, 'تم رفض الطلب ');
}
public function confirmOrder(Request $request)
{
    $order = $request->user()->orders()->find($request->order_id);
    
   
    $order->update(['status' => 'delivered']);
    $client = $order->client;
    $client->notifications()->create([
        'title' => 'تم تأكيد توصيل طلبك',
        'body' => 'تم تأكيد التوصيل للطلب رقم '.$request->order_id,
        'order_id' => $request->order_id,
    ]);

   
    return resposeJson(1,'تم تأكيد الوصول');
}


}

