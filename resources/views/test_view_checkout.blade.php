<form action="{{ route('order_test') }}" method="post">
    @csrf
    {{-- <select name="" id="">
        <option value="0" name="pay">COD</option>
        <option value="1" name="pay">vnpay</option>
        <option value="2" name="pay">momo</option>
    </select> --}}
    <button type="submit" name="payUrl" value="3">MOMO</button>
    <button type="submit">gửi</button>
</form>