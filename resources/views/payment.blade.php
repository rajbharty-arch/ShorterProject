@extends('header')

@section('content')
<h2>Processing</h2>

<button onclick="startPayment()">Pay with Razorpay</button>
@endsection

@section('script')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
const order = @json($order->toArray());
console.log(typeof(order));
function startPayment() {
    var options = {
        key: "{{ env('RAZOR_PAY_ID') }}",
        amount: order.amount,
        currency: order.currency,
        name: "Your Company Name",
        description: "Payment for your order",
        image: "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
        order_id: order.id,
        theme: {
            color: "#738276"
        },
        callback_url: "{{route('paymentCallback')}}"
    };

    var rzp = new Razorpay(options);
    rzp.open();
}
</script>
@endsection