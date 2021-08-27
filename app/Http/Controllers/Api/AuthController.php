<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\City;
use App\Models\BloodType;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator()->make($request->all() , ['name' => 'required', 'email' => 'required|unique:clients', 'phone' => 'required', 'last_donation_date' => 'required', 'date_of_birth' => 'required', 'password' => 'required', 'blood_type_id' => 'required',

        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()
                ->first() , $validator->errors());

        }
        $request->merge(['password' => bcrypt($request->password) ]);
        $client = Client::create($request->all());
        $client->api_token = Str::random(60);
        $client->save();
        $client->Governorates()->attach($request->city_id);
        $bloodType=BloodType::where('name',$request->blood_type)->first();
        $client->BloodTypeClintable()->attach($bloodType->id);
        return resposeJson(1, 'success', ['api_token' => $client->api_token, 'client' => $client->email]);

    }
    public function login(Request $request)
    {
        $validator = Validator()->make($request->all() , [

        'phone' => 'required', 'password' => 'required',

        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()
                ->first() , $validator->errors());

        }
        $client = Client::where('phone', $request->phone)
            ->first();
        if ($client)
        {
            if (Hash::check($request->password, $client->password))
            {
                return resposeJson(1, ' تم تسجيل الدخول بنجاح   ', ['api_token' => $client->api_token, 'client' => $client]);
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
        if($request->has('password')){
            $user->password=bcrypt($request->password);
            $user->save();
        }
        // if($request->has('governorate_id')){
        //     $user->cities()->detach($request->city_id);
        //     $user->cities()->attach($request->city_id);
        // }
        // if($request->has('blood_type')){
        //     $bloodType=BloodType::where('name',$request->blood_type)->first();
        //     $user->blood_Types()->detach($bloodType->id);
        //     $user->blood_Types()->attach($bloodType->id);
        // }
        $data=[
            'user'=>$request->user()->fresh(),
        ];
        return resposeJson(1, '   تم تحديث البيانات بنجاح ',$data);    
       
}
public function reset_password(Request $request){
    $validator = Validator()->make($request->all() , [

     
        'phone' =>'required', 


        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()->first() , $validator->errors());

        }
        $user=Client::where('phone',$request->phone)->first();
        if($user){
            $code=rand(1111,9999);
            $update=$user->update(['pin_code'=>$code]);
            if($update){
                //send mail or sms
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
        $user=Client::where('pin_code',$request->pin_code)->where('pin_code','!=',0)->first();
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

