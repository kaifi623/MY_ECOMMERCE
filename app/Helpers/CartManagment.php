<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;
use NumberFormatter;


class CartManagment
{

    function formatCurrency($amount, $currency = 'PKR')
{
    $formatter = new NumberFormatter('en_PK', NumberFormatter::CURRENCY);
    return $formatter->formatCurrency($amount, $currency);
}
    // add items to cart
    // static public function addItemsToCart($product_id)
    // {
    //     $cart_items = self::getCartItemsFromCookie();

    //     if (is_string($cart_items)) {
    //         $cart_items = json_decode($cart_items, true);
    //     }
    //     if (!is_array($cart_items)) {
    //         $cart_items = [];
    //     }


    //     $existing_item = null;

    //     foreach ($cart_items as $key => $item) {
    //         if ($item['id'] == $product_id) {
    //             $existing_item = $key;
    //             break;
    //         }
    //     }

    //     if ($existing_item !== null) {
    //         $cart_items[$existing_item]['quantity']++;
    //         $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
    //             $cart_items[$existing_item]['unit_amount'];
    //     } else {
    //         $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
    //         if ($product) {
    //             $cart_items[] = [
    //                 'id'       => $product_id,
    //                 'name'     => $product->name,
    //                 'images'   => $product->images,
    //                 'quantity' => 1,
    //                 'unit_amount' => $product->price,
    //                 'total_amount' => $product->price,
    //             ];
    //         }
    //     }
    //     self::addCartItemsToCookie($cart_items);
    //     return count($cart_items);
    // }

    // AI generated code 
    public static function addItemsToCart($product_id)
{
    $cart_items = self::getCartItemsFromCookie();

    // Ensure cart items are decoded properly
    if (is_string($cart_items)) {
        $cart_items = json_decode($cart_items, true);
    }
    if (!is_array($cart_items)) {
        $cart_items = [];
    }

    // Check if the product already exists in the cart
    $existing_item_key = array_search($product_id, array_column($cart_items, 'id'));

    if ($existing_item_key !== false) {
        // If product exists, increase quantity
        $cart_items[$existing_item_key]['quantity']++;
        $cart_items[$existing_item_key]['total_amount'] = $cart_items[$existing_item_key]['quantity'] * $cart_items[$existing_item_key]['unit_amount'];
    } else {
        // If product does not exist, add a new entry
        $product = Product::find($product_id, ['id', 'name', 'price', 'images']);
        
        if ($product) {
            $cart_items[] = [
                'id'           => $product->id,
                'name'         => $product->name,
                'images'       => $product->images,
                'quantity'     => 1,
                'unit_amount'  => $product->price,
                'total_amount' => $product->price,
            ];
        }
    }

    // Update cart in cookie
    self::addCartItemsToCookie($cart_items);

    // Return total cart item count
    return count($cart_items);
}




    // add items to cart with quantity
    static public function addItemsToCartWithQty($product_id, $qty = 1)
    {
        $cart_items = self::getCartItemsFromCookie();
        

        if (is_string($cart_items)) {
            $cart_items = json_decode($cart_items, true);
        }
        if (!is_array($cart_items)) {
            $cart_items = [];
        }


        $existing_item = null;

        foreach ($cart_items as $key => $item) {
            if ($item['id'] == $product_id) {
                $existing_item = $key;
                break;
            }
        }

        if ($existing_item !== null) {
            $cart_items[$existing_item]['quantity'] = $qty;
            $cart_items[$existing_item]['total_amount'] = $cart_items[$existing_item]['quantity'] *
                $cart_items[$existing_item]['unit_amount'];
        } else {
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if ($product) {
                $cart_items[] = [
                    'id'       => $product_id,
                    'name'     => $product->name,
                    'images'   => $product->images,
                    'quantity' => $qty,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price,
                ];
            }
        }
        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    // remove items from cart
    static public function removeCartItems($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }
        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // add cart item to cookie
    static public function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 7); // 7 days

    }

    // clear cart items from cookie
    static public function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    // get all cart items from cookie
    static public function getCartItemsFromCookie()
    {
        // $cart_items = json_encode(Cookie::get('cart_items'), true);

        // if (!$cart_items) {
        //     $cart_items = [];
        // }

        $cart_items = Cookie::get('cart_items');
        $cart_items = $cart_items ? json_decode($cart_items, true) : [];

        return $cart_items;
    }

    // increment items quantity
    static public function incrementQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['id'] == $product_id) {
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                    $cart_items[$key]['unit_amount'];
            }
        }
        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // decrement items quantity
    static public function decrementQuantityToCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['id'] == $product_id) {
                if ($cart_items[$key]['quantity'] > 1) {
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] *
                        $cart_items[$key]['unit_amount'];
                }else {
                    unset($cart_items[$key]);
                }
            }
        }
        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }


    // calculate grand total
    static public function calculateGrandTotal($items)
    {
        // Decode the first level
        $itemsArray = $items;

        // If still a string, decode again
        if (is_string($itemsArray)) {
            $itemsArray = json_decode($itemsArray, true);
        }

        // Check if decoding was successful
        if (!is_array($itemsArray)) {
            dd('Invalid JSON format:', $items);
        }

        // Sum the 'total_amount' values
        return array_sum(array_column($itemsArray, 'total_amount'));
    }
}
