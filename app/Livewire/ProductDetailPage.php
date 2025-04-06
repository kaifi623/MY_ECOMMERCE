<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - DCodeMania')]
class ProductDetailPage extends Component
{

    public $slug;

    public $quantity=1;

    public function increaseQty(){
       $this->quantity++;
    }

    public function decreaseQty(){
        if ($this->quantity>1) {
            $this->quantity--;
    }
        }

        // add to cart method
    public function addToCart($product_id){
        $total_count=CartManagment::addItemsToCartWithQty($product_id,$this->quantity);

        $this->dispatch('update-cart-count', total_count : $total_count)->to(Navbar::class);


          // Trigger SweetAlert
          $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'Product added to Cart Successfully.',
            'timer' => 3000
        ]);
    }

       
     
    public function mount($slug){
        $this->slug=$slug;

    }
    public function render()
    {
        return view('livewire.product-detail-page',[
            'product' => Product::query()->where('slug', $this->slug)->firstOrFail(),
        ]);
    }
}
