const ROUTE = 'http://localhost';
const PORT = '8080';
let currentAction = null;
let currentUserId = null;
let actionType = null; // New variable to track whether it's block or unblock

async function fetchUsers() {
  const response = await fetch(`${ROUTE}:${PORT}/users`);
  const data = await response.json();
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
              <button onclick="prepareDeleteUser(${user.id})">Delete</button>
              <button>Details</button>
          </td>
      `;
      tableBody.appendChild(row);
  });
}

// Toggle block/unblock
async function toggleBlock(userId, isBlocked) {
  actionType = isBlocked ? 'Unblock' : 'Block'; // Set action type based on block status
  showConfirmationPopup(actionType, () => {
    updateBlockStatus(userId, !isBlocked);
  });
}

// Prepare for user deletion
function prepareDeleteUser(userId) {
  actionType = 'Delete';
  showConfirmationPopup("Delete this user", () => {
    deleteUser(userId);
  });
}

function disabledButtons() {
  const button = document.getElementById('confirmBtn');
  const buttonCancel = document.getElementById('cancelBtn');
  button.disabled = true;
  buttonCancel.disabled = true;
  button.textContent = "Loading...";
}

// Show confirmation pop-up
function showConfirmationPopup(message, action) {
  document.getElementById('popupMessage').innerText = message;
  document.getElementById('confirmationPopup').style.display = 'flex';
  currentAction = action;
}

// Confirm the action
document.getElementById('confirmBtn').addEventListener('click', () => {
  if (currentAction) {
    currentAction();
    disabledButtons();
  }
});

// Cancel the action
document.getElementById('cancelBtn').addEventListener('click', hideConfirmationPopup);

// Hide the pop-up
function hideConfirmationPopup() {
  document.getElementById('confirmationPopup').style.display = 'none';
}

// Update block/unblock status
async function updateBlockStatus(userId, isBlocked) {
  try {
    const response = await fetch(`${ROUTE}:${PORT}/users/${userId}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ isBlocked }),
    });
    const data = await response.json();
  } catch (error) {
    console.error(error);
  } finally {
    hideConfirmationPopup();
    location.reload();
  }
}

// Delete user
async function deleteUser(userId) {
  try {
    const response = await fetch(`${ROUTE}:${PORT}/users/${userId}`, {
      method: 'DELETE'
    });
    const data = await response.json();
  } catch (error) {
    console.error(error);
  } finally {
    hideConfirmationPopup();
    location.reload();
  }
}
// Fetch users when the page loads
window.onload = fetchUsers;
