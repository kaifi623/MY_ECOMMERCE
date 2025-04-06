<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagment;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{

    public $total_count = 0;

    public function mount()
    {
        $cartItems = CartManagment::getCartItemsFromCookie();
    
        // Ensure $cartItems is an array by decoding JSON if needed
        if (is_string($cartItems)) {
            $cartItems = json_decode($cartItems, true) ?? [];
        }

        if (!is_array($cartItems)) {
            $cartItems = [];
        }
        $this->total_count = count($cartItems);
    }
        #[On('update-cart-count')]
        public function updateCartCount($total_count){
            $this->total_count=+$total_count;
        }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
