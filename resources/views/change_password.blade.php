<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coldblooded Admin Login</title>
    <link rel="stylesheet" href="{{asset('auth.css')}}">
</head>
<body>
    <section id="login">
        <h1>Log In</h1>
        <form id="login_form" method="POST" action="/users/must_change_password">
            @csrf

            <div class="image_container">
                <img src="https://media.themcoldbloodeddrifters.com/assets/admin/adminlogo.png" alt="">
            </div>
            <div class="password">
                <input type="password" name="current_password" placeholder="Current Password">
                <img src="{{asset('assets/siteimg/show_pass.png')}}" class="show_button" id="show_password" onclick="toggle_show_pass()">
            </div>
            <div class="password">
                <input type="password" name="password" placeholder="New Password" required />
                <img src="{{asset('assets/siteimg/show_pass.png')}}" class="show_button" id="show_password" onclick="toggle_show_pass()">
            </div>
            <input type="password" name="password_confirmation" placeholder="Confirm New Password" required /><br/>
            <button type="submit">Log In</button>
        </form>
        @if ($errors->any())
            <p id="login_error" style="color:red; position:absolute;z-index:10;bottom:3rem;display:flex;justify-content:center;align-items:center;">
                {{ $errors->first() }}
            </p>
        @endif
    </section>
    <script type="module">
    import { passwordStrength } from 'https://unpkg.com/check-password-strength?module';

    const form = document.getElementById('login_form');
    const passwordInput = form.querySelector('input[name="password"]');
    const confirmInput = form.querySelector('input[name="password_confirmation"]');
    const errorEl = document.getElementById('login_error');

    form.addEventListener('submit', (e) => {
        errorEl.textContent = "";

        const password = passwordInput.value.trim();
        const confirm = confirmInput.value.trim();

        if (password.length < 12) {
            e.preventDefault();
            errorEl.textContent = "Password must be at least 12 characters long.";
            return;
        }

        // Check match
        if (password !== confirm) {
            e.preventDefault();
            errorEl.textContent = "Passwords do not match.";
            return;
        }

        const strength = passwordStrength(password).value;
        if (strength === "Too weak" || strength === "Weak") {
            e.preventDefault();
            errorEl.textContent = "Password is too weak. Please make it stronger.";
            return;
        }

        
    });
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