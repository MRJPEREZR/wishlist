ROUTE='http://localhost';
PORT='8080';

const loginForm = document.getElementById('login-form');
loginForm.addEventListener('submit', async function(event) {
    event.preventDefault();

    try {
        const response = await fetch(`${ROUTE}:${PORT}/users/authenticate`, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                username: username,
                password: password
            })
        });

        console.log(response)

        if (response.ok) {
            window.location.href = '/dashboard';
        } else {
            const error = await response.json();
            alert('Login failed: ' + error.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});
