<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Models\PasswordResets;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Response;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * @param $token
     * @return $this
     */
    public function showResetForm($token)
    {
        if (!PasswordResets::where('token', $token)->get('email')->first()) {
            return response(null, Response::HTTP_FORBIDDEN);
        }
        return view('auth.passwords.reset', ['token' => $token]);
    }


    /**
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response
     */
    public function reset(ResetPasswordRequest $request)
    {
        try {
            if (!$passwordResets = PasswordResets::where('token', $request->token)->first()) {
                return response(null, Response::HTTP_FORBIDDEN);
            }
            $email=$passwordResets->email;
            $user=User::where('email',$email)->first();
            $user->setPassword($request->newPassword);
            $passwordResets->delete();
            if ($user->update()) {
                return response()->json(["Pomyślnie zmieniono hasło"], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['Wystąpił błąd w trakcie zmiany hasła'], 500);
        }
    }
}
