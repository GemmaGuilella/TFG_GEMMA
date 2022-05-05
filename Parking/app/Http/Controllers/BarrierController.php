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
    // Associar-lo a una plaça lliure quan la barrera es vol posar a
    // oberta

    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function open(UpdateRequest $request)
    {
        // Desencriptar codi QR
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
        // $space == null ? dd('No està a dins -> ENTRANT') : dd('Està a dins -> SORTINT');
        // Mirem si la plaça és buida (null)
        // si ho és, busquem la primera plaça que està null i li associem el cotxe
        // return [$space === null ? "no a dins" : "a dins"];
        if (!$car->isParked()) {
            $space = Space::available()->first();
            if ($space === null) {
                abort(409, 'No hi han places disponibles'); // conflice, no tenim places lliures!
            }
            $space->car()->associate($car);
            $space->save();
            // 1ra: El QR hauria de tenir un token invalidable. Comprovo si aquest token està bé i l'invalido.
            // Afegir columne de token a la BD de cars.
            // El codi QR que hi hagi la ID i el token.
            // Amb la id del cotxe tinc el token de la BD la comparo amb el token que m'ha arribat
            // si es aixi obro barreres
            // return ["Plaça adjudicada a:", $space->id]; // fer proves DELETE
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER')]);
            $pusher->trigger('my-channel', 'my-event', ['car_id' => $car->id, 'user_id' => $car->user->id]);

            $car->logs()->create([
                'state' => LogState::Enter,
                'price' => null,
                'payment_id' => null,
            ]);

            return response()->noContent();
            // un no content es un 204 (ha anat be) per tant LED VERD (aixo python)
        }
        // Si la plaça no és Null, voldrem sortir, per tant li posem com a Null
        // Generar el codi qr per sortir si ha pagat
        $space = $car->space;
        $space->car()->disassociate($car);
        $space->save();
        $car->logs()->create([
            'state' => LogState::Exit,
            'price' => null,
            'payment_id' => null,
        ]);
        // return ["Sortint", $space];
        return response()->noContent();
    }
}
