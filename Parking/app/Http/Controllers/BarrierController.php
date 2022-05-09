<?php

namespace App\Http\Controllers;

use App\Enums\LogState;
use Illuminate\Http\Request;
use App\Http\Requests\Barriers\UpdateRequest;
use App\Http\Resources\BarrierResource;
use App\Models\Barrier;
use App\Models\Car;
use App\Models\Space;
use Illuminate\Support\Facades\Crypt;
use Pusher\Pusher;
use App\Models\Settings;

class BarrierController extends Controller
{
    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function open(UpdateRequest $request)
    {
        $qr = json_decode(Crypt::decryptString($request->qr));

        $car = Car::findOrFail($qr->id);

        if ($car->token !== $qr->token) {
            abort(406, 'Token invalid');
        }

        if (now()->diffInMinutes($qr->token_created_at) > Settings::first()->token_expiration) {
            $car->update(['token' => null]);
            abort(406, 'Token expired');
        }

        $car->update(['token' => null]);

        if (!$car->isParked()) {
            $space = Space::available()->first();
            if ($space === null) {
                abort(409, 'No hi han places disponibles');
            }
            $space->car()->associate($car);
            $space->save();

            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER')]);
            $pusher->trigger('my-channel', 'my-event', ['car_id' => $car->id, 'user_id' => $car->user->id]);

            $car->logs()->create([
                'state' => LogState::Enter,
                'price' => null,
                'payment_id' => null,
            ]);

            return response()->noContent();
        }

        $space = $car->space;
        $space->car()->disassociate($car);
        $space->save();
        $car->logs()->create([
            'state' => LogState::Exit,
            'price' => null,
            'payment_id' => null,
        ]);

        return response()->noContent();
    }
}
