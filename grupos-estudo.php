<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grupos de Estudo - EduTech</title>
  <link rel="stylesheet" href="css/estilo.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<header>
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
</header>

<section class="container mt-5">
  <h1>Grupos de Estudo para Melhoria de Desempenho</h1>

  <!-- Formulário de Pesquisa -->
  <form method="GET" class="form-inline mb-3">
    <input type="text" name="alunoPesquisa" class="form-control mr-2" placeholder="Pesquisar Aluno" value="<?= $_GET['alunoPesquisa'] ?? '' ?>">
    <input type="text" name="grupoPesquisa" class="form-control mr-2" placeholder="Pesquisar Grupo (Ex: 8)" value="<?= $_GET['grupoPesquisa'] ?? '' ?>">
    <button type="submit" class="btn btn-primary">Pesquisar</button>
  </form>

  <?php
  // Conexão com o banco de dados
  $conn = new mysqli("localhost", "root", "", "univesttechexperience");
  if ($conn->connect_error) {
      die("Falha na conexão: " . $conn->connect_error);
  }

  // Obtém os valores das caixas de pesquisa
  $alunoPesquisa = $_GET['alunoPesquisa'] ?? '';
  $grupoPesquisa = $_GET['grupoPesquisa'] ?? '';

  // Define a consulta SQL para obter todos os alunos, sem filtro de grupo na consulta
  $sql = "SELECT a.nome, a.presenca, a.notaOficial AS media, a.atividadesEmSala AS atividades_sala, a.atividadesExtracurriculares AS atividades_extra 
          FROM aluno a 
          WHERE (? = '' OR a.nome = ?)
          ORDER BY media DESC";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $alunoPesquisa, $alunoPesquisa);
  $stmt->execute();
  $result = $stmt->get_result();

  $grupo = [];
  $contador = 0;
  $grupoAtual = 1;

  if ($result->num_rows > 0) {
      echo "<table class='table table-bordered table-striped'><thead><tr><th>Grupo</th><th>Nome</th><th>Presença</th><th>Média</th><th>Atividades em Sala</th><th>Atividades Extra</th></tr></thead><tbody>";
      while ($row = $result->fetch_assoc()) {
          $grupo[] = $row;
          $contador++;

          if ($contador % 5 == 0 || $result->num_rows == $contador) {
              // Somente exibe o grupo se a pesquisa corresponder ao número do grupo ou se o campo estiver vazio
              if ($grupoPesquisa === '' || $grupoPesquisa == $grupoAtual) {
                  foreach ($grupo as $aluno) {
                      echo "<tr><td>Grupo " . $grupoAtual . "</td><td>{$aluno['nome']}</td><td>{$aluno['presenca']}%</td><td>{$aluno['media']}</td><td>{$aluno['atividades_sala']}</td><td>{$aluno['atividades_extra']}</td></tr>";
                  }
              }
              $grupo = [];
              $grupoAtual++;
          }
      }
      echo "</tbody></table>";
  } else {
      echo "<p>Nenhum aluno encontrado.</p>";
  }

  $stmt->close();
  $conn->close();
  ?>
</section>

<script src="https://kit.fontawesome.com/ad276175bf.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
