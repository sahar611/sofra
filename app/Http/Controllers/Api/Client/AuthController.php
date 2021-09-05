<?php
namespace App\Http\Controllers\Api\Client;

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

class AuthController extends Controller
{

  
   
    public function profile(Request $request){
    $validator = Validator()->make($request->all() , [

     //   'password' => 'comfirmed',
        'phone' =>Rule::unique('clients')->ignore($request->user()->id), 
        'email' =>Rule::unique('clients')->ignore($request->user()->id), 


        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()
                ->first() , $validator->errors());

        }
        $user=$request->user();
       

        $user->update($request->all());
        if($request->hasFile('image')){
            $file = $request->file('image');   
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $file->move($destinationPath, $fileName);
            $user->image = $fileName;


        };

        if($request->has('password')){
            $user->password=bcrypt($request->password);
            $user->save();
        }
     
        $data=[
            'user'=>$request->user()->fresh(),
        ];
        return resposeJson(1, '   تم تحديث البيانات بنجاح ',$data);    
       
}


public function addToken(Request $request)
{
    $validation = validator()->make($request->all(), [
        'platform'  => 'required',
        'token_device' => 'required',
    ]);

    if ($validation->fails()) {
        $data = $validation->errors();
        return resposeJson(0, $validation->errors()->first(), $data);
    }

    Token::where('token_device', $request->token_device)->delete();

    $request->user()->tokens()->create($request->all());
    return resposeJson(1, 'تم التسجيل بنجاح');
}
public function newOrder(Request $request)
{
    $validation = validator()->make($request->all(), [
        'restaurant_id'     => 'required|exists:restaurants,id',
      
        'items.*id'           => 'required|exists:products,id',
        'items.*.quantity'        => 'required',
        'address'           => 'required',
       
    ]);
    if ($validation->fails()) {
        $data = $validation->errors();
        return resposeJson(0, $validation->errors()->first(), $data);
    }

    $restaurant = Restaurant::find($request->restaurant_id);

  
    if ($restaurant->activated == '0') {
        return resposeJson(0, 'عذرا المطعم غير متاح في الوقت الحالي');
    }

  
    $order = $request->user()->orders()->create([
            'restaurant_id'     => $request->restaurant_id,
            'notes'              => $request->notes,
            'status'             => 'pending',
            'address'           => $request->address,
            // 'payment_method_id' => $request->payment_method_id,
      ]);

    $cost = 0;
    $delivery_cost = $restaurant->delivery_cost;

    if ($request->has('items')) {
        $counter = 0;
        foreach ($request->items as $i) {
            $item = Product::find($i["id"]);
            $readyItem = [
                $i["id"] => [
                    "quantity" => $i["quantity"],
                    "price" => $item->price,
                    "notes" => (isset($i["notes"])) ? $i["notes"] : "",
                ]
            ];

            $order->products()->attach($readyItem);
            $cost += ($item->price * $i["quantity"]);
        }

    
    if ($cost >= $restaurant->minimum_order) {
        $total = $cost + $delivery_cost; 
        $commission = settings()->app_commission * $cost; 
    //    $net = $total - settings()->commission;
        $update = $order->update([
                 'cost'          => $cost,
                 'delivery_cost' => $delivery_cost,
                 'total'         => $total,
                 'commission'    => $commission,
              //   'net'           => $net,
             ]);
    //    $request->user()->cart()->detach();
    $restaurant->notifications()->create([
        'title'      => 'لديك طلب جديد',
        'body'    => ' لديك طلب جديد برقم ' . $order->id ,
        'order_id'   => $order->id,
    ]);
return resposeJson(1, 'تم انشاء الطلب بنجاح');
            } 
        }
}
public function currentOrders(Request $request)
{
    $orders = Order::where("status", "pending")->where("client_id", $request->user()->id)->paginate(10);
    return  resposeJson(1, "الطلبات الحالية", [
        "orders" => OrderResource::collection($orders),
        "pagination" => getPagination($orders)
    ]);
}
public function confirmOrder(Request $request)
{
    $order = Order::where("client_id", $request->user()->id)->find($request->order_id);
    if ($order->status =="confirmed") {
        return resposeJson(0, 'لا يوجد طلب بهذه البيانات ');
    }
    $order->update(['status' => 'confirmed']);
    $restaurant=$order->restaurant;
    $restaurant->notifications()->create([
        'title'      => 'تم توصيل الطلب      ',
        'body'    => 'تم تأكيد التوصيل للطلب رقم ' . $request->order_id . ' للعميل',
        'order_id'   => $request->order_id,
    ]);


    return resposeJson(1, 'تم تأكيد استلام الطلب ');
}
public function decliendOrder(Request $request)
{
    $order = $request->user()->orders()->find($request->order_id);
    if ($order->status =="confirmed" && $order->status =="delivered" ) {
        return resposeJson(0, 'عفوا لا يمكنك الغاء  طلبك الان');
    }
    $order->update(['status' => 'decliend']);
    $restaurant=$order->restaurant;
    $restaurant->notifications()->create([
        'title'      => 'قام العميل بالغاء الطلب         ',
        'body'    => 'تم الغاء الطلب الخاص برقم' . $request->order_id . ' من العميل ',
        'order_id'   => $request->order_id,
    ]);
    return resposeJson(1, 'تم الغاء طلبك الان    ');
}
public function allOrders(Request $request)
{
    $orders = $request->user()->orders()->whereIn('status',["decliend","confirmed"])->paginate(10);
 
    return resposeJson(1, 'تم تحميل قائمة الطلبات السابقة', [
        "orders" => OrderResource::collection($orders),
        "pagination" => getPagination($orders)
    ]);
}
public function review(Request $request){
    $validator = Validator()->make($request->all() , [

       'rating' => 'required',
       'comment' => 'required',
       'restaurant_id' => 'required|exists:restaurants,id',



        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()
                ->first() , $validator->errors());

        }
        
        $data = Review::create([
            "comment" => $request->comment,
            "rating" => $request->rating,
            "restaurant_id" =>  $request->restaurant_id,
            "client_id" => $request->user()->id
        ]);

 
        return resposeJson(1, ' تم تقييمك بنجاح ',$data);    
       
}



}

