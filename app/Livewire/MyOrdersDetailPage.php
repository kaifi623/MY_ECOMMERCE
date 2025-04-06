<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\Order_item;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Order Detail')]
class MyOrdersDetailPage extends Component
{
    public $order_id;

    public function mount($order_id){
        $this->order_id = $order_id;
    }
    public function render()
    {
        $order_items= Order_item::with('product')->where('order_id' , $this->order_id)->get();
        $address= Address::where('order_id',$this->order_id)->first();
        $order=Order::where('id',$this->order_id)->first();

        return view('livewire.my-orders-detail-page',[
            'order_items'=>$order_items,
            'address' => $address,
            'order' => $order,
        ]);
    }
}
