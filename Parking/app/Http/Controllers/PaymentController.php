<?php

namespace App\Http\Controllers;

use App\Enums\LogState;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Car;
use App\Models\Log;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('pay');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Car $car
     * @return void
     */
    public function pay(PaymentRequest $request, Car $car)
    {
        $attributes = $request->validated();
        $centims = Settings::first()->priceMinute() * $car->space->minutesParked();

        return new PaymentResource([
            'url' => $request->user()->checkoutCharge($centims === 0 ? 1 : ceil($centims), 'Parking', 1, [
                'success_url' => route('checkout.success', ['redirect' => $attributes['success_url']]) . '&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.error', ['redirect' => $attributes['cancel_url']]),
                'metadata' => ['car_id' => $car->id],
            ])->asStripeCheckoutSession()->url,
        ]);
    }

    public function success(Request $request)
    {
        $session = null;

        try {
            $session = Cashier::stripe()->checkout->sessions->retrieve($request->get('session_id'));
        } catch (\Exception $e) {
            abort(400, "There was an issue with the stripe session");
        }

        $car = Car::findOrFail($session->metadata->car_id);

        $car->update(['token' => Str::random(16), 'token_created_at' => now()]);

        $car->logs()->create([
            'state' => LogState::Payment,
            'price' => $session->amount_total,
            'payment_id' => $session->payment_intent,
        ]);

        return redirect($request->query('redirect'));
    }

    public function error(Request $request, Car $car)
    {
        return redirect($request->query('redirect'));
    }
}
