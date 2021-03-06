<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function info()
    {
        return view('admin.info');
    }

    public function pass()
    {
        if($input = Input::all()){
            $rules = [
                'password' => 'required|between:1,20|confirmed',
            ];
            $msg = [
                'password.required' => '密碼不能為空',
                'password.between' => '新密碼必須介於1~20位',
                'password.confirmed' => '新密碼和確認密碼不一致',
            ];
            $validator = Validator::make($input,$rules,$msg); //密碼欄位驗證$input為輸入值,$rules為規則,$msg內建訊息為英文，改寫成中文訊息
            if($validator->passes()){
                $user = User::first(); //取得使用者資訊
                $_password = Crypt::decrypt($user->user_pass); //取得使用者的密碼後解密成明碼
                if($input['password_o'] == $_password){
                   $user->user_pass = Crypt::encrypt($input['password']); //新密碼加密後注入$user
                    $user->update();
                    return back()->with('success','修改成功');
                }else{
                    $validator->errors()->add('pw_error', '原密碼輸入錯誤');
                    return back()->withErrors($validator);

                    /*寫法二
                     return back()->with('errors','原密碼輸入錯誤');
                    ---pass.blade.php---
                    使用with傳參數用@foreach($errors->all() as $error)會出錯
                    因為這時的$errors為字串非陣列，直接輸出{{ $errors }}即可
                    有使用withErrors和with兩種方式傳參數需要先判斷$errors是否為object
                    @if(count($errors) >0 )
                        <div class="mark">
                            @if(is_object($errors))
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            @else
                                <p>{{ $errors }}</p>
                            @endif
                        </div>
                    @endif
                     */
                }
            }else{
                //dd($validator->errors()->all());
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.pass');
        }
    }
}
