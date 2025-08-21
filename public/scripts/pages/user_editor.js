async function csrfFetch(url, options = {}) {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
        },
        credentials: 'same-origin',
    };

    const finalOptions = Object.assign({}, defaultOptions, options);

    const res = await fetch(url, finalOptions);

    let data;
    try {
        data = await res.json();
    } catch (err) {
        const text = await res.text();
        console.error('Response not JSON:', text);
        throw new Error('Invalid JSON response');
    }

    return data;
}

function attachEditHandler(row) {
    const deleteBtn = row.querySelector('.delete_user');
    const editBtn = row.querySelector('.edit_user');
    const usernameCell = row.querySelector('.username');
    const passwordCell = row.querySelector('.password');

    if (deleteBtn) {
        deleteBtn.addEventListener('click', async () => {
            const userId = deleteBtn.dataset.id;
            try {
                const result = await csrfFetch('/users/delete', {
                    method: 'POST',
                    body: JSON.stringify({ id: userId }),
                });
                if (result.success) row.remove();
                else alert(result.message || 'Failed to delete user');
            } catch (err) {
                console.error('Delete error:', err);
                alert('Error deleting user');
            }
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
                    const data = await csrfFetch('/users/update', {
                        method: 'POST',
                        body: JSON.stringify(payload),
                    });

                    if (data.success) {
                        usernameCell.textContent = data.user.username;
                        passwordCell.textContent = '**************';
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
                passwordCell.innerHTML = `<input type="password" placeholder="**************">`;
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
        const data = await csrfFetch('/users/add', {
            method: 'POST',
            body: JSON.stringify({ username, password }),
        });

        if (!data.success) {
            alert(data.message || 'Failed to add user');
            return;
        }

        const table = document.querySelector('.users table');
        const newRow = document.createElement('tr');
        newRow.id = `user-${data.user.id}`;
        newRow.innerHTML = `
            <td class="username">${data.user.username}</td>
            <td class="password">**************</td>
            <td><button class="edit_user" data-id="${data.user.id}">Edit</button></td>
            <td><button class="delete_user" data-id="${data.user.id}">Delete</button></td>
        `;
        table.appendChild(newRow);
        form.reset();
        attachEditHandler(newRow);
    } catch (err) {
        console.error('Error adding user:', err);
        alert('An error occurred while adding the user.');
    }
});
