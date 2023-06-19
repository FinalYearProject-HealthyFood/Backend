<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>

<body>
    <h2>Xác minh địa chị email</h2>
    <p>Xin chào,</p>
    <p>Vui lòng nhấp vào nút bên dưới để xác minh địa chỉ email của bạn.</p>

    <table cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" bgcolor="#3490dc" style="border-radius: 5px;">
                <a href="{{ $verificationUrl }}" target="_blank"
                    style="padding: 10px 20px; color: #ffffff; text-decoration: none; display: inline-block;">Xác nhận
                    Email</a>
            </td>
        </tr>
    </table>

    <p>Nếu bạn không tạo tài khoản, bạn không cần thực hiện thêm hành động nào.</p>

    <p>Trân trọng,<br>Healthy Food Store</p>
</body>

</html>
