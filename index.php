<?php
// agenda.php - Simples sistema de agendamento para carpinteiros


// Horários disponíveis (simulação)
$horarios = [
    "08:00", "09:00", "10:00", "11:00",
    "13:00", "14:00", "15:00", "16:00"
];

// Simula banco de dados (arquivo texto)
$arquivo = "agendamentos.txt";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $horario = $_POST['horario'] ?? '';
    
    if ($nome && $horario) {
        file_put_contents($arquivo, "$horario - $nome\n", FILE_APPEND);
    }
}

// Lê os agendamentos feitos
$agendados = file_exists($arquivo) ? file($arquivo, FILE_IGNORE_NEW_LINES) : [];
$horarios_ocupados = array_map(fn($linha) => explode(" - ", $linha)[0], $agendados);
?>
<!DOCTYPE html>

<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Carvalho Serviços</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Carvalho Serviços Madeiras</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Página Inicial</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Agenda</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Trabalhos 
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
  <div class="texto">
    <h1> Carvalho Serviços em Madeiras</h1>

    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="10000">
      <img src="..." class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="5000">
      <img src="..." class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="5000">
      <img src="..." class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

  <h2>Agendamento de Pergolados</h2>

  <form method="POST">
    <label for="nome">Seu nome:</label><br>
    <input type="text" id="nome" name="nome" required><br><br>

    <label for="horario">Escolha um horário:</label><br>
    <select id="horario" name="horario" required>
      <?php foreach ($horarios as $h): ?>
        <?php if (!in_array($h, $horarios_ocupados)): ?>
          <option value="<?= $h ?>"><?= $h ?></option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Agendar</button>
  </form>

  <h2>Horários</h2>
  <?php foreach ($horarios as $h): ?>
    <div class="horario <?= in_array($h, $horarios_ocupados) ? 'ocupado' : 'livre' ?>">
      <?= $h ?> - <?= in_array($h, $horarios_ocupados) ? 'Indisponível' : 'Disponível' ?>
    </div>
  <?php endforeach; ?>

</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

</body>
</html>