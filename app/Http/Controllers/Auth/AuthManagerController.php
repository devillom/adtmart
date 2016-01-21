<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use Validator;
use Exception;

class AuthManagerController extends Controller
{
    protected $redirectTo = '/';

    /**
     * Редиректим на нужный провайдер
     * @param $provider
     * @return mixed
     */
    public function oauthLogin($provider)
    {
        return \Socialite::with($provider)->redirect();
    }

    /**
     * Обробатываем данные с провайдера
     * @param Request $request
     * @param $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Redirect
     */
    public function oauthHandle(Request $request, $provider)
    {
        try {

            $user = \Socialite::driver($provider)->user();

        } catch (Exception $e) {
            //@TODO уведомление об ошибке типа что то не так!
            return redirect('/');

        }

        $userDB = User::where('oauth_id', $user->getId())->where('provider', $provider)->first();

        if (!is_null($userDB)) {

            Auth::login($userDB);

            if(Auth::check()){
                //@TODO показываем уведомление об успешной авторизации
                return redirect($this->redirectTo);
            }else{
                //@TODO уведомление об ошибке типа что то не так!
                return redirect('/');
            }


        } else if (!is_null($user->getEmail())) {

            return $this->createAndLogin($user, $provider);

        } else {

            session(['oauth' => $user, 'provider' => $provider]);
            return view('auth.oauth.email');

        }
    }

    /**
     * Регистрируем и авторизуем пользователя
     *
     * @param $oauthData
     * @param $provider
     * @param null $email
     * @return Redirect
     */
    public function createAndLogin($oauthData, $provider, $email = null)
    {
        if (is_null($email))
            $email = $oauthData->getEmail();

        $data = array(
            'nickname' => $oauthData->getNickname(),
            'firstname' => $oauthData->user['first_name'],
            'lastname' => $oauthData->user['last_name'],
            'email' => $email,
            'oauth_id' => $oauthData->getId(),
            'provider' => $provider
        );
        //Авторизация пользователя
        Auth::login(User::create($data));

        if(Auth::check()){
            //@TODO показываем уведомление об успешной авторизации
            return redirect($this->redirectTo);
        }

        //@TODO уведомление об ошибке типа что то не так!
        return redirect('/');

    }

    /**
     * Добавляем Email если отсутствует
     *
     * @param Request $request
     * @return Redirect|void
     */
    public function setEmail(Request $request)
    {
        //Валидация email'a
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users'
        ]);

        if ($validator->fails()) {
            return Redirect::route('oAuthSetEmail')
                ->withErrors($validator)
                ->withInput();
        }

        if (session()->has('oauth') && session()->has('provider')) {

           return $this->createAndLogin(session()->get('oauth'), session()->get('provider'), $request->input('email'));

        }

    }
}
