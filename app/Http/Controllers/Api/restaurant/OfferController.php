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

class OfferController extends Controller
{

public function allOffer(Request $request)
{
    $products = $request->user()->offers()->paginate(20);
    return resposeJson(1,' تم تحميل قائمة العروض الخاصة بالمطعم ',[
        "products" => OfferResource::collection($products),
        "pagination" => getPagination($products)
    ]);
}
public function addOffer(Request $request)
{
    $validator = Validator()->make($request->all() , [
        'name' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
        'image' => 'required',
    ]);

    if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()
                ->first() , $validator->errors());

        }
    $product = $request->user()->offers()->create($request->all());
    if($request->hasFile('image')){
        $file = $request->file('image');   
        $fileName = time().'.'.$file->getClientOriginalExtension();
        $destinationPath = public_path('/uploads');
        $file->move($destinationPath, $fileName);
        $product->image = $fileName;


    };
    $product->save();


    return resposeJson(1,'تم الاضافة بنجاح',$product);
}
public function editOffer(Request $request)
{
    $validator = Validator()->make($request->all() , [
        'name' => 'required',
        'image' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',

    ]);

    if ($validator->fails())
    {
        return resposeJson(0, $validator->errors()
            ->first() , $validator->errors());

    }

    $product = $request->user()->offers()->find($request->id);
  
    $product->update($request->all());
    if($request->hasFile('image')){
        $file = $request->file('image');   
        $fileName = time().'.'.$file->getClientOriginalExtension();
        $destinationPath = public_path('/uploads');
        $file->move($destinationPath, $fileName);
        $product->image = $fileName;


    };

    return resposeJson(1,'تم تعديل العرض بنجاح  ',$product);
}
public function deleteOffer(Request $request)
{
    $validator = Validator()->make($request->all() , [
        'id' => 'required|exists:offers,id',
      
    ]);

    if ($validator->fails())
    {
        return resposeJson(0, $validator->errors()
            ->first() , $validator->errors());

    }
    $product = $request->user()->offers()->find($request->id);
    $product->delete();
    return resposeJson(1,'تم الحذف بنجاح');
}

}

