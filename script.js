// Variáveis para armazenar os elementos do DOM
const daysCounter = document.getElementById('days-counter');
const motivationPhrase = document.getElementById('motivation-phrase');
const resetBtn = document.getElementById('reset-btn');
const infoBtn = document.getElementById('info-btn');
const modal = document.getElementById('modal');
const closeModal = document.querySelector('.close-modal');
const resetModal = document.getElementById('reset-modal');
const closeResetModal = document.querySelector('.close-reset-modal');
const confirmReset = document.getElementById('confirm-reset');
const cancelReset = document.getElementById('cancel-reset');
const moneySaved = document.getElementById('money-saved');

// Frases motivacionais
const motivationalPhrases = [
    "Você está indo muito bem, continue assim!",
    "A cada dia mais forte, o seu futuro sem cigarro está mais próximo!",
    "Seus pulmões estão te agradecendo por essa decisão!",
    "Cada dia sem fumar é uma vitória para a sua saúde.",
    "Você é mais forte que o vício. Continue firme!",
    "Sua determinação é inspiradora. Parabéns pelo seu progresso!",
    "Pequenas vitórias diárias levam a grandes transformações.",
    "Sua saúde está melhorando a cada minuto sem cigarro!",
    "Lembre-se: você está quebrando um ciclo vicioso. Isso é incrível!",
    "Sua força de vontade é admirável. Continue nessa jornada!",
    "Cada dia sem fumar é um presente para você e seus entes queridos.",
    "Sua pele, dentes e respiração já estão agradecendo!",
    "Você já economizou dinheiro e ganhou saúde. Ótima troca!",
    "O ar está mais fresco quando se respira liberdade do vício.",
    "Sua jornada inspira outras pessoas ao seu redor.",
    "Hoje sem fumar, amanhã também. Um dia de cada vez.",
    "Sua saúde está se recuperando a cada momento. Continue assim!",
    "Você está provando para si mesmo que é possível vencer esse desafio.",
    "O cigarro não controla mais sua vida. Você está no comando!",
    "Parabéns pela sua determinação. Você consegue!"
];

// Configurações padrão
const DEFAULT_COST_PER_PACK = 12; // Custo médio de um maço de cigarros em reais
const DEFAULT_PACKS_PER_DAY = 1; // Quantidade padrão de maços por dia

// Função para obter uma frase aleatória
function getRandomPhrase() {
    const randomIndex = Math.floor(Math.random() * motivationalPhrases.length);
    return motivationalPhrases[randomIndex];
}

// Função para calcular dias desde uma data
function calculateDaysSince(date) {
    const currentDate = new Date();
    const startDate = new Date(date);
    
    // Calcula a diferença em milissegundos
    const differenceInTime = currentDate.getTime() - startDate.getTime();
    
    // Converte a diferença para dias
    const differenceInDays = Math.floor(differenceInTime / (1000 * 3600 * 24));
    
    return differenceInDays;
}

// Função para calcular economia
function calculateMoneySaved(days, packsPerDay, costPerPack) {
    return (days * packsPerDay * costPerPack).toFixed(2);
}

// Função para atualizar a interface
function updateUI() {
    // Verificar se já existe uma data de início armazenada
    const startDate = localStorage.getItem('quitDate');
    
    if (startDate) {
        // Calcula os dias desde que parou de fumar
        const daysSince = calculateDaysSince(startDate);
        daysCounter.textContent = daysSince;
        
        // Calcula o dinheiro economizado (usando valores padrão ou salvos)
        const packsPerDay = localStorage.getItem('packsPerDay') || DEFAULT_PACKS_PER_DAY;
        const costPerPack = localStorage.getItem('costPerPack') || DEFAULT_COST_PER_PACK;
        const saved = calculateMoneySaved(daysSince, packsPerDay, costPerPack);
        moneySaved.textContent = saved;
    } else {
        // Primeira vez acessando o site, registra a data atual
        const currentDate = new Date().toISOString();
        localStorage.setItem('quitDate', currentDate);
        localStorage.setItem('packsPerDay', DEFAULT_PACKS_PER_DAY);
        localStorage.setItem('costPerPack', DEFAULT_COST_PER_PACK);
        
        // Atualiza a interface com os valores iniciais
        daysCounter.textContent = "0";
        moneySaved.textContent = "0.00";
    }
    
    // Atualiza a frase motivacional
    // Verifica se já existe uma frase armazenada para hoje
    const today = new Date().toDateString();
    const savedDate = localStorage.getItem('lastPhraseDate');
    
    if (today === savedDate) {
        // Se for o mesmo dia, usa a frase armazenada
        motivationPhrase.textContent = localStorage.getItem('todaysPhrase');
    } else {
        // Se for um novo dia, gera uma nova frase
        const newPhrase = getRandomPhrase();
        motivationPhrase.textContent = newPhrase;
        
        // Armazena a nova frase e a data
        localStorage.setItem('todaysPhrase', newPhrase);
        localStorage.setItem('lastPhraseDate', today);
    }
}

// Função para reiniciar o contador
function resetCounter() {
    const currentDate = new Date().toISOString();
    localStorage.setItem('quitDate', currentDate);
    
    // Fecha o modal de confirmação
    resetModal.style.display = "none";
    
    // Atualiza a interface
    updateUI();
    
    // Exibe uma nova frase motivacional para o novo começo
    const newPhrase = getRandomPhrase();
    motivationPhrase.textContent = newPhrase;
    localStorage.setItem('todaysPhrase', newPhrase);
}

// Event Listeners

// Botão de reiniciar contador
resetBtn.addEventListener('click', function() {
    resetModal.style.display = "block";
});

// Confirmar reinício do contador
confirmReset.addEventListener('click', resetCounter);

// Cancelar reinício
cancelReset.addEventListener('click', function() {
    resetModal.style.display = "none";
});

// Fechar o modal de reinício
closeResetModal.addEventListener('click', function() {
    resetModal.style.display = "none";
});

// Botão de informações
infoBtn.addEventListener('click', function() {
    modal.style.display = "block";
});

// Fechar o modal de informações
closeModal.addEventListener('click', function() {
    modal.style.display = "none";
});

// Fechar os modais ao clicar fora deles
window.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
    if (event.target === resetModal) {
        resetModal.style.display = "none";
    }
});

// Alternar exibição de login/cadastro
const loginToggleBtn = document.getElementById('login-toggle-btn');
const loginOptions = document.getElementById('login-options');

loginToggleBtn.addEventListener('click', function() {
    loginOptions.style.display = loginOptions.style.display === 'flex' ? 'none' : 'flex';
});

// Login
const loginForm = document.getElementById('login-form');

loginForm.addEventListener('submit', function(event) {
    event.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Validação de login com dados armazenados
    const registeredUser = localStorage.getItem('registeredUser');
    const registeredPassword = localStorage.getItem('registeredPassword');

    if (username === registeredUser && password === registeredPassword) {
        alert('Login bem-sucedido!');
        loginOptions.style.display = 'none';
    } else {
        alert('Usuário ou senha inválidos.');
    }
});

// Botão de cadastro
const registerBtn = document.getElementById('register-btn');

registerBtn.addEventListener('click', function() {
    alert('Funcionalidade de cadastro ainda não implementada.');
});

// Inicializa a aplicação
document.addEventListener('DOMContentLoaded', function() {
    updateUI();
    
    // Aplica uma animação ao contador para chamar a atenção
    daysCounter.style.transform = "scale(1.05)";
    setTimeout(() => {
        daysCounter.style.transform = "scale(1)";
    }, 500);
});