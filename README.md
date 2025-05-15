# FumoDromo - Sistema de Acompanhamento para Parar de Fumar

## 📋 Sobre o Projeto

O FumoDromo é uma aplicação web desenvolvida para auxiliar pessoas que desejam parar de fumar. O sistema oferece um conjunto de ferramentas para acompanhamento diário, registro de humor e progressos, além de estatísticas motivacionais.

## 🚀 Funcionalidades Principais

### 1. Página Inicial (Login)
- Autenticação de usuários
- Opção de cadastro para novos usuários
- Interface limpa e acolhedora

### 2. Dashboard
- Visão geral do progresso
- Contador de dias sem fumar
- Checklist diário de objetivos
- Conquistas desbloqueadas
- Registro rápido de humor

### 3. Diário
- Registro detalhado do dia a dia
- Seletor de humor com 5 níveis
- Medidor de ansiedade
- Medidor de vontade de fumar
- Campo para anotações pessoais
- Filtros por humor e data
- Busca nas anotações
- Visualização do histórico em cards

### 4. Estatísticas
- Gráficos de progresso
- Histórico de humor
- Níveis de ansiedade ao longo do tempo
- Sequência atual de dias sem fumar
- Maior sequência alcançada
- Economia financeira estimada

### 5. Perfil
- Dados pessoais
- Configurações da conta
- Opção de alterar senha
- Meta diária de cigarros não fumados
- Valor médio gasto com cigarros (para cálculo de economia)

## 💻 Requisitos do Sistema

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache Web Server
- XAMPP (recomendado para ambiente de desenvolvimento)

## 🔧 Instalação

1. **Instalar o XAMPP**

2. **Clonar o Repositório**
   ```sh
   cd /opt/lampp/htdocs/
   git clone [URL_DO_REPOSITORIO] fumodromo
   ```

3. **Configurar o Banco de Dados**
   - Abrir o phpMyAdmin (http://localhost/phpmyadmin)
   - Criar um novo banco de dados chamado "fumodromo"
   - Importar o arquivo `db.sql` do projeto

4. **Configurar o Arquivo de Conexão**
   - Abrir o arquivo `config.php`
   - Ajustar as credenciais do banco de dados se necessário:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'seu_usuario');
     define('DB_PASS', 'sua_senha');
     define('DB_NAME', 'fumodromo');
     ```

5. **Permissões de Arquivos**
   ```sh
   sudo chmod -R 755 /opt/lampp/htdocs/fumodromo
   sudo chown -R daemon:daemon /opt/lampp/htdocs/fumodromo
   ```

## 📱 Estrutura do Projeto

```
fumodromo/
├── api/                    # APIs para comunicação com o backend
├── assets/                 # Recursos estáticos (imagens, etc)
├── css/                    # Arquivos de estilo
├── includes/              # Arquivos PHP reutilizáveis
├── js/                    # Scripts JavaScript
├── *.php                  # Páginas principais
└── README.md              # Esta documentação
```

## 🎨 Tecnologias Utilizadas

- **Frontend**:
  - HTML5
  - CSS3 (com variáveis CSS personalizadas)
  - JavaScript (Vanilla JS)
  - Bootstrap 5
  - Bootstrap Icons
  - SweetAlert2 (para notificações)

- **Backend**:
  - PHP
  - MySQL
  - JSON para comunicação API

## 🔒 Segurança

O sistema implementa:
- Proteção contra SQL Injection
- Validação de sessões
- Senhas criptografadas com hash
- Proteção contra CSRF
- Sanitização de inputs

## 📊 Banco de Dados

O sistema utiliza as seguintes tabelas:
- `usuarios`: Dados dos usuários
- `diario`: Registros diários
- `checklist`: Items completados
- `conquistas`: Conquistas desbloqueadas
- `configuracoes`: Preferências do usuário

## 🎯 Funcionalidades Detalhadas

### Sistema de Conquistas
- Primeiro dia sem fumar
- Primeira semana completa
- Primeiro mês sem fumar
- Economia financeira atingida
- Sequência de registros no diário
- Metas diárias alcançadas

### Checklist Diário
- Exercícios de respiração
- Beber água
- Exercício físico
- Momento de meditação
- Atividade relaxante

### Sistema de Humor
1. Muito Mal (Vermelho)
2. Mal (Amarelo)
3. Neutro (Azul)
4. Bem (Verde claro)
5. Muito Bem (Verde)

## 📈 Próximos Passos Sugeridos para os Alunos

1. Implementar modo escuro
2. Adicionar suporte a múltiplos idiomas
3. Criar versão PWA do sistema
4. Implementar backup dos dados
5. Adicionar compartilhamento social
6. Criar sistema de medalhas personalizadas

## ❓ Suporte

Para dúvidas sobre o projeto, você pode:
1. Consultar esta documentação
2. Verificar os comentários no código
3. Entrar em contato com o professor

## 👥 Contribuição

Este é um projeto educacional. Alunos são encorajados a:
1. Propor melhorias
2. Reportar bugs
3. Sugerir novas funcionalidades
4. Criar pull requests com correções

## 📝 Notas de Desenvolvimento

- O sistema usa uma arquitetura simples e didática
- Código comentado para facilitar o aprendizado
- Implementação de boas práticas de programação
- Exemplos de integração frontend/backend
- Demonstração de UI/UX responsiva
