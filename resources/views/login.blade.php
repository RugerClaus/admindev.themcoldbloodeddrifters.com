<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('auth.css')}}">
    <title>Coldblooded Admin Login</title>
</head>
<body>
    <section id="login">
        <h1>Log In</h1>
        <form id="login_form" method="POST" action="/authenticate">
            @csrf

            <div class="image_container">
                <img src="https://media.themcoldbloodeddrifters.com/assets/admin/adminlogo.png" alt="">
            </div>

            <input type="text" name="username" placeholder="Username" required />
            <div class="password">
                <input id="password_field" type="password" name="password" placeholder="Password" required />
                <img src="{{asset('assets/siteimg/show_pass.png')}}" class="show_button" id="show_password" onclick="toggle_show_pass()">
            </div>
            <button type="submit">Log In</button>
        
        </form>
        @if ($errors->any())
            <p id="login_error" style="color:red; position:absolute;z-index:10;bottom:3rem;display:flex;justify-content:center;align-items:center;">
                {{ $errors->first() }}
            </p>
        @endif
    </section>
    <script>
        const show_pass_button = document.getElementById('show_password');
        const passfield = document.getElementById('password_field');

        function toggle_show_pass() {
            if (passfield.type === 'password') {
                passfield.type = 'text';
                show_pass_button.src = "{{ asset('assets/siteimg/hide_pass.png') }}";
            } else {
                passfield.type = 'password';
                show_pass_button.src = "{{ asset('assets/siteimg/show_pass.png') }}";
            }
        }
    </script>
</body>
</html>