<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('cart - DCodeMania')]
class CartPage extends Component


{
    public $cart_items = [];
    public $grand_total;

    


    public function mount()
    {
        $this->cart_items =  CartManagment::getCartItemsFromCookie();
        $this->grand_total = CartManagment::calculateGrandTotal($this->cart_items);
    }

    public function removeItem($product_id){
        $this->cart_items = CartManagment::removeCartItems($product_id);
        $this->grand_total = CartManagment::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count',total_count: count($this->cart_items))->to(Navbar::class);
    }

    public function increaseQty($product_id){
        $this->cart_items=CartManagment::incrementQuantityToCartItem($product_id);
        $this->grand_total=CartManagment::calculateGrandTotal($this->cart_items);
    }

    public function decreaseQty($product_id){
        $this->cart_items=CartManagment::decrementQuantityToCartItem($product_id);
        $this->grand_total=CartManagment::calculateGrandTotal($this->cart_items);
    }
    

    public function render()
    {
        return view('livewire.cart-page');
    }
}
