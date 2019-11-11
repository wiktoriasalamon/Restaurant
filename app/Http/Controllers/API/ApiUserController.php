<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiUserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = new User();
            $user = $this->fillUser($user, $request);
            $user->password = Hash::make($request->password);
            $user->save();
        } catch (\Exception $e) {
            return response()->json([ 'message' => 'Wystąpił błąd w trakcie dodawania użytkownika'], 500);
        }
        return response()->json(null, 201);
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
        return $user;
    }

    /**
     * Change users password
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request, User $user)
    {
        if (Hash::check($request->oldPassword, $user->password)) {
            $user->password = Hash::make($request->newPassword);
            return response()->json(null, 201);
        }
        return response()->json([ 'message' => 'Wystąpił błąd w trakcie zmiany hasła'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $user = $this->fillUser($user, $request);
            $user->save();
        } catch (\Exception $e) {
            return response()->json([ 'message' => 'Wystąpił błąd w trakcie dodawania użytkownika'], 500);
        }
        return response()->json(null, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }
}
