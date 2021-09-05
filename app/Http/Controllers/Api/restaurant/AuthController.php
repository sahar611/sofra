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

class AuthController extends Controller
{

  
   
    public function profile(Request $request){
    $validator = Validator()->make($request->all() , [

     //   'password' => 'comfirmed',
        'phone' =>Rule::unique('restaurants')->ignore($request->user()->id), 
        'email' =>Rule::unique('restaurants')->ignore($request->user()->id), 


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
public function allProducts(Request $request)
{
    $products = $request->user()->products()->paginate(20);
    return resposeJson(1,' تم تحميل قائمة المنتجات الخاصة بالمطعم ',[
        "products" => ProductResource::collection($products),
        "pagination" => getPagination($products)
    ]);
}


}

