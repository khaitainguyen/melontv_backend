<?php

namespace App\Http\Controllers;

use App\Models\Coupon as ModelsCoupon;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\Coupon;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Subscription;
use Stripe\Token;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

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
    public function coupon()
    {
        $coupons = ModelsCoupon::get();
        return view('coupon', compact('coupons'));
    }
    public function createCoupon(Request $request)
    {
        $coupon = Coupon::create([
            // "amount_off" => null,
            'currency' => "jpy",
            "duration" => "repeating",
            "duration_in_months" => 3,
            "max_redemptions" => 100,
            "name" => "25.5% off",
            // "redeem_by" =>  null,
            "percent_off" => 25,
            // "times_redeemed" => 0,
            // "valid" => true
        ]);
        dd($coupon);
        $data = new ModelsCoupon();
        $data->currency = $coupon->currency;
        $data->duration = $coupon->duration;
        $data->duration_in_months = $coupon->duration_in_months;
        $data->max_redemptions = $coupon->max_redemptions;
        $data->name = $coupon->name;
        $data->percent_off = $coupon->percent_off;
        $data->save();

        return view('coupon');
    }
    public function session()
    {
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => '{{PRICE_ID}}',
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'discounts' => [[
                'coupon' => '{{COUPON_ID}}',
            ]],
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);
    }
    public function update(Request $request)
    {
        $stripe = new StripeClient(
            'sk_test_51ItPdhCnmaP0SoyguZgygHdQCNU2OAaD14UN1WcyO8ysyeH66jsZT3DuIxTU5of41fk3wSOwgWZUQNq8rTEVXKfV00voQhT0fD'
          );
          $stripe->coupons->update(
            'SawDEccv',
            ['name' => '30% sales off',]
          );
            dd($stripe);
    }
    public function show()
    {
        $coupon = Coupon::all();
        dd($coupon);
        
    }
    public function listCustomer()
    {
        $customer = Customer::all();
        dd($customer);
    }
    public function customer()
    {
        $customers = Customer::create([
            'address' => [
                'city' => 'Tokyo',
                'country' => 'JP',
                'line1' => 'Nanda'
                ],
            'email' => 'melon@gmail.com',
            'name' => 'Kawasaki',
            'phone' => '01236688',
            'description' => 'Test customer create'
        ]);
        dd($customers);
    }
    public function paymentMethod()
    {
        $payment = PaymentMethod::create([
            'type' => 'card',
            'card' => [
              'number' => '4242424242424242',
              'exp_month' => 8,
              'exp_year' => 2024,
              'cvc' => '333',
            ],
          ]);
        dd($payment);
    }
    public function attachMethod()
    {
        $stripe = new StripeClient(
            'sk_test_51ItPdhCnmaP0SoyguZgygHdQCNU2OAaD14UN1WcyO8ysyeH66jsZT3DuIxTU5of41fk3wSOwgWZUQNq8rTEVXKfV00voQhT0fD'
          );
          $attach = $stripe->paymentMethods->attach(
            'pm_1IuvtcCnmaP0SoygD7erAplD',
            ['customer' => 'cus_JXxhnArP8xN4WS']
          );
        dd($attach);
    }
    public function subscription()
    {
        $subcription = Subscription::create([
            'customer' => 'cus_JXxhnArP8xN4WS',
            'default_payment_method' => 'pm_1IuvtcCnmaP0SoygD7erAplD',
            'items' => [
                ['price' => 'price_1IuYpxCnmaP0SoygxR2dFHCJ'],
            ],
        ]);
        dd($subcription);
    }
}
