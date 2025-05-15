document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!loginForm.checkValidity()) {
            e.stopPropagation();
            loginForm.classList.add('was-validated');
            return;
        }

        const formData = {
            usuario: document.getElementById('usuario').value,
            senha: document.getElementById('senha').value
        };

        try {
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: data.message || 'Erro ao fazer login'
                });
            }
        } catch (error) {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Erro ao conectar com o servidor'
            });
        }
    });
});
