<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Models\PasswordResets;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Log;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            (new PasswordResets())->sendResetEmail($request->email);
            return response("Mail resetujący hasło został wysłany.", 200);
        } catch (\Exception $e) {
            Log::notice("Error sending mail:" . $e);
            Log::notice("Error sending mail:" . $e->getMessage());
            Log::notice("Error sending mail:" . $e->getCode());
            return response(trans('Wystąpił błąd podczas wysyłania maila.'), 500);
        }
    }
}
