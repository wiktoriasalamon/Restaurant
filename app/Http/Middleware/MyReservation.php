<?php

namespace App\Http\Middleware;

use App\Models\Reservation;
use Closure;
use Illuminate\Support\Facades\Auth;

class MyReservation
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $reservation = Reservation::findOrFail($request->route('id'));
        } catch (\Exception $exception) {
            abort(404, 'Not found');
        }
        $auth = Auth::user();
        if ($auth->hasPermissionTo('reservationShow')) {
            return $next($request);
        }

        if ($auth->email !== $reservation->email) {
            abort(403, 'Access denied');
        }
        return $next($request);

    }

}