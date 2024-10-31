<?php
// Configurações de conexão com o banco de dados
$host = 'localhost'; // Nome do host
$dbname = 'univesttechexperience'; // Nome do banco de dados
$user = 'root'; // Nome do usuário do banco de dados
$password = ''; // Senha do banco de dados

try {
    // Cria uma nova conexão com PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Em caso de erro, exibe a mensagem
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
