namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        return view('payment');
    }

    public function processPayment(Request $request)
    {
        // Validate the request
        $request->validate([
            'stripeToken' => 'required',
        ]);

        // Set Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a charge
            $charge = Charge::create([
                'amount' => 5000, // Amount in cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Payment Description',
            ]);

            // Handle successful payment
            return redirect()->route('payment.form')->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            // Handle error
            return redirect()->route('payment.form')->with('error', $e->getMessage());
        }
    }
}
