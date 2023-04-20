/*jshint debug:true */


$(document).ready(function () {
    let email = $('#email');
    let password = $('#password');
    let confirmPassword = $('#confirmed');

    let currentDate = new Date();
    let maxDate = new Date(currentDate.getFullYear() - 5, currentDate.getMonth(), currentDate.getDate());
    let formattedDate = maxDate.toISOString().substring(0, 10);

    // Set the maximum date for the input element
    $("#birthday").attr("max", formattedDate);

    confirmPassword.blur(function () {
        if (confirmPassword.val() !== password.val()) {
            confirmPassword.val('');
            toastr.error('Mật khẩu không khớp');
        }
    });
    // Function to check letters and numbers
    function alphanumeric(text) {
        let letterNumber = /^[a-z0-9A-Z_ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễếệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ@]+$/;
        return letterNumber.test(text);
    }

    password.on("keyup", function (e) {
        if (!alphanumeric(password.val()) && password.val() !== '') {
            toastr.error('Mật khẩu bao gồm số và chữ!');
            password.val('');
            password.focus();
        }
    });

    $('#submitButton').click(function () {
        if ($('#account').val() === '') {
            toastr.error('Tài khoản không hợp lệ');
        }
        $('#email').val(email.val());
    });

    $('#backgroundUrl').on('change', function (event) {
        let newAvt = event.target.files[0];
        let reader = new FileReader();
        reader.onload = (event) => {
            $('#avatarView').attr('src', event.target.result);
        };
        reader.readAsDataURL(newAvt);
    });
});



