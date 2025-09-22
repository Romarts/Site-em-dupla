<?php
// agenda.php - Simples sistema de agendamento para carpinteiros

// Horários disponíveis (simulação)
$horarios = [
    "08:00", "09:00", "10:00", "11:00",
    "13:00", "14:00", "15:00", "16:00"
];

// Simula banco de dados (arquivo texto)
$arquivo = "agendamentos.txt";
$sucesso = false;

// Lista fixa de serviços
$servicos = [
    "Pergolado",
    "Deck de Madeira",
    "Mesa Rústica",
    "Porta Sob Medida"
];

// Cancelamento de agendamento
if (isset($_GET['cancelar'])) {
    $idCancelar = (int) $_GET['cancelar'];
    if (file_exists($arquivo)) {
        $linhas = file($arquivo, FILE_IGNORE_NEW_LINES);
        if (isset($linhas[$idCancelar])) {
            unset($linhas[$idCancelar]);
            file_put_contents($arquivo, implode("\n", $linhas));
        }
    }
    header("Location: agenda.php"); // redireciona após cancelar
    exit;
}

// Agendamento novo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $servico = $_POST['servico'] ?? '';

    if ($nome && $horario && $servico) {
        file_put_contents($arquivo, "$horario - $nome - $servico\n", FILE_APPEND);
        $sucesso = true;
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Carvalho Serviços Madeiras</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Página Inicial</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Agenda</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Trabalhos 
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Pergolados</a></li>
            <li><a class="dropdown-item" href="#">Decks</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Mais serviços</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Buscar">
        <button class="btn btn-outline-success" type="submit">Buscar</button>
      </form>
    </div>
  </div>
</nav>

<div class="container my-5">

  <h1>Carvalho Serviços em Madeiras</h1>

  <!-- Formulário -->
  <h2>Agendamento</h2>
  
  <?php if ($sucesso): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill"></i> Seu horário foi agendado com sucesso!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow p-4 mb-5">
    <form method="POST">
      <div class="mb-3">
        <label for="nome" class="form-label">Seu nome</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input type="text" id="nome" name="nome" class="form-control" placeholder="Digite seu nome" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="servico" class="form-label">Escolha o serviço</label>
        <select id="servico" name="servico" class="form-select" required>
          <option value="">Selecione...</option>
          <?php foreach ($servicos as $s): ?>
            <option value="<?= $s ?>"><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="horario" class="form-label">Escolha um horário</label>
        <select id="horario" name="horario" class="form-select" required>
          <?php foreach ($horarios as $h): ?>
            <option value="<?= $h ?>" <?= in_array($h, $horarios_ocupados) ? 'disabled' : '' ?>>
              <?= $h ?> <?= in_array($h, $horarios_ocupados) ? '(Indisponível)' : '(Disponível)' ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button type="submit" class="btn btn-success w-100">Agendar</button>
    </form>
  </div>

  <!-- Horários -->
  <h2>Agendamentos Realizados</h2>
  <ul class="list-group">
    <?php foreach ($agendados as $index => $linha): ?>
      <?php list($hora, $nome, $servico) = explode(" - ", $linha); ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <span><strong><?= $hora ?></strong> - <?= $nome ?> (<?= $servico ?>)</span>
        <a href="?cancelar=<?= $index ?>" class="btn btn-danger btn-sm">
          <i class="bi bi-x-circle"></i> Cancelar
        </a>
      </li>
    <?php endforeach; ?>
  </ul>

</div>

<!-- Footer -->
<footer class="footer mt-5 py-4">
  <div class="container">
    <div class="row text-center text-md-start">
      <div class="col-md-4 mb-3">
        <h5>Carvalho Serviços</h5>
        <p>Especialistas em pergolados, decks e serviços em madeira de qualidade.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h5>Links Rápidos</h5>
        <ul class="list-unstyled">
          <li><a href="index.php">Página Inicial</a></li>
          <li><a href="#">Agenda</a></li>
          <li><a href="#">Trabalhos</a></li>
        </ul>
      </div>
      <div class="col-md-4 mb-3">
        <h5>Contato</h5>
        <p><i class="bi bi-telephone"></i> (11) 99999-9999</p>
        <p><i class="bi bi-envelope"></i> contato@carvalhoservicos.com</p>
        <div class="social-icons">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="https://wa.me/5544999797283?text=Olá%20gostaria%20de%20fazer%20um%20agendamento!" target="_blank">
            <i class="bi bi-whatsapp"></i>
          </a>
        </div>
      </div>
    </div>
    <hr>
    <div class="text-center mt-3">
      <p class="mb-0">&copy; <?= date("Y") ?> Carvalho Serviços - Todos os direitos reservados.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
