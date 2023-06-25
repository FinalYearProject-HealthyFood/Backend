<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>

<body>
    <h2>Đặt lại mật khẩu của bạn</h2>
    <p>Xin chào, {{ $email }}</p>
    <p>Gần đây bạn đã yêu cầu đặt lại mật khẩu của mình. Vui lòng sử dụng mã PIN sau để tiến hành đặt lại mật khẩu:</p>
    <h3>{{ $pin }}</h3>
    <p>Mã PIN này sẽ hết hạn sau 60 phút.</p>
    <p>Nếu bạn không bắt đầu thiết lập lại mật khẩu này thì không cần thực hiện thêm hành động nào.</p>

    <p>Trân trọng,<br>Healthy Food Store</p>
</body>

</html>
