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
        <form action="{{ route('logout') }}" method="POST" class="logout_form" id="logout">
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
                @if($data['user']->permission_level == 'user')
                <div class="card" data-target="bio_editor">
                    <div class="inner_card">
                        <div>Bio</div>
                        <div>Editor</div>
                    </div>
                </div>
                @endif
                @if(auth()->user()->isAdmin())
                    <div class="card" data-target="user_editor">
                        <div class="inner_card">
                            <div>User</div>
                            <div>Manager</div>
                        </div>
                    </div>
                @endif
                <div class="card" data-target="messages">
                    <div class="inner_card">
                        <div>Messages</div>
                        <div id="unread_count">({{$data['unread_count']}})</div>
                    </div>
                </div>
            </div>
        </nav>
        <section id="home_editor" class="page hidden" >
            <button class="close_section_button"><-- back to menu</button>
            <section class="carousel" id="carousel_control">
                <div id="carousel_status" class="img_delete_status hidden"></div>

                <section id="carousel_grid" class="carousel_grid">
                    
                </section>
                <div id="carousel_modal" class="carousel_edit_modal">
                    <form id="carousel_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="carousel_id">
                        <div class="text_fields">
                            <input type="file" name="image" id="carousel_image">
                            <input type="text" name="caption" id="carousel_caption" placeholder="Label" required>
                            <textarea name="blurb" id="carousel_blurb" placeholder="Image info" required></textarea>
                            <div style="display:flex;gap:.5rem;justify-content:flex-end;">
                                <button type="submit" class="save_user">Save</button>
                                <button type="button" id="close_carousel_modal" class="edit_user">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="divider_line"></div>

                <div class="edit_home_text_wrapper">
                    <form id="home_left_text">
                        @csrf
                        <label for="left">Update Left/Top Text:</label>
                        <textarea name="left" id="left_text" class="home_text">{{$data['home_text_left']}}</textarea>
                        <button type="submit">Update Left/Top</button>
                    </form>
                    <form id="home_right_text">
                        @csrf
                        <label for="right">Update Right/Bottom Text:</label>
                        <textarea name="right" id="right_text" class="home_text">{{$data['home_text_right']}}</textarea>
                        <button type="submit">Update Right/Bottom</button>
                    </form>
                </div>
                <div id="home_text_status" class="img_delete_status hidden"></div>
                <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const leftForm = document.getElementById("home_left_text");
                    const rightForm = document.getElementById("home_right_text");
                    const statusEl = document.getElementById("home_text_status");
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    function flash(msg) {
                        statusEl.textContent = msg;
                        statusEl.classList.remove('hidden');
                        setTimeout(() => {
                            statusEl.textContent = '';
                            statusEl.classList.add('hidden');
                        }, 2000);
                    }

                    async function handleSubmit(form, url) {
                        form.addEventListener("submit", async (e) => {
                            e.preventDefault();

                            const formData = new FormData(form);

                            try {
                                const res = await fetch(url, {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": csrfToken,
                                        "Accept": "application/json"
                                    },
                                    body: formData
                                });

                                const data = await res.json();

                                if (data.success) {
                                    flash(data.message);
                                } else {
                                    alert("Update failed: " + (data.message || "Unknown error"));
                                }

                            } catch (err) {
                                console.error("Error updating text:", err);
                                alert("An error occurred while updating.");
                            }
                        });
                    }

                    handleSubmit(leftForm, "/home/update_left");
                    handleSubmit(rightForm, "/home/update_right");
                });
                </script>

            </section>
            <script src="{{ asset('scripts/pages/home_editor.js') }}"></script>
        </section>
        <section id="band_bio_editor" class="page hidden">
            <button class="close_section_button"><-- back to menu</button>
            <div id="band_image_status" class="img_delete_status hidden"></div>
            <div class="bio_editor_wrapper">
                <form enctype="multipart/form-data" method="POST" class="band_bio_editor">
                    <div class="band_bio_image">
                        <img src="{{$data['band']->image ?? 'https://placehold.co/1280x720?text=Band+Photo'}}" alt="band image" id="band_image">
                        <button type="button" id="delete_band_image">Delete Image</button>
                    </div>
                    <div class="band_bio_text">
                        <input type="file" name="bio_image" id="band_image_input">

                        <input type="text" name="bio_name" id="band_name" 
                            value="{{$data['band']->name ?? ''}}" placeholder="Band Name">

                        <input type="text" name="bio_list_left_to_right" id="band_list" 
                            value="{{$data['band']->band_list_left_to_right ?? ''}}" placeholder="List (L→R)">

                        <textarea name="bio_text" id="band_bio_text" placeholder="Band bio...">{{$data['band']->bio ?? ''}}</textarea>

                        <input type="text" name="bio_imgalt" id="band_imgalt" 
                            value="{{$data['band']->image_alt ?? ''}}" placeholder="Alt text">

                        <button type="submit">Update Band Bio</button>
                    </div>
                </form>
            </div> 

            <script>
                // Handle image deletion
                document.getElementById('delete_band_image').addEventListener('click', function() {
                    fetch('/band/bio/delete_image', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('band_image').src = 'https://placehold.co/1280x720?text=Band+Photo';
                            document.getElementById('band_image_status').textContent = `${data.message}`;
                            document.getElementById('band_image_status').classList.remove('hidden')
                            setTimeout(() => {
                                document.getElementById('band_image_status').textContent = ``;
                                document.getElementById('band_image_status').classList.add('hidden')
                            }, 2000);
                        } else {
                            alert('Something went wrong.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Failed to delete band image.');
                    });
                });
            </script>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const form = document.querySelector(".band_bio_editor");
                    const bandImg = document.getElementById("band_image");

                    if (!form) return;

                    form.addEventListener("submit", async (e) => {
                        e.preventDefault();

                        const formData = new FormData(form);

                        try {
                            const res = await fetch("/band/bio/update", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                                },
                                body: formData
                            });

                            const data = await res.json();

                            if (data.success) {
                                document.getElementById('band_image_status').textContent = `${data.message}`;
                                document.getElementById('band_image_status').classList.remove('hidden')
                                setTimeout(() => {
                                    document.getElementById('band_image_status').textContent = ``;
                                    document.getElementById('band_image_status').classList.add('hidden')
                                }, 2000);

                                if (data.updated_fields.image) {
                                    bandImg.src = data.updated_fields.image;
                                }

                            } else {
                                alert("Update failed: " + (data.message || "Unknown error"));
                            }

                        } catch (err) {
                            console.error("Error updating band bio:", err);
                            alert("An error occurred while updating.");
                        }
                    });
                });
            </script>
        </section>
        <section id="bio_editor" class="page hidden">
            <button class="close_section_button"><-- back to menu</button>
            <div id="image_deletion_status" class="img_delete_status hidden"></div>
            @auth
                @if($data['user']->permission_level == 'user')
                   <div class="bio_editor_wrapper">
                        <form enctype="multipart/form-data" method="POST" class="member_bio_editor">
                           <div class="member_bio_image">
                                <img src="{{$data['bio']->portrait}}" alt="bio image" id="bio_portrait">
                                <button type="button" id="delete_img">Delete Image</button>
                           </div>
                            <div class="member_bio_text">
                                <input type="file" name="bio_portrait" id="bio_image" value="{{$data['bio']->portrait}}">
                                <input type="text" name="bio_name" id="bio_name" value="{{$data['bio']->name}}">
                                <input type="text" name="bio_instrument" value="{{$data['bio']->instrument}}">
                                <textarea type="text" name="bio_text">{{$data['bio']->bio}}</textarea>
                                <button type="submit">Update Bio</button>
                            </div>
                        </form>
                    </div> 
                    <script>
                        document.getElementById('delete_img').addEventListener('click', function() {

                            fetch('/band_members/delete_portrait', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    
                                    document.getElementById('bio_portrait').src = 'https://placehold.co/300x700';
                                    document.getElementById('image_deletion_status').textContent = `${data.message}`;
                                    document.getElementById('image_deletion_status').classList.remove('hidden')
                                    setTimeout(() => {
                                        document.getElementById('image_deletion_status').textContent = ``;
                                        document.getElementById('image_deletion_status').classList.add('hidden')
                                    }, 2000);
                                } else {
                                    alert('Something went wrong.');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('Failed to delete portrait.');
                            });
                        });
                    </script>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                        const form = document.querySelector(".member_bio_editor");
                        const portraitImg = document.getElementById("bio_portrait");

                        if (!form) return;

                        form.addEventListener("submit", async (e) => {
                            e.preventDefault();

                            const formData = new FormData(form);

                            try {
                                const res = await fetch("/band_members/bio/update", {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                                    },
                                    body: formData
                                });

                                const data = await res.json();

                                if (data.success) {
                                    document.getElementById('image_deletion_status').textContent = `${data.message}`;
                                    document.getElementById('image_deletion_status').classList.remove('hidden')
                                    setTimeout(() => {
                                        document.getElementById('image_deletion_status').textContent = ``;
                                        document.getElementById('image_deletion_status').classList.add('hidden')
                                    }, 2000);

                                    if (data.updated_fields.portrait) {
                                        portraitImg.src = data.updated_fields.portrait;
                                    }

                                } else {
                                    alert("Update failed: " + (data.message || "Unknown error"));
                                }

                            } catch (err) {
                                console.error("Error updating bio:", err);
                                alert("An error occurred while updating.");
                            }
                        });
                    });
                    </script>
                @endif
            @endauth

        </section>
        <section id="messages" class="page hidden">
            <button class="close_section_button"><-- back to menu</button>
            <div class="message_previews">
                <div class="message_preview_wrapper">
                    @foreach ($data['messages'] as $message)
                        <div class="message_preview_overlay">
                            <div class="message_preview {{ $message->read ? 'message_read' : 'message_unread' }}" data-id="{{ $message->id }}">
                                <div class="from"><b>From: </b>{{ $message->name }}</div>
                                <div class="email"><b>Email: </b> {{$message->email}}</div>
                                <div class="phone"><b>Phone: </b> {{$message->phone}}</div>
                                <div class="message"><b>Message: </b>{{ \Illuminate\Support\Str::limit($message->body, 30) }}</div>
                            </div>

                            <div class="overlay_controls">
                                <button class="delete_btn" data-id="{{ $message->id }}">
                                    <img src="{{asset('assets/icons/trash.png')}}" alt="Delete" />
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="message_viewer"></div>
            <div class="mobile_message_viewer"></div>
            <script>
                async function fetchUnreadCount() {
                    try {
                        const res = await fetch('/messages/unread_count');
                        const data = await res.json();
                        document.getElementById('unread_count').textContent = `(${data.unread_count})`;
                    } catch (err) {
                        console.error("Unread count error:", err);
                    }
                }
                setInterval(fetchUnreadCount, 3000);

                const container = document.querySelector('.message_preview_wrapper');
                const mobileViewer = document.querySelector('.mobile_message_viewer');
                const desktopViewer = document.querySelector('.message_viewer');

                container.addEventListener('click', async e => {

                    const deleteBtn = e.target.closest('.delete_btn');
                        if (deleteBtn) {
                            e.stopPropagation();
                            const id = deleteBtn.dataset.id;

                            try {
                                const res = await fetch(`/messages/delete`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({ id })
                                });

                                let data;
                                try {
                                    data = await res.json();
                                } catch (err) {
                                    const text = await res.text();
                                    console.error('Server response is not JSON:', text);
                                    alert('Server returned unexpected response. Check console.');
                                    return;
                                }

                                if (data.success) {
                                    deleteBtn.closest('.message_preview_overlay').remove();
                                } else {
                                    alert('Failed to delete message: ' + (data.error || 'Unknown error'));
                                }
                            } catch (err) {
                                console.error('Delete request error:', err);
                                alert('Network or server error occurred. Check console.');
                            }

                            return; 
                        }

                    
                    const message = e.target.closest('.message_preview');
                    if (!message) return;

                    const messageId = message.dataset.id;
                    const isMobile = window.innerWidth <= 800;
                    const viewer = isMobile ? mobileViewer : desktopViewer;

                    try {
                        const res = await fetch('/messages/mark_message_as_read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ id: messageId })
                        });

                        const data = await res.json();
                        if (data.success) {
                            message.classList.remove('message_unread');
                            message.classList.add('message_read');
                        }

                        const fullRes = await fetch(`/messages/load_messages?id=${messageId}`);
                        const fullData = await fullRes.json();

                        viewer.innerHTML = `
                            ${isMobile ? `<button class="close_button">Close</button>` : ''}
                            <div class="message_wrapper">
                                <div class="name"><b>From:</b>&nbsp;${fullData.name}</div>
                                <div class="email"><b>Email:</b>&nbsp;${fullData.email}</div>
                                <div class="phone"><b>Phone:</b>&nbsp;${fullData.phone}</div>
                                <div class="body">&nbsp;${fullData.body}</div>
                            </div>
                        `;

                        if (isMobile) {
                            viewer.classList.add('active');
                            viewer.querySelector('.close_button').addEventListener('click', () => {
                                viewer.classList.remove('active');
                            });
                        }
                    } catch (err) {
                        console.error('Open message error:', err);
                    }
                });

                async function pollMessages() {
                    try {
                        const response = await fetch('/messages/load_messages');
                        const messages = await response.json();

                        messages.forEach(msg => {
                            if (document.querySelector(`[data-id="${msg.id}"]`)) return;

                            const overlay = document.createElement("div");
                            overlay.classList.add("message_preview_overlay");
                            overlay.dataset.id = msg.id;

                            const shortBody = msg.body.length > 30 ? msg.body.substring(0, 30) + "…" : msg.body;

                            overlay.innerHTML = `
                                <div class="message_preview ${msg.read ? "message_read" : "message_unread"}" data-id="${msg.id}">
                                    <div class="from"><b>From: </b>${msg.name}</div>
                                    <div class="email"><b>Email:</b>&nbsp;${msg.email}</div>
                                    <div class="phone"><b>Phone:</b>&nbsp;${msg.phone}</div>
                                    <div class="message"><b>Message:</b>&nbsp;${shortBody}</div>
                                </div>
                                <div class="overlay_controls">
                                    <button class="delete_btn" data-id="${msg.id}">
                                        <img src="/assets/icons/trash.png" alt="Delete" />
                                    </button>
                                    <input type="checkbox" class="select_message" data-id="${msg.id}">
                                </div>
                            `;

                            container.prepend(overlay);
                        });
                    } catch (err) {
                        console.error("Polling error:", err);
                    }
                }
                setInterval(pollMessages, 5000);

            </script>
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