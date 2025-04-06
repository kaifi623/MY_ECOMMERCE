<x-mail::message>
# Order placed Succesfully
Thank you for your Order . Your order number is : {{ $order->id }}.

<x-mail::button :url="$url">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
