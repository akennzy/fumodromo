document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const username = document.getElementById('new-username').value;
    const password = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (password !== confirmPassword) {
        alert('As senhas não coincidem. Tente novamente.');
        return;
    }

    // Simulação de armazenamento de dados de usuário
    localStorage.setItem('registeredUser', username);
    localStorage.setItem('registeredPassword', password);

    alert('Cadastro realizado com sucesso!');
    window.location.href = 'index.html';
});
