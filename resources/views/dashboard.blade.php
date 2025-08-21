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
            test messages
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
                                @if ($user->username != 'admin')
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