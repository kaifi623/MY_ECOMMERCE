<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsPage extends Component
{


    use WithPagination;


    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brand = [];

    #[Title('Products DCodeMania')]

    #[Url]
    public $featured;

    #[Url]
    public $on_sale;

    #[Url]
    public $price_range = 300000;

    #[Url]
    public $sort = 'latest';

    // add to cart method
    public function addToCart($product_id)
    {
        $total_count = CartManagment::addItemsToCart($product_id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);


        // Trigger SweetAlert
        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'Product added to Cart Successfully.',
            'timer' => 3000
        ]);
    }

    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);

        if (!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }
        $brands = Brand::query()->where('is_active', 1)->get(['id', 'name', 'slug']);

        if (!empty($this->selected_brand)) {
            $productQuery->whereIn('brand_id', $this->selected_brand);
        }

        if ($this->featured) {
            $productQuery->where('is_featured', 1);
        }

        if ($this->on_sale) {
            $productQuery->where('on_sale', 1);
        }

        if ($this->price_range) {
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }
        if ($this->sort == 'latest') {
            $productQuery->latest();
        }

        if ($this->sort == 'price') {
            $productQuery->orderBy('price');
        }

        $categories = Category::query()->where('is_active', 1)->get(['id', 'name', 'slug']);
        return view('livewire.products-page', [
            'products' => $productQuery->paginate(6),
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }
}
