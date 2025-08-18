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
        <nav class="menu">
            <div class="nav_row">
                <div class="card">
                    <div>Home</div> 
                    <div>Page</div>
                    <div>Editor</div>
                </div>
                <div class="card">
                    <div>Band</div>
                    <div>Bio</div>
                    <div>Editor</div>
                </div>
            </div>
            <div class="nav_row">
                <div class="card">
                    <div>Member</div>
                    <div>Bio</div>
                    <div>Editor</div>
                </div>
                <div class="card">
                    <div>Messages</div>
                </div>
            </div>
            <div class="nav_row">
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="card">
                            <div>Add</div>
                            <div>or</div>
                            <div>Remove</div>
                            <div>Members</div>
                        </div>
                    @endif
                @endauth
            </div>
        </nav>
    </body>
</html>