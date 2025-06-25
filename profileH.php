<?php
session_start();
include_once 'conexao.php';

if (!isset($_SESSION['id_cliente'])) {
    header('location: login.php');
    exit();
}

$id_cliente = $_SESSION['id_cliente'];

// buscar dados do usuario, formacao e diversidade
$stmt = $conn->prepare("
    SELECT u.*, f.idioma, f.nivel_idioma, d.identidade_de_genero, d.orientacao_sexual, d.cor_ou_raca
    FROM usuario u
    LEFT JOIN formacao_idioma f ON u.id_user = f.id_user
    LEFT JOIN diversidade d ON u.id_user = d.id_user
    WHERE u.id_cliente = :id_cliente
");
$stmt->execute([':id_cliente' => $id_cliente]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Decodificar conhecimentos JSON para pegar resumo e interesses
$conhecimentos_json = json_decode($dados['conhecimentos'] ?? '{}', true);
$resumo = $conhecimentos_json['resumo'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // dados do form
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $cargo = $_POST['cargo'];
    $estado = $_POST['estado_user'];
    $cidade = $_POST['cidade_user'];
    $genero = $_POST['gen'];
    $nacionalidade = $_POST['nac_WN'];
    $resumo = $_POST['resumo'];
    $interesses = $_POST['descricao_exp'];

    // campos extra
    $idioma = $_POST['idioma'] ?? null;
    $nivel_idioma = $_POST['nivel_idioma'] ?? null;
    $identidade = $_POST['identidade'] ?? null;
    $orientacao = $_POST['orientacao'] ?? null;
    $cor = $_POST['cor'] ?? null;

    $endereco = json_encode(['estado' => $estado, 'cidade' => $cidade]);
    $conhecimentos = json_encode(['resumo' => $resumo, 'interesses' => $interesses]);

    // atualizar tabela usuario
    $update_user = $conn->prepare("UPDATE usuario SET 
      nome_user = :nome, 
      sobrenome = :sobrenome, 
      profissao_ou_cargo = :cargo, 
      email_user = :email, 
      nacionalidade_user = :nacionalidade, 
      sexo_user = :genero, 
      endereco_user = :endereco, 
      conhecimentos = :conhecimentos,
      descricao = :descricao
      WHERE id_cliente = :id_cliente");

    $update_user->execute([
        ':nome' => $nome,
        ':sobrenome' => $sobrenome,
        ':cargo' => $cargo,
        ':email' => $email,
        ':nacionalidade' => $nacionalidade,
        ':genero' => $genero,
        ':endereco' => $endereco,
        ':conhecimentos' => $conhecimentos,
        ':descricao' => $interesses,
        ':id_cliente' => $id_cliente
    ]);

    // buscar id_user correspondente
    $buscar_id_user = $conn->prepare("SELECT id_user FROM usuario WHERE id_cliente = :id_cliente");
    $buscar_id_user->execute([':id_cliente' => $id_cliente]);
    $id_user = $buscar_id_user->fetchColumn();

    // atualizar ou inserir formacao_idioma
    $verifica_formacao = $conn->prepare("SELECT COUNT(*) FROM formacao_idioma WHERE id_user = :id_user");
    $verifica_formacao->execute([':id_user' => $id_user]);

    if ($verifica_formacao->fetchColumn() > 0) {
        $update_idioma = $conn->prepare("UPDATE formacao_idioma SET idioma = :idioma, nivel_idioma = :nivel WHERE id_user = :id_user");
        $update_idioma->execute([
            ':idioma' => $idioma,
            ':nivel' => $nivel_idioma,
            ':id_user' => $id_user
        ]);
    } else {
        $insert_idioma = $conn->prepare("INSERT INTO formacao_idioma (id_user, escola, estado, cidade, status_fa, data_inicio, idioma, nivel_idioma) VALUES (:id_user, '', '', '', 'cursando', CURRENT_DATE(), :idioma, :nivel)");
        $insert_idioma->execute([
            ':id_user' => $id_user,
            ':idioma' => $idioma,
            ':nivel' => $nivel_idioma
        ]);
    }

    // atualizar ou inserir diversidade
    $verifica_div = $conn->prepare("SELECT COUNT(*) FROM diversidade WHERE id_user = :id_user");
    $verifica_div->execute([':id_user' => $id_user]);

    if ($verifica_div->fetchColumn() > 0) {
        $update_div = $conn->prepare("UPDATE diversidade SET identidade_de_genero = :identidade, orientacao_sexual = :orientacao, cor_ou_raca = :cor WHERE id_user = :id_user");
        $update_div->execute([
            ':identidade' => $identidade,
            ':orientacao' => $orientacao,
            ':cor' => $cor,
            ':id_user' => $id_user
        ]);
    } else {
        $insert_div = $conn->prepare("INSERT INTO diversidade (id_user, identidade_de_genero, orientacao_sexual, cor_ou_raca) VALUES (:id_user, :identidade, :orientacao, :cor)");
        $insert_div->execute([
            ':id_user' => $id_user,
            ':identidade' => $identidade,
            ':orientacao' => $orientacao,
            ':cor' => $cor
        ]);
    }

    echo "<script>alert('Perfil atualizado com sucesso!'); window.location.href='profileH.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Perfil do Usuário</title>
  <link rel="stylesheet" href="profile.css" />
</head>
<body>

<a href="tela_principal.html">
  <button type="button" class="menusuperior" >Tela Principal</button>
</a>

<div class="perfil-container">
<form method="post" action="">
  <h1 style="text-align:center; color:#4b1ea7; margin-bottom: 30px;">Perfil</h1>

  <div class="profile-header">
    <div class="profile-info">
      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($dados['nome_user'] ?? '') ?>" placeholder="Nome" required style="margin-bottom: 10px; width: 100%;" />

      <label for="sobrenome">Sobrenome</label>
      <input type="text" id="sobrenome" name="sobrenome" value="<?= htmlspecialchars($dados['sobrenome'] ?? '') ?>" placeholder="Sobrenome" required style="margin-bottom: 10px; width: 100%;" />
    </div>
  </div>

  <div class="profile-info">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($dados['email_user'] ?? '') ?>" required placeholder="Email" style="margin-bottom: 10px; width: 100%;" />

    <label for="cargo">Profissão ou Cargo</label>
    <input type="text" id="cargo" name="cargo" value="<?= htmlspecialchars($dados['profissao_ou_cargo'] ?? '') ?>" placeholder="Profissão ou cargo" style="margin-bottom: 10px; width: 100%;" />
  </div>

  <div class="profile-info">
    <label for="estado_user">Estado</label>
    <input type="text" id="estado_user" name="estado_user" value="<?= htmlspecialchars(json_decode($dados['endereco_user'] ?? '{}')->estado ?? '') ?>" style="margin-bottom: 10px; width: 100%;" />

    <label for="cidade_user">Cidade</label>
    <input type="text" id="cidade_user" name="cidade_user" value="<?= htmlspecialchars(json_decode($dados['endereco_user'] ?? '{}')->cidade ?? '') ?>" style="margin-bottom: 10px; width: 100%;" />
  </div>

  <label for="nac_WN">Nacionalidade</label>
  <input type="text" id="nac_WN" name="nac_WN" value="<?= htmlspecialchars($dados['nacionalidade_user'] ?? '') ?>" placeholder="Nacionalidade" style="margin-bottom: 10px; width: 400px;" />

  <label for="gen">Gênero</label>
  <select name="gen" id="gen" style="margin-bottom: 10px; width: 200px;">
    <option value="masculino" <?= ($dados['sexo_user'] ?? '') === 'masculino' ? 'selected' : '' ?>>Masculino</option>
    <option value="feminino" <?= ($dados['sexo_user'] ?? '') === 'feminino' ? 'selected' : '' ?>>Feminino</option>
    <option value="outro" <?= ($dados['sexo_user'] ?? '') === 'outro' ? 'selected' : '' ?>>Outro</option>
  </select>

  <div class="section">
    <h3>Idioma</h3>
    <label for="idioma">Idioma</label>
    <input type="text" id="idioma" name="idioma" value="<?= htmlspecialchars($dados['idioma'] ?? '') ?>" placeholder="Idioma" style="margin-bottom: 10px; width: 100%;" />

    <label for="nivel_idioma">Nível do Idioma</label>
    <input type="text" id="nivel_idioma" name="nivel_idioma" value="<?= htmlspecialchars($dados['nivel_idioma'] ?? '') ?>" placeholder="Nível (ex: Básico, Intermediário)" style="margin-bottom: 10px; width: 100%;" />
  </div>

  <div class="section">
    <h3>Diversidade</h3>
    <label for="identidade">Identidade de Gênero</label>
    <input type="text" id="identidade" name="identidade" value="<?= htmlspecialchars($dados['identidade_de_genero'] ?? '') ?>" placeholder="Identidade de Gênero" style="margin-bottom: 10px; width: 100%;" />

    <label for="orientacao">Orientação Sexual</label>
    <input type="text" id="orientacao" name="orientacao" value="<?= htmlspecialchars($dados['orientacao_sexual'] ?? '') ?>" placeholder="Orientação Sexual" style="margin-bottom: 10px; width: 100%;" />

    <label for="cor">Cor ou Raça</label>
    <input type="text" id="cor" name="cor" value="<?= htmlspecialchars($dados['cor_ou_raca'] ?? '') ?>" placeholder="Cor ou Raça" style="margin-bottom: 10px; width: 100%;" />
  </div>

  <div class="section">
    <h3>Resumo</h3>
    <label for="resumo">Resumo Profissional</label>
    <textarea id="resumo" name="resumo" rows="5" placeholder="Resumo" style="margin-bottom: 10px; width: 100%;"><?= htmlspecialchars($resumo) ?></textarea>
  </div>

  <div class="section">
    <h3>Interesses</h3>
    <label for="descricao_exp">Áreas de Interesse</label>
    <textarea id="descricao_exp" name="descricao_exp" rows="5" placeholder="Interesses" style="margin-bottom: 10px; width: 100%;"><?= htmlspecialchars($dados['descricao'] ?? '') ?></textarea>
  </div>

<div style="margin-top: 30px; text-align: left;">
  <button type="submit" style="padding: 12px 25px; font-size: 16px; background-color: #4b1ea7; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">
    Salvar Alterações
  </button>
</div>

</form>
</div>

<script src="APIs.js"></script>
<script src="profile.js"></script>

</body>
</html>
