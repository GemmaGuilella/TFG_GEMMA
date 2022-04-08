<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Barriers\UpdateRequest;
use App\Http\Resources\BarrierResource;
use App\Models\Barrier;
use App\Models\Car;
use App\Models\Space;
use Illuminate\Support\Facades\Crypt;

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
    public function open(Request $request, Barrier $barrier)
    {
        // Desencriptar codi QR
        $qr = json_decode(Crypt::decryptString($request->qr));
        // dd($carID);
        // Quin cotxe es, no crec que faci falta
        $car = Car::findOrFail($qr->id);

        if ($car->token !== $qr->token && (now()->diffInMinutes($qr->token_created_at) > 5)) {
            abort(406, 'Token invalid');
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
            return response()->noContent();
            // un no content es un 204 (ha anat be) per tant LED VERD (aixo python)
        }
        // Si la plaça no és Null, voldrem sortir, per tant li posem com a Null
        // Generar el codi qr per sortir si ha pagat
        $space = $car->space;
        $space->car()->disassociate($car);
        $space->save();
        // return ["Sortint", $space];
        return response()->noContent();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Barrier $barrier
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Barrier $barrier)
    {
        return new BarrierResource(
            tap($barrier)
                ->update($request->validated()),
        );
    }
}
