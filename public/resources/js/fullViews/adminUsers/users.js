document.addEventListener('DOMContentLoaded', function () {
    $('[data-toggle="tooltip"]').tooltip();

    const usersTable = document.getElementById('usersTable');
    usersTable.addEventListener('click', function (event) {
        if (event.target.classList.contains('manage-roles')) {
            const userId = event.target.getAttribute('data-user-id');

            fetch(`/users/${userId}/roles`)
                .then(response => response.json())
                .then(data => {
                    const modalBody = document.querySelector('#rolesModal .modal-body');
                    modalBody.innerHTML = `<input type="hidden" name="user_id" value="${userId}">`;
                    data.allRoles.forEach(role => {
                        const isChecked = data.assignedRoles.includes(role.id);
                        const roleDiv = document.createElement('div');
                        roleDiv.className = 'form-check';
                        roleDiv.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="roles[]" value="${role.id}" id="role_${role.id}" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label" for="role_${role.id}" title="${role.description || ''}">
                                ${role.name}
                            </label>`;
                        modalBody.appendChild(roleDiv);
                    });
                    $('#rolesModal').modal('show');
                })
                .catch(error => console.error('Error:', error));
        }
    });

    document.getElementById('rolesForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const userId = formData.get('user_id');

        fetch(`/users/${userId}/update-roles`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#rolesModal').modal('hide');
                    showUniversalModal('Úspěch', 'Role byly úspěšně změněny.', true);
                } else {
                    showUniversalModal('Chyba', 'Role nebyly změněny, kontaktujte administrátora');
                }
            })
            .catch(error => console.error('Error:', error));
    });

    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchFilter = document.getElementById('searchFilter');

    roleFilter.addEventListener('change', fetchUsers);
    statusFilter.addEventListener('change', fetchUsers);
    searchFilter.addEventListener('input', fetchUsers);

    function fetchUsers() {
        const role = roleFilter.value;
        const status = statusFilter.value;
        const search = searchFilter.value;

        fetch(`/users/filter?role=${role}&status=${status}&search=${search}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => updateUsersTable(data))
            .catch(error => console.error('Error:', error));
    }

    function resetFilters() {
        roleFilter.selectedIndex = 0;
        statusFilter.selectedIndex = 0;
        searchFilter.value = '';
    }

    resetFilters();
});

function updateUsersTable(data) {
    const tbody = document.querySelector('#usersTable tbody');
    tbody.innerHTML = '';
    data.users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.first_name} ${user.last_name}</td>
            <td>${user.email}</td>
            <td>${formatUserStatus(user.status)}</td>
            <td>${user.roles.map(role => `<span class="badge bg-secondary">${role.name}</span>`).join(' ')}</td>
            <td>
                <form action="/users/${user.id}/update-status" method="POST">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <button type="submit" class="btn btn-sm ${user.status === 'activated' ? 'btn-secondary' : 'btn-success'}">
                        ${user.status === 'activated' ? 'Pozastavit' : 'Aktivovat'}
                    </button>
                </form>
                <button class="btn btn-info btn-sm manage-roles" data-user-id="${user.id}">Spravovat role</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function formatUserStatus(status) {
    switch (status) {
        case 'activated':
            return 'Aktivní';
        case 'unactivated':
            return 'Neaktivní';
        case 'stopped':
            return 'Pozastaven';
        default:
            return 'Neznámý';
    }
}
