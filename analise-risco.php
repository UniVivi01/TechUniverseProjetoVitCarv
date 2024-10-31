<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Análise de Risco - EduTech</title>
  <link rel="stylesheet" href="css/estilo.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<header> <!--inicio cabecalho-->
    <nav class="navbar navbar-expand-sm navbar-light" style="background-color: #4907ff;">
      <div class="container">
        <a href="index.html" class="navbar-brand">
          <img src="img/Logotipo_Azul_e_Branco_Faculdade_Curso_Escola___500_x_300_px___1_-removebg-preview.png" width="142px" alt="">
        </a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navprincipal">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navprincipal">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a href="index.html" class="nav-link text-white">Home</a>
            </li>
            <li class="nav-item">
              <a href="analise-risco.php" class="nav-link text-white">Análise de Risco</a>
            </li>
            <li class="nav-item">
              <a href="grupos-estudo.php" class="nav-link text-white">Grupos de Estudo</a>
            </li>
            <li class="nav-item">
              <a href="listagem.php" class="nav-link text-white">Listagem de Alunos</a>
            </li>
            <li class="nav-item">
              <a href="" class="btn btn-outline-light ml-4">Entrar</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
</header> <!--fim cabecalho-->

<section class="container mt-5">
    <h1>Análise de Risco de Evasão e Reprovação</h1>

    <!-- Formulário de Pesquisa -->
    <form method="GET" class="mb-4">
        <input type="text" name="search" class="form-control" placeholder="Pesquisar aluno pelo nome" aria-label="Pesquisar" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="btn btn-primary mt-2">Pesquisar</button>
    </form>

    <?php
    // Conexão com o banco de dados
    $conn = new mysqli("localhost", "root", "", "univesttechexperience");

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Inicializa a variável de busca
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Prepara a consulta SQL com base na pesquisa
    if (!empty($search)) {
        $sql = "SELECT nome, presenca, notaOficial AS media, atividadesEmSala, atividadesExtracurriculares FROM aluno WHERE nome = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $search);
    } else {
        $sql = "SELECT nome, presenca, notaOficial AS media, atividadesEmSala, atividadesExtracurriculares FROM aluno";
        $stmt = $conn->prepare($sql);
    }

    // Executa a consulta
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table class='table table-striped'><thead><tr><th>Nome</th><th>Presença</th><th>Média</th><th>Atividades em Sala</th><th>Atividades Extra</th><th>Nível de Risco</th></tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            $risco = "Baixo";
            
            // Cálculo de risco
            if ($row["presenca"] < 30 && $row["media"] <= 5 && $row["atividadesEmSala"] <= 7 && $row["atividadesExtracurriculares"] < 3) {
                $risco = "Alto";
            } elseif ($row["presenca"] >= 30 && $row["presenca"] <= 60 && $row["media"] > 5 && $row["media"] < 7 && $row["atividadesEmSala"] > 7 && $row["atividadesEmSala"] <= 10 && $row["atividadesExtracurriculares"] >= 3 && $row["atividadesExtracurriculares"] < 5) {
                $risco = "Médio";
            }

            echo "<tr><td>{$row['nome']}</td><td>{$row['presenca']}%</td><td>{$row['media']}</td><td>{$row['atividadesEmSala']}</td><td>{$row['atividadesExtracurriculares']}</td><td>$risco</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Nenhum aluno encontrado.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
</section>
<!--font-->
<script src="https://kit.fontawesome.com/ad276175bf.js" crossorigin="anonymous"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Popper.js (necessário para o Bootstrap 4) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
