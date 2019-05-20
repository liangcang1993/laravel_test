<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\Services\GoogleAuthService;

use Input;
use Auth;
use Debugbar;
use Config;
use Session;
use DB;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/admin';
    protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout', 'ssoLogin']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => bcrypt($data['password']),
        ]);
    }

    public function postLogin()
    {
        $messages = [
          'captcha.required' => '请输入验证码',
          'captcha.captcha' => '验证码不正确或已过期，请重试'
        ];

        $validator = Validator::make(Input::all(), [
            //'captcha' => 'required|captcha',
            'password' => 'required',
            'email' => 'required|email',
        ], $messages);

        if ($validator->fails()) {
            return redirect('login')->withErrors($validator)->withInput();
        }

        $email = Input::get('email');
        $captcha = Input::get('captcha');
        $password = Input::get('password');
        $remember = Input::get('remember');

        $check = ['email' => $email, 'password' => $password, 'is_delete' => 0];
        if (Auth::attempt($check, $remember)) {
            // Session::push($check['email'],1);
            // $auths = DB::table('admin_user')->where('email',$check['email'])
            //                                 ->join('admin_role', 'admin_user.role_id', '=', 'admin_role.id')
            //                                 ->select('admin_role.auth_json')
            //                                 ->get();
            //                                 //dd($auths[0]->auth_json);
            // if ($auths) 
            // {
            //     $authArr = json_decode($auths[0]->auth_json,true);//dd($authArr);
            //     foreach ($authArr as $key => &$value) 
            //     {
            //         Session::push($check['email'].'app', $key);
            //         Session::push($check['email'],$key);
            //         foreach ($value as &$val) 
            //         {
            //             Session::push($check['email'].$key.'auth',$val);
            //             Session::push($check['email'].'auth',$val);
            //             Session::push($check['email'],$val);
            //         }
                   
            //     }
            // }

            
            // $auths = DB::table('admin_role')->where('is_delete','<>',1)->get();
            // if ($auths) 
            // {
            //     // $authArr = json_decode($auths[0]->auth_json,true);//dd($authArr);
            //     foreach ($auths as $key => &$value) 
            //     {
            //         $authArr = json_decode($value->auth_json,true);
            //         if ($authArr) 
            //         {
            //             foreach ($authArr as $kk => &$val) 
            //             {
            //                 Session::push($check['email'],$kk);
            //                 Session::push('role'.$value->id,$kk);
            //                 foreach ($val as &$vv) 
            //                 {
            //                     Session::push('role'.$value->id,$vv);
            //                 }
                            
            //             }
            //         }else{
            //             Session::push('role'.$value->id,'');
            //         }
            //     }
            // }
            //dd(Session::get('role5'));
            // dd(Session::get($check['email'].$key),Session::get($check['email'].$key.'auth'),Session::get($check['email']));
            return redirect()->intended('/');
        } else {
            return redirect('login')->withErrors("密码和账户不匹配，请重新输入")->withInput();
        }
    }

    public function ssoLogin()
    {
        Debugbar::disable();

        $action = Input::get('action');

        $action_arr = ['login', 'logout'];
        if (!in_array($action, $action_arr)) {
            return -1;
        }

        $token = Input::get('token');

        if ($action == 'login') {
            $ssourl = Config::get('base.ssohost');
            //验证token
            $ssourl .= '/sso/checktoken?token='.$token;
            $jsonContent = file_get_contents($ssourl);
            $content = json_decode($jsonContent,true);

            // 成功情况
            if ($content['code'] == 0) {
                $user = User::find($content['data']['account']);
                Auth::login($user);
            } else {
                return -2;
            }
        } else {
            Auth::logout();
        }

        return response(Input::get('callback') . "('" . Config::get('base.appkey') . "')")->withCookie('token', $token, 10000000)
            ->header('p3p', 'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    }
}
