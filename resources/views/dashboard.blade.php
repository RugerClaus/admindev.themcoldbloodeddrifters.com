<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{asset('main.css')}}">
        <title>{{$data['user']->username}} - Dashboard</title>
    </head>
    <body>
        <form action="{{ route('logout') }}" method="POST" class="logout_form">
            @csrf
            <button type="submit">Logout</button>
        </form>

        <nav class="menu page" id="menu">
            @if($data['user']->permission_level != 'admin')
            <button class="change_password_button" id="open_password_modal">Change Password</button>
            <div class="change_password_modal hidden" id="password_modal">
                <form action="/users/user_change_password" method="post" id="change_pass_form">
                    @csrf
                    <div class="password">
                        <input type="password" name="password" placeholder="New Password" required id="password_field"/>
                        <img src="{{asset('assets/siteimg/show_pass.png')}}" 
                            class="show_button" id="show_password" 
                            onclick="toggle_show_pass()" 
                            style="object-fit: contain;">
                    </div>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" style="align-self: flex-start">
                    <button type='submit'>Update Password</button>
                    <button type="button" id="close_modal">Cancel</button>
                </form>
                
            </div>
            <p id="password_error" style="color: red;position: absolute;top:4rem;left:2rem"></p>

            <script type="module">

                @if ($errors->has('password'))
                    const passwordError = @json($errors->first('password'));
                    document.getElementById('password_error').textContent = passwordError;
                @endif

                import { passwordStrength } from 'https://unpkg.com/check-password-strength?module';

                const form = document.getElementById('change_pass_form');
                const passwordInput = form.querySelector('input[name="password"]');
                const confirmInput = form.querySelector('input[name="password_confirmation"]');
                const errorEl = document.getElementById('password_error');

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

                const modal = document.getElementById("password_modal");
                const openBtn = document.getElementById("open_password_modal");
                const closeBtn = document.getElementById("close_modal");

                openBtn.addEventListener("click", () => {
                    modal.classList.remove("hidden");
                    errorEl.textContent = ''
                });

                closeBtn.addEventListener("click", () => {
                    modal.classList.add("hidden");
                });

                modal.addEventListener("click", (e) => {
                    if (e.target === modal) {
                        modal.classList.add("hidden");
                    }
                });
                
            </script>

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
        @endif
            <div class="nav_row">
                <div class="card" data-target="home_editor">
                    <div class="inner_card">
                        <div>Home</div> 
                        <div>Page</div>
                        <div>Editor</div>
                    </div>
                </div>
                <div class="card" data-target="band_bio_editor">
                    <div class="inner_card">
                        <div>Band</div>
                        <div>Bio</div>
                        <div>Editor</div>
                    </div>
                </div>
            </div>
            <div class="nav_row">
                <div class="card" data-target="bio_editor">
                    <div class="inner_card">
                        <div>Member</div>
                        <div>Bio</div>
                        <div>Editor</div>
                    </div>
                </div>
                <div class="card" data-target="messages">
                    <div class="inner_card">
                        <div>Messages</div>
                    </div>
                </div>
            </div>
            <div class="nav_row">
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="card" data-target="user_editor">
                            <div class="inner_card">
                                <div>User</div>
                                <div>Manager</div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </nav>
        <section id="home_editor" class="page hidden" >
            <button class="close_section_button">back to menu</button>
            <section class="carousel" id="carousel_control">
                <img id="loading" src="{{asset('assets/loading.gif')}}" alt="Loading..." style="display:none; width:50px; height:50px;">
                <script src="{{asset('scripts/pages/home_editor.js')}}"></script>
            </section>
            
            test home
        </section>
        <section id="band_bio_editor" class="page hidden">
            <button class="close_section_button"><-- back to menu</button>
            test band bio
        </section>
        <section id="bio_editor" class="page hidden">
            <button class="close_section_button"><-- back to menu</button>
            test bio
        </section>
        <section id="messages" class="page hidden">
            <button class="close_section_button"><-- back to menu</button>
            @foreach ($data['messages'] as $message)
                <div class="message_preview">
                    <div class="from">{{$message->name}}</div>
                </div>
            @endforeach
        </section>
        
        <section id="user_editor" class="page hidden">
            <button class="close_section_button"><-- back to menu</button>
            <br>
            <section class="users">
                <section class="add-user">
                    <form id="add-user-form">
                        @csrf
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit">Add User</button>
                    </form>
                </section>
                    <div class="table-container">
                        <table class="users_table">
                            <tr>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Modify</th>
                            </tr>
                            @foreach ($data['users'] as $user)
                            <tr id="user-{{ $user->id }}">
                                <td class="username">{{ $user->username }}</td>
                                @if ($user->permission_level != 'admin')
                                    <td class="password">**************</td>
                                    <td><button class="edit_user" data-id="{{ $user->id }}">Edit</button></td>
                                    <td><button class="delete_user" data-id="{{ $user->id }}">Delete</button></td>
                                @endif
                            </tr>
                            @endforeach
                        </table>
                    </div>
            </section>
            <script src="{{asset('scripts/pages/user_editor.js')}}"></script>
        </section>
        <script src="{{asset("scripts/main.js")}}"></script>
    </body>
</html>