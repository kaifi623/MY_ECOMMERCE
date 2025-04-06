<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    public function mount(){
        $cart_items=CartManagment::getCartItemsFromCookie();
        if (empty($cart_items)) {
            return redirect()->route('products');
        }
    }

    public function placeOrder(){
       
        $this->validate([
            'first_name'  => 'required',
            'last_name'  => 'required',
            'phone'  => 'required',
            'street_address'  => 'required',
            'city'  => 'required',
            'state'  => 'required',
            'zip_code'  => 'required',
            'payment_method'  => 'required',
        ]);

        $cart_items= CartManagment::getCartItemsFromCookie();

        $line_items = [];

        foreach ($cart_items as $item) {
            // code 
            $order_items[] = [
                'product_id' => $item['id'], // Ensure this exists
                'quantity' => $item['quantity'],
                'unit_amount' => $item['unit_amount'],
                'total_amount' => $item['unit_amount'] * $item['quantity'],
            ];
            //   code

            $line_items[]=[
                'price_data' =>[
                    'currency' => 'pkr',
                    'unit_amount' => $item['unit_amount'] * 100 ,
                    'product_data' => [
                        'name' => $item['name'],
                    ]
                    ],
                'quantity' => $item['quantity'],
            ];
        }

        // instant of order
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->grand_total=CartManagment::calculateGrandTotal($cart_items);
        $order->payment_method=$this->payment_method;
        $order->payment_status='pending';
        $order->status='new';
        $order->currency='pkr';
        $order->shipping_amount=0;
        $order->shipping_method=$this->payment_method;
        $order->notes='order place by'. Auth::user()->name;

        //instant of address
        $address= new Address();
        $address->order_id=$order->id;
        $address->first_name=$this->first_name;
        $address->last_name=$this->last_name;
        $address->phone=$this->phone;
        $address->street_address=$this->street_address;
        $address->city=$this->city;
        $address->state=$this->state;
        $address->zip_code=$this->zip_code;


        $redirect_url='';

        if ($this->payment_method=='stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessinCheckOut=Session::create([
               'payment_method_types' => ['card'],
               'customer_email' => Auth::user()->email,
               'line_items' => $line_items,
               'mode' => 'payment',
             'success_url' =>  route('success').'?session_id={CHECKOUT_SESSION_ID}',
               'cancel_url' =>  route('cancel'),
            ]);
            $redirect_url=$sessinCheckOut->url;
        } else {
            $redirect_url=route('success');
        }

        $order->save();
        $address->order_id=$order->id;
        $address->save();
        $order->items()->createMany($order_items);
        CartManagment::clearCartItems();
        Mail::to(request()->user())->send(new OrderPlaced($order));
        return redirect($redirect_url);
        
    }
    
    public function render()
    {
        
        $cart_items = CartManagment::getCartItemsFromCookie();
        $grand_total = CartManagment::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page',[
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}
