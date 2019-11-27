<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserRequest;
use App\Mails\RegistrationMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class ApiUserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * require: name, surname, email, address, phone, password
     *
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCustomer(UserCreateRequest $request)
    {
        return $this->store($request, "customer");
    }

    /**
     * Store a newly created resource in storage.
     * require: name, surname, email, address, phone, password
     *
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeWorker(UserCreateRequest $request)
    {
        return $this->store($request, "worker");
    }

    /**
     * @param UserCreateRequest $request
     * @param string $role
     * @return \Illuminate\Http\JsonResponse
     */
    private function store(UserCreateRequest $request, string $role)
    {
        try {
            $user = new User();
            $user->fillUser($request);
            $user->setPassword($request->password);
            $user->assignRole($role);
            if($user->save()){
                (new RegistrationMail($request->password, $request->email, $role))->sendMail();
            }
        } catch (\Exception $exception) {
            Log::notice("Error :" . $exception);
            Log::notice("Error :" . $exception->getMessage());
            Log::notice("Error :" . $exception->getCode());
            return response()->json(['message' => 'Wystąpił błąd w trakcie dodawania użytkownika'], 500);
        }
        return response()->json(['message' => "Pomyślnie dodano użytkownika"], 200);
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $user->fillUser($request);
            $user->save();
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json(['message' => 'Wystąpił błąd w trakcie edycji użytkownika'], 500);
        }
        return response()->json(['message' => "Pomyślnie zmieniono dane użytkownika"], 201);
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
            $user->setPassword($request->newPassword);
            $user->save();
            return response()->json(['message' => "Pomyślnie zmieniono hasło"], 200);
        }
        return response()->json(['message' => 'Wystąpił błąd w trakcie zmiany hasła'], 500);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchCustomers()
    {
        return response()->json((new UserService)->getUsersByRole("customer"), 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchWorkers()
    {
        return response()->json((new UserService)->getUsersByRole("worker"), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function fetchUser(User $user)
    {
        return response()->json($user->fetchUserData(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy($id)
    {
        try {
            User::findOrFail($id)->delete();
            return response()->json("Użytkownik został usunięty", 201);
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function myAccount(){
        try {
            if ($user = Auth::user()){
                return response()->json($user, 200);
            }
            return response()->json("unauthenticated", 200);
        } catch (\Exception $e) {
            Log::notice("Error :" . $e);
            Log::notice("Error :" . $e->getMessage());
            Log::notice("Error :" . $e->getCode());
            return response()->json('Wystąpił nieoczekiwany błąd', 500);
        }

    }


}
