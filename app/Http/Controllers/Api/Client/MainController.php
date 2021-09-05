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


class MainController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator()->make($request->all() , 
        ['name' => 'required',
         'email' => 'required|unique:clients', 
         'phone' => 'required', 
         'region_id' => 'required', 
         'address' => 'required', 
         'password' => 'required', 
         'image' => 'required',

        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()
                ->first() , $validator->errors());

        }
        $request->merge(['password' => bcrypt($request->password) ]);
        $client = Client::create($request->all());
        // $client->api_token = Str::random(60);
        $accessToken = $client->createToken('authToken')->accessToken;
        $client->api_token = $accessToken ;
        if($request->hasFile('image')){
            $file = $request->file('image');   
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $file->move($destinationPath, $fileName);
            $client->image = $fileName;

        };
        $client->save();
        return resposeJson(1, 'success', ['api_token' => $accessToken, 'client' => $client->email]);

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
        $client = Client::where('email', $request->email)
            ->first();
        if ($client)
        {
            $accessToken = $client->createToken('authToken')->accessToken;

            if (Hash::check($request->password, $client->password))
            {
                return resposeJson(1, ' تم تسجيل الدخول بنجاح   ', ['api_token' => $accessToken, 'client' => $client]);
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
  
public function resetPassword(Request $request){
    $validator = Validator()->make($request->all() , [

     
        'email' =>'required', 


        ]);
        if ($validator->fails())
        {
            return resposeJson(0, $validator->errors()->first() , $validator->errors());

        }
        $user=Client::where('email',$request->email)->first();
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
public function savePassword(Request $request){
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

