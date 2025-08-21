const csrf_token = document.querySelector('meta[name="csrf-token"]').content;

function attachEditHandler(row) {
    const deleteBtn = row.querySelector('.delete_user');
    const editBtn = row.querySelector('.edit_user');
    const usernameCell = row.querySelector('.username');
    const passwordCell = row.querySelector('.password');

    if (deleteBtn) {
        deleteBtn.addEventListener('click', async function () {
            const userId = deleteBtn.dataset.id;
            const res = await fetch('/users/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf_token,
                },
                body: JSON.stringify({ id: userId }),
            });
            const result = await res.json();
            if (result.success) row.remove();
            else alert(result.message || 'Failed to delete user');
        });
    }

    if (editBtn) {
        editBtn.addEventListener('click', async function handleEdit() {
            const userId = editBtn.dataset.id;

            if (editBtn.classList.contains('save_user')) {
                // Save mode
                const newUsername = usernameCell.querySelector('input').value.trim();
                const newPassword = passwordCell.querySelector('input').value.trim();

                const payload = { id: userId, username: newUsername };
                if (newPassword !== '') payload.password = newPassword;

                try {
                    const res = await fetch('/users/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf_token,
                        },
                        body: JSON.stringify(payload),
                    });
                    const data = await res.json();
                    if (data.success) {
                        usernameCell.textContent = data.user.username;
                        passwordCell.textContent = data.user.password;
                        editBtn.textContent = 'Edit';
                        editBtn.classList.remove('save_user');
                        editBtn.classList.add('edit_user');
                    } else {
                        alert(data.message || 'Failed to update user');
                    }
                } catch (err) {
                    console.error('Update error:', err);
                    alert('An error occurred while updating the user.');
                }
            } else {
                // Edit mode
                const currentUsername = usernameCell.textContent;
                usernameCell.innerHTML = `<input type="text" value="${currentUsername}">`;
                passwordCell.innerHTML = `<input type="password" placeholder="New password">`;
                editBtn.textContent = 'Save';
                editBtn.classList.add('save_user');
                editBtn.classList.remove('edit_user');
            }
        });
    }
}

// Attach handlers for all existing rows
document.querySelectorAll('.users table tr').forEach(row => {
    if (row.id.startsWith('user-')) attachEditHandler(row);
});

document.getElementById('add-user-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const username = form.querySelector('input[name="username"]').value;
    const password = form.querySelector('input[name="password"]').value;

    try {
        const response = await fetch('/users/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            },
            body: JSON.stringify({ username, password }),
        });

        const data = await response.json();
        if (!data.success) {
            alert(data.message || 'Failed to add user');
            return;
        }

        // Create new row
        const table = document.querySelector('.users table');
        const newRow = document.createElement('tr');
        newRow.id = `user-${data.user.id}`;
        newRow.innerHTML = `
            <td class="username">${data.user.username}</td>
            <td class="password">${data.user.password}</td>
            <td><button class="edit_user" data-id="${data.user.id}">Edit</button></td>
            <td><button class="delete_user" data-id="${data.user.id}">Delete</button></td>
        `;
        table.appendChild(newRow);

        // reset form
        form.reset();

        // just call attachEditHandler once
        attachEditHandler(newRow);

    } catch (err) {
        console.error('Error adding user:', err);
        alert('An error occurred while adding the user.');
    }
});

