<?php

namespace App\Http\Controllers\Auth;

use App\Configurationsite;
use App\User;
use Laravel\Socialite\Two\InvalidStateException;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\CartSession;
use App\Cart;
use Session;
use App\OptionCart;
use Auth;
use Redirect;
use Socialite;
use Mail;

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

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);

    }

    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|confirmed',
        ], [
            'email.unique' => trans('site.email_unique_errors'),
            'phone.unique' => trans('site.phone_unique_errors')
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();

            return redirect('register')->withInput()->withErrors($errors);
        }

        $add = new User();
        $add->name = $request->Input('name');
        $add->email = $request->Input('email');
        $add->phone = $request->Input('phone');
        $add->password = bcrypt($request->Input('password'));
        $add->save();

        $e = Configurationsite::select('email')->first();
        $emailsales = $e->email;
        $data['to'] = $add->email;
        $data['subject'] = 'BabyMumz مرحبا بك';
        $data['message'] = 'مرحبا بك ' . $add->name . '<br />' . 'تم تسجيل حسابك لدينا بنجاح يمكنك من الآن طلب المنتجات ومتابعة العروض.';
        try {
            Mail::send('auth.emails.template', ['data' => $data], function ($message) use ($emailsales, $data) {
                $message->from($emailsales)
                    ->to($data['to'])
                    ->subject($data['subject']);
            });
        } catch (\Exception $ex) {

        }

        $user = User::where('email', $request->Input('email'))->first();
        $user2 = User::where('phone', $request->Input('email'))->first();
        $errors = [];

        if ($user || $user2) {

            $field = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $request->merge([$field => $request->input('email')]);

            if (Auth::attempt($request->only($field, 'password'))) {
                $links = session()->has('links') ? session('links') : [];
                $currentLink = request()->path(); // Getting current URI like 'category/books/'
                array_unshift($links, $currentLink); // Putting it in the beginning of links array
                session(['links' => $links]); // Saving links array to the session
                $this->authenticated();
                return Redirect::intended($this->redirectPath());
            } else {
                $errors = ['password' => trans('home.password_error')];
                return redirect('login')->withInput()->withErrors($errors);
            }

        } else {
            if (!$user) {
                $errors = ['email_phone' => trans('home.email_phone_errors')];
            }
            return redirect('login')->withInput()->withErrors($errors);
        }

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ($data['email']) {
            return Validator::make($data, [
                'name' => 'required|max:255',
                'email' => 'email|unique:users',
                'phone' => 'required|unique:users',
                'password' => 'required|confirmed',
            ], [
                'email.unique' => trans('site.email_unique_errors'),
                'phone.unique' => trans('site.phone_unique_errors'),
            ]);
        } else {
            return Validator::make($data, [
                'name' => 'required|max:255',
                'phone' => 'required|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);
        }
    }

    public function login(Request $request)
    {

        $user = User::where('email', $request->login)->first();
        $user2 = User::where('phone', $request->login)->first();
        $errors = [];

        if ($user || $user2) {

            $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $request->merge([$field => $request->input('login')]);

            if (Auth::attempt($request->only($field, 'password'))) {
                $links = session()->has('links') ? session('links') : [];
                $currentLink = request()->path(); // Getting current URI like 'category/books/'
                array_unshift($links, $currentLink); // Putting it in the beginning of links array
                session(['links' => $links]); // Saving links array to the session
                $this->authenticated();
                return Redirect::intended($this->redirectPath());
            } else {
                $errors = ['password' => trans('home.password_error')];
                return redirect('login')->withInput()->withErrors($errors);
            }

        } else {
            if (!$user) {
                $errors = ['email_phone' => trans('home.email_phone_errors')];
            }
            return redirect('login')->withInput()->withErrors($errors);
        }
    }

    protected function authenticated()
    {
        //put your thing in here
        if (\Session::has('cartsessionnumber')) {
            $number = Session::get('cartsessionnumber');
            $cartsession = CartSession::where('session_number', $number)->get();

            foreach ($cartsession as $key => $value) {
                $cart = new Cart();
                $cart->product_id = $value->product_id;
                $cart->user_id = Auth::user()->id;
                $cart->count = $value->count;
                $cart->optionprice = $value->optionprice;
                $cart->save();
                if ($value->optionradio) {
                    $radioids = explode(",", $value->optionradio);
                    foreach ($radioids as $x) {
                        $optioncart = new OptionCart();
                        $optioncart->cart_id = $cart->id;
                        $optioncart->option_id = $x;
                        $optioncart->save();
                    }
                }
                if ($value->optioncheck) {
                    $checkids = explode(",", $value->optioncheck);
                    foreach ($checkids as $xx) {
                        $optioncart = new OptionCart();
                        $optioncart->cart_id = $cart->id;
                        $optioncart->option_id = $xx;
                        $optioncart->save();
                    }
                }

            }
            CartSession::where('session_number', $number)->delete();

            Session::forget('cartsessionnumber');
            // return redirect()->intended($this->redirectPath());
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subscribe' => $data['newsletter'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $userModel = new User;
            $create['name'] = $user->name;
            $create['email'] = $user->email;
            $create['google_id'] = $user->id;
            $create['remember_token'] = $user->token;
            $create['password'] = bcrypt(123456);

            $checkuser = User::where('email', $create['email'])->first();

            if ($checkuser) {
                $checkuser->google_id = $create['google_id'];
                $checkuser->save();
                Auth::login($checkuser);
                $this->authenticated();
                return Redirect::intended($this->redirectPath());
            } else {
                $userModel = new User;
                $createdUser = $userModel->addNew1($create);
                Auth::loginUsingId($createdUser->id);
                $this->authenticated();
                return Redirect::intended($this->redirectPath());
            }

        } catch (Exception $e) {

            return redirect('auth/google');

        }

    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }


    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $create['name'] = $user->name;
            if($user->email != null){
            	$create['email'] = $user->email;
            }else{
            	$create['email'] = 'Facebook'.str_replace(' ', '', $create['name']).'@facebook.com';
            }
            
            $create['facebook_id'] = $user->id;
            $create['remember_token'] = $user->token;
            $create['password'] = bcrypt(123456);

            $checkuser = User::where('email', $create['email'])->first();

            if ($checkuser) {
                $checkuser->facebook_id = $create['facebook_id'];
                $checkuser->save();
                Auth::login($checkuser);
                $this->authenticated();
                return Redirect::intended($this->redirectPath());

            } else {
                $userModel = new User;
                $createdUser = $userModel->addNew($create);
                Auth::loginUsingId($createdUser->id);
                $this->authenticated();
                return Redirect::intended($this->redirectPath());
            }

        } catch (InvalidStateException $e) {
            return redirect('auth/facebook');
        } catch (\Exception $e) {
            return redirect('auth/facebook');
        }

    }
}
