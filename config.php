<?php

// Defina as configurações do banco de dados como constantes
define('DB_HOST', 'localhost');     // Endereço do servidor do banco de dados (geralmente localhost)
define('DB_NAME', 'nome_do_banco'); // Nome do seu banco de dados
define('DB_USER', 'nome_do_usuario'); // Nome de usuário do banco de dados
define('DB_PASS', 'senha_do_usuario'); // Senha do banco de dados
define('DB_CHARSET', 'utf8mb4');   // Conjunto de caracteres para a conexão (recomendado utf8mb4)

try {
    // Cria uma nova instância da classe PDO para a conexão
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);

            // Define o modo de erro do PDO para exceções
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Define o modo de recuperação para objetos (opcional, mas útil)
                        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

                            // Opcional: Defina o nível de isolamento da transação (se necessário)
                                //$pdo->exec("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");

                                    // Se a conexão for bem-sucedida, você pode adicionar uma mensagem de sucesso (apenas para teste)
                                        // echo "Conexão com o banco de dados estabelecida com sucesso!";

                                        } catch (PDOException $e) {
                                            // Em caso de erro na conexão, exibe uma mensagem de erro
                                                die("Erro na conexão com o banco de dados: " . $e->getMessage());
                                                }

                                                // A variável $pdo agora contém a conexão com o banco de dados
                                                // Você pode incluir este arquivo em outros scripts PHP para usar a conexão

                                                ?>
                                                