@if (session('success'))
    {{ session('success') }}
@elseif(session('error'))
    {{ session('error') }}
@endif
<form action="{{ route('order_test') }}" method="post">
    @csrf
    {{-- <select name="" id="">
        <option value="0" name="pay">COD</option>
        <option value="1" name="pay">vnpay</option>
        <option value="2" name="pay">momo</option>
    </select> --}}
    <button type="submit" name="payUrl" value="2">VNPay</button>
    <button type="submit" name="payUrl" value="3">MOMO</button>
    <button type="submit">gửi</button>
</form>
