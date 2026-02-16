<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>

<h2 align="center">User Management</h2>

<table border="1" width="70%" align="center" cellpadding="10">

    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th width="120">Action</th>
        </tr>
    </thead>

    <tbody id="userRows">
        <!-- Users loaded dynamically -->
    </tbody>

    <tfoot>
        <tr>
            <td>
                <input id="new_name" placeholder="Enter name">
            </td>

            <td>
                <input id="new_email" placeholder="Enter email">
            </td>

            <td align="center">
                <button onclick="addUser()">Save</button>
            </td>
        </tr>
    </tfoot>

</table>


<script>

function loadUsers() {
    fetch('/api/users')
        .then(res => res.json())
        .then(data => {

            let rows = '';

            data.users.forEach(user => {
                rows += `
                    <tr>
                        <td>
                            <input value="${user.name}" ${data.is_admin ? '' : 'readonly'}>
                        </td>

                        <td>
                            <input value="${user.email}" ${data.is_admin ? '' : 'readonly'}>
                        </td>

                        <td></td>
                    </tr>
                `;
            });

            document.getElementById('userRows').innerHTML = rows;
        });
}


function addUser() {

    let name = document.getElementById('new_name').value.trim();
    let email = document.getElementById('new_email').value.trim();

    if (!name || !email) {
        alert("Name and Email required");
        return;
    }

    fetch('/api/users', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name: name,
            email: email,
            password: '123456'
        })
    })
    .then(res => res.json())
    .then(() => {
        loadUsers();
        document.getElementById('new_name').value = '';
        document.getElementById('new_email').value = '';
    });
}

loadUsers();

</script>

</body>
</html>
