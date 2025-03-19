ROUTE='http://localhost';
PORT='8080';

async function fetchUsers() {
  const response = await fetch(`${ROUTE}:${PORT}/users`);
  const data = await response.json();
  console.log(response);
  const tableBody = document.querySelector('#usersTable tbody');
  tableBody.innerHTML = ''; // Clear current table rows

  data.users.forEach(user => {
      const row = document.createElement('tr');
      row.innerHTML = `
          <td>${user.username}</td>
          <td>${user.name} ${user.surname}</td>
          <td>${user.mail}</td>
          <td>
              <button onclick="toggleBlock(${user.id}, ${user.isBlocked})">
                  ${user.isBlocked ? 'Unblock' : 'Block'}
              </button>
              <button onclick="deleteUser(${user.id})">Delete</button>
          </td>
      `;
      tableBody.appendChild(row);
  });
}

// Toggle block/unblock
async function toggleBlock(userId, isBlocked) {
  const response = await fetch(`${ROUTE}:${PORT}/users/${userId}`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json', // Ensure the content type is JSON
    },
    body: JSON.stringify({
      isBlocked: !isBlocked, // Toggle the block status
    }),
  });
  const data = await response.json();
  fetchUsers(); // Refresh the list of users
}

// Delete user
async function deleteUser(userId) {
  const response = await fetch(`${ROUTE}:${PORT}/users/${userId}`, {
    method: 'DELETE'
  });
  const data = await response.json();
  fetchUsers(); // Refresh the list of users
}

// Fetch users when the page loads
window.onload = fetchUsers;