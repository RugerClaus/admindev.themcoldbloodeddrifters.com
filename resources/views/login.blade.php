<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <section id="login">
        <h2>Log In</h2>
        <form id="login_form" method="POST" action="/authenticate">
            @csrf
            <input type="text" name="username" placeholder="Username" required /><br/>
            <input type="password" name="password" placeholder="Password" required /><br/>
            <button type="submit">Log In</button>
        </form>
        <p id="login_error" style="color:red;"></p>
    </section>

</body>
</html>