<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\City;
use Image;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestPassword;
use App\Models\Token;


class RestaurantController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator()->make($request->all() , 
        ['name' => 'required',
         'email' => 'required|unique:restaurants', 
         'phone' => 'required', 
         'region_id' => 'required', 
         'address' => 'required', 
         'password' => 'required', 
         'image' => 'required',
         'whatsapp' => 'required',  
         'delivery_cost' => 'required',  
         'minimum_order' => 'required',  
      
        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()
                ->first() , $validator->errors());

        }
        $request->merge(['password' => bcrypt($request->password) ]);
        $restaurant = Restaurant::create($request->all());
        // $client->api_token = Str::random(60);
        $accessToken = $restaurant->createToken('authToken')->accessToken;
        $restaurant->api_token = $accessToken ;
        if($request->hasFile('image')){
            $file = $request->file('image');   
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $file->move($destinationPath, $fileName);
            $restaurant->image = $fileName;

        };
        $restaurant->save();
        return resposeJson(1, 'success', ['api_token' => $accessToken, 'restaurant' => $restaurant->email]);

    }
    public function login(Request $request)
    {
        $validator = Validator()->make($request->all() , [

        'email' => 'required', 'password' => 'required',

        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()
                ->first() , $validator->errors());

        }
        $restaurant = Restaurant::where('email', $request->email)
            ->first();
        if ($restaurant)
        {
            $accessToken = $restaurant->createToken('authToken')->accessToken;

            if (Hash::check($request->password, $restaurant->password))
            {
                return resposeJson(1, ' تم تسجيل الدخول بنجاح   ', ['api_token' => $accessToken, 'restaurant' => $restaurant]);
            }
            else
            {
                return resposeJson(0, 'بيانات الدخول غير صحيحه ');

            }
        }
        else
        {
            return resposeJson(0, 'بيانات الدخول غير صحيحه ');

        }

    }
    public function profile(Request $request){
        
    $validator = Validator()->make($request->all() , [

        'password' => 'required|confirmed',
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
public function reset_password(Request $request){
    $validator = Validator()->make($request->all() , [

     
        'email' =>'required', 


        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()->first() , $validator->errors());

        }
        $user=Restaurant::where('email',$request->email)->first();
        if($user){
            $code=rand(1111,9999);
            $update=$user->update(['pin_code'=>$code]);
            if($update){
                //send mail or sms
            //     Mail::to($user->email)
            //     ->cc("saharnagy11@gmail.com")
            //    ->send(new RestPassword($code));
                return resposeJson(1, 'تم ارسال sms تحتوي علي الكود ' , ['pin_code'=>$code]);
            }else{
                return resposeJson(0, 'حدث خطأ حاول مره اخري');

            }
        }       
}
public function save_password(Request $request){
    $validator = Validator()->make($request->all() , [

        'pin_code' =>'required', 

        'password' =>'required|confirmed', 


        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()->first() , $validator->errors());

        }
        $user=Restaurant::where('pin_code',$request->pin_code)->where('pin_code','!=',0)->first();
        if($user){
           $user->password=bcrypt($request->password);
         //  $user->pin_code=null;
            if($user->save()){
                //send mail or sms
             
                return resposeJson(1, 'تم تغيير كلمة المرور بنجاح' );
            }else{
                return resposeJson(0, 'حدث خطأ حاول مره اخري');

            }
        } else{
            return resposeJson(0, ' هذا الكود غير صالح ');

        }      
}

}

