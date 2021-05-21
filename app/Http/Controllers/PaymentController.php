<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;

class PaymentController extends Controller
{
    // public function index()
    // {
    //     return view('payment');
    // }
    // public function makePayment(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     Stripe\Charge::create ([
    //             "amount" => 150 * 100,
    //             "currency" => "eur",
    //             "source" => $request->stripeToken,
    //             "description" => "Make payment and chill." 
    //     ]);
  
    //     Session::flash('success', 'Payment successfully made.');
          
    //     return back();
    // }

    public function stripe()
    {
        return view('payment2');
    }
    public function payStripe(Request $request)
    {
        $this->validate($request, [
            'card_no' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'cvv' => 'required',
        ]);
 
        $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $response = Token::create(array(
                "card" => array(
                    "number"    => $request->input('card_no'),
                    "exp_month" => $request->input('expiry_month'),
                    "exp_year"  => $request->input('expiry_year'),
                    "cvc"       => $request->input('cvc')
                )));
            if (!isset($response['id'])) {
                return redirect()->route('addmoney.paymentstripe');
            }
            $charge = Charge::create([
                'card' => $response['id'],
                'currency' => 'jpy',
                'amount' =>  100 * 100,
                'description' => 'wallet',
            ]);
 
            if($charge['status'] == 'succeeded') {
                return redirect('stripe')->with('success', 'Payment Success!');
 
            } else {
                return redirect('stripe')->with('error', 'something went to wrong.');
            }
 
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
 
    }
}
