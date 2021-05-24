<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Coupon</title>
</head>
<body>
    <h1>List coupon</h1>
    <div>
        <h3 class="create">Create coupon</h3>
        <form action="{{route('create.coupon')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <button type="submit" class="btn btn-info">Create coupon</button>
        </form>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>currency</th>
                <th>duration</th>
                <th>duration_in_months</th>
                <th>max_redemptions</th>
                <th>percent_off</th>
                <th>redeem_by</th>
                <th>name</th>
                <th>valid</th>
            </tr>
        </thead>
        <tbody>
        @if (!empty($coupons))
            @foreach ($coupons as $coupon)
                <tr>
                    <th>{{$coupon->id}}</th>
                    <th>{{$coupon->currency}}</th>
                    <th>{{$coupon->duration}}</th>
                    <th>{{$coupon->duration_in_months}}</th>
                    <th>{{$coupon->max_redemptions}}</th>
                    <th>{{$coupon->percent_off}}</th>
                    <th>{{$coupon->redeem_by}}</th>
                    <th>{{$coupon->name}}</th>
                    <th>{{$coupon->valid}}</th>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</body>
</html>