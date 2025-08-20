<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{asset('main.css')}}">
        <title>{{$data['user']->username}} - Dashboard</title>
    </head>
    <body>
        <form action="{{ route('logout') }}" method="POST" class="logout_form">
            @csrf
            <button type="submit">Logout</button>
        </form>
        <nav class="menu" id="menu">
            <div class="nav_row">
                <div class="card" data-target="home_editor">
                    <div>Home</div> 
                    <div>Page</div>
                    <div>Editor</div>
                </div>
                <div class="card" data-target="band_bio_editor">
                    <div>Band</div>
                    <div>Bio</div>
                    <div>Editor</div>
                </div>
            </div>
            <div class="nav_row">
                <div class="card" data-target="bio_editor">
                    <div>Member</div>
                    <div>Bio</div>
                    <div>Editor</div>
                </div>
                <div class="card" data-target="messages">
                    <div>Messages</div>
                </div>
            </div>
            <div class="nav_row">
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="card" data-target="member_editor">
                            <div>Add</div>
                            <div>or</div>
                            <div>Remove</div>
                            <div>Members</div>
                        </div>
                    @endif
                @endauth
            </div>
        </nav>
        <section id="home_editor" class="page hidden" >
            <button class="close_section_button">back to menu</button>
            test home
        </section>
        <section id="band_bio_editor" class="page hidden">
            <button class="close_section_button">back to menu</button>
            test band bio
        </section>
        <section id="bio_editor" class="page hidden">
            <button class="close_section_button">back to menu</button>
            test bio
        </section>
        <section id="messages" class="page hidden">
            <button class="close_section_button">back to menu</button>
            test messages
        </section>
        <section id="member_editor" class="page hidden">
            <button class="close_section_button">back to menu</button>
            test member editor
        </section>
        <script src="{{asset("scripts/main.js")}}"></script>
    </body>
</html>