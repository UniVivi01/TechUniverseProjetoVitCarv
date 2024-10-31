<?php
// api.php
header('Content-Type: application/json');
require 'config.php';

// Função para calcular a média e a presença
function calcularDadosAluno($aluno) {
    $media = $aluno['media'];
    $presenca = $aluno['presenca'];
    
    // Aqui você pode definir seus critérios para risco de evasão
    $risco = 'Baixo'; // Inicializa como baixo

    if ($presenca < 75) {
        $risco = 'Alto';
    } elseif ($presenca < 85) {
        $risco = 'Médio';
    }
    
    return [
        'nome' => $aluno['nome'],
        'presenca' => $presenca,
        'media' => $media,
        'atividadesEmSala' => $aluno['atividadesEmSala'],
        'atividadesExtracurriculares' => $aluno['atividadesExtracurriculares'],
        'risco' => $risco,
    ];
}

// Endpoint para buscar alunos com cálculos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT nome, presenca, notaOficial AS media, atividadesEmSala, atividadesExtracurriculares FROM aluno WHERE nome LIKE ?");
        $stmt->execute(["%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT nome, presenca, notaOficial AS media, atividadesEmSala, atividadesExtracurriculares FROM aluno");
    }

    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcula os dados de cada aluno
    $alunosComCalculos = array_map('calcularDadosAluno', $alunos);
    
    echo json_encode($alunosComCalculos);
}

// Endpoint para adicionar um novo aluno
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nome = $data['nome'];
    $presenca = $data['presenca'];
    $media = $data['media'];
    $atividadesEmSala = $data['atividadesEmSala'];
    $atividadesExtracurriculares = $data['atividadesExtracurriculares'];

    $stmt = $pdo->prepare("INSERT INTO aluno (nome, presenca, notaOficial, atividadesEmSala, atividadesExtracurriculares) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $presenca, $media, $atividadesEmSala, $atividadesExtracurriculares]);

    echo json_encode(['message' => 'Aluno adicionado com sucesso!']);
}

// Endpoint para atualizar um aluno
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = $_PUT['id'];
    $nome = $_PUT['nome'];
    $presenca = $_PUT['presenca'];
    $media = $_PUT['media'];
    $atividadesEmSala = $_PUT['atividadesEmSala'];
    $atividadesExtracurriculares = $_PUT['atividadesExtracurriculares'];

    $stmt = $pdo->prepare("UPDATE aluno SET nome = ?, presenca = ?, notaOficial = ?, atividadesEmSala = ?, atividadesExtracurriculares = ? WHERE id = ?");
    $stmt->execute([$nome, $presenca, $media, $atividadesEmSala, $atividadesExtracurriculares, $id]);

    echo json_encode(['message' => 'Aluno atualizado com sucesso!']);
}

// Endpoint para deletar um aluno
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_DELETE['id'];

    $stmt = $pdo->prepare("DELETE FROM aluno WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['message' => 'Aluno deletado com sucesso!']);
} else {
    echo json_encode(['message' => 'Método não permitido.']);
}
?>
