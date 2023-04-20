<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body>
<div style="position: relative;">
    <p style="font-size: 16px;">
        Xin chào {{ $user->last_name }},
    </p>
    <p style="font-size: 16px;">
        Bạn đã đăng ký tài khoản học viên tại <a href="englishcentercuatui.com.vn">englishcentercuatui.com.vn</a>. Kích
        hoạt tài khoản đăng nhập hệ thống
        <a style="font-size: 16px; font-weight: bold; text-align: center; background-color: red; color: white;
            border: none; border-radius: 5px; padding: 10px 20px; text-decoration: none;" href="{{ $user->activation_link }}">
            tại đây
        </a>.
    </p>
    <br>
    <p style="font-size: 16px; font-weight: bold">Mọi thắc mắc xin liên hệ:</p>
    <p style="color: dodgerblue; font-weight: bold; font-size: 16px">Hotline: 1900 1000</p>
</div>
</body>
</html>
