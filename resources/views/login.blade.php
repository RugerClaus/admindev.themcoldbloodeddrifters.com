<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coldblooded Admin Login</title>
</head>
<body>
    <style>
        *, *::before,*::after {
            text-decoration: none;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
            font-family: Helvetica;
        }
        section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-width: 100vw;
            height: 100vh;
            gap: 2rem;
        }
        section form {
            width: 30rem;
            height: 30rem;
            background-color: lightblue;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            align-items: center;
        }

        section form input {
            font-size: 2rem;
        }

        section form button {
            font-size: 2rem;
            background-color: blue;
            border: 2px solid darkblue;
            color: white;
            border-radius: 1rem;
            padding: 5px
        }

        section form .image_container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        section form .image_container img {
            max-width: 80%;
            object-fit: contain;
        }
        @media(max-width:800px)
        {
            section form {
                width: 90%;
            }

            section form input {
                font-size: 1.8rem;
            }
        }
        
    </style>
    <section id="login">
        <h1>Log In</h1>
        <form id="login_form" method="POST" action="/authenticate">
            @csrf

            <div class="image_container">
                <img src="https://media.themcoldbloodeddrifters.com/assets/admin/adminlogo.png" alt="">
            </div>

            <input type="text" name="username" placeholder="Username" required /><br/>
            <input type="password" name="password" placeholder="Password" required /><br/>
            <button type="submit">Log In</button>
        </form>
        <p id="login_error" style="color:red;"></p>
    </section>

</body>
</html>