<x-mail::message>
# Order thành công

Cảm ơn bạn đã mua hàng.

{{-- <x-mail::button :url="''">
Button Text
</x-mail::button> --}}

@component('mail::table')
    | STT       | Sản phẩm         | Số lượng         | Giá  |
    | ------------- |:-------------:|:-------------:| --------:|
    @if (isset($order))
        @foreach ($orderItem as $key => $item)
            @if ($item->meal_id)
                | {{$key}}      | {{$item->meal->name}}      | {{$item->meal->serving_size * $item->quantity}} grams      | {{$item->total_price}} vnđ      |
            @else
                | {{$key}}      | {{$item->ingredient->name}}      | {{$item->ingredient->serving_size * $item->quantity}} grams      | {{$item->total_price}} vnđ      |
            @endif
        @endforeach
    @endif
    | Tổng giá thành      |       |       | {{$order->total_price}} vnđ      |
@endcomponent

Thanks,<br>
Healthy Food Store
</x-mail::message>
