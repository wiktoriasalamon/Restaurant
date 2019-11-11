<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\JWTAuth;

class ApiUserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * require: name, surname, email, address, phone, password
     *
     * @param Request $request
     * @return Response
     */
    public function store(UserCreateRequest $request)
    {
        try {
            $user = new User();
            $user = $this->fillUser($user, $request);
            $user->password = Hash::make($request->password);
            $user->assignRole("customer");
            $user->save();
        } catch (Exception $e) {
            return response()->json([ 'message' => 'Wystąpił błąd w trakcie dodawania użytkownika'], 500);
        }
        return response()->json(['message' => "Pomyślnie dodano użytkownika"], 200);
    }

    /**
     * Store a newly created resource in storage.
     * require: name, surname, email, address, phone, password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWorker(UserCreateRequest $request)
    {
        try {
            $user = new User();
            $user = $this->fillUser($user, $request);
            $user->password = Hash::make($request->password);
            $user->assignRole("worker");
            $user->save();
        } catch (\Exception $e) {
            return response()->json([ 'message' => 'Wystąpił błąd w trakcie dodawania użytkownika'], 500);
        }
        return response()->json(['message' => "Pomyślnie dodano użytkownika"], 200);
    }
    /**
     * Fill users fields using request
     *
     * @param User $user
     * @param Request $request
     * @return User
     */
    private function fillUser(User $user, Request $request) {
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone = $request->phone;
        if (!$user->remember_token) {
            $user->remember_token = Str::random(10);
        }
        return $user;
    }

    /**
     * Change users password
     * require: oldPassword, newPassword, newPasswordRepeated
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function changePassword(UserChangePasswordRequest $request, User $user)
    {
        if (Hash::check($request->oldPassword, $user->password)) {
            $user->password = Hash::make($request->newPassword);
            $user->save();
            return response()->json(['message' => "Pomyślnie zmieniono hasło"], 200);
        }
        return response()->json([ 'message' => 'Wystąpił błąd w trakcie zmiany hasła'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
            'address' => $user->address,
            'phone' => $user->phone
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * require: name, surname, email, address, phone
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $user = $this->fillUser($user, $request);
            $user->save();
        } catch (Exception $e) {
            return response()->json([ 'message' => 'Wystąpił błąd w trakcie edycji użytkownika'], 500);
        }
        return response()->json(['message' => "Pomyślnie zmieniono dane użytkownika"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id)
    {
        //
    }

    public function myAccount(){
        $user = Auth::user();
        return response()->json($user, 200);
    }
}
