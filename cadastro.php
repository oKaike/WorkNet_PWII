<?php
session_start();
include_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Coletar dados
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $data_nasc = $_POST['nascimento'];
    $sexo = $_POST['sexo'];
    $telefone = $_POST['telefone'];
    $estado_civil = $_POST['estado_civil'];
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $rg = $_POST['rg'];
    $nacionalidade = $_POST['nacionalidade'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];

    // Conhecimentos (checkbox)
    $conhecimentos = isset($_POST['conhecimentos']) ? json_encode($_POST['conhecimentos']) : null;

    // Endereço JSON
    $endereco = json_encode([
        'cep' => $cep,
        'estado' => $estado,
        'cidade' => $cidade,
        'bairro' => $bairro
    ]);

    try {
        // Verifica duplicidade de email ou CPF
        $check = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email_user = :email OR cpf_user = :cpf");
        $check->execute([':email' => $email, ':cpf' => $cpf]);
        if ($check->fetchColumn() > 0) {
            echo "E-mail ou CPF já está cadastrado.";
            exit();
        }

        // 1️⃣ Inserir na tabela AcessoUsuario
        $insertLogin = $conn->prepare("INSERT INTO AcessoUsuario (nome_cliente, email_cliente, senha_cliente) VALUES (:nome, :email, :senha)");
        $insertLogin->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha
        ]);

        // 2️⃣ Recuperar o ID do cliente (id_cliente) recém-gerado
        $id_cliente = $conn->lastInsertId();

        // 3️⃣ Inserir na tabela usuario
        $insertUsuario = $conn->prepare("INSERT INTO usuario (
            id_cliente, nome_user, email_user, senha_user, data_nasc, sexo_user, telefone_user,
            estado_civil, cpf_user, rg_user, nacionalidade_user, endereco_user, conhecimentos
        ) VALUES (
            :id_cliente, :nome, :email, :senha, :data_nasc, :sexo, :telefone,
            :estado_civil, :cpf, :rg, :nacionalidade, :endereco, :conhecimentos
        )");

        $insertUsuario->bindParam(':id_cliente', $id_cliente);
        $insertUsuario->bindParam(':nome', $nome);
        $insertUsuario->bindParam(':email', $email);
        $insertUsuario->bindParam(':senha', $senha);
        $insertUsuario->bindParam(':data_nasc', $data_nasc);
        $insertUsuario->bindParam(':sexo', $sexo);
        $insertUsuario->bindParam(':telefone', $telefone);
        $insertUsuario->bindParam(':estado_civil', $estado_civil);
        $insertUsuario->bindParam(':cpf', $cpf);
        $insertUsuario->bindParam(':rg', $rg);
        $insertUsuario->bindParam(':nacionalidade', $nacionalidade);
        $insertUsuario->bindParam(':endereco', $endereco);
        $insertUsuario->bindParam(':conhecimentos', $conhecimentos);

        $insertUsuario->execute();

        // Sucesso
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Conta</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Criar Nova Conta</h1>
        
        <form id="form-cadastro" method="POST" action="cadastro.php">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
            </div>
            
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>
            </div>
            
            <div class="form-group">
                <label for="nascimento">Data de Nascimento</label>
                <input type="date" id="nascimento" name="nascimento" required>
            </div>

            <div class="form-group">
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Selecione...</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
            </div>

            <div class="form-group">
                <label for="estado_civil">Estado Civil</label>
                <select id="estado_civil" name="estado_civil" required>
                    <option value="">Selecione...</option>
                    <option value="solteiro">Solteiro(a)</option>
                    <option value="casado">Casado(a)</option>
                    <option value="divorciado">Divorciado(a)</option>
                    <option value="viuvo">Viúvo(a)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
            </div>

            <div class="form-group">
                <label for="rg">RG</label>
                <input type="text" id="rg" name="rg" placeholder="00.000.000-0" required>
            </div>

            <div class="form-group">
                <label for="nacionalidade">Nacionalidade</label>
                <input type="text" id="nacionalidade" name="nacionalidade" placeholder="Digite sua nacionalidade" required>
            </div>

            <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" id="estado" name="estado" placeholder="Estado" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" id="cidade" name="cidade" placeholder="Cidade" required>
            </div>

            <div class="form-group">
                <label for="bairro">Bairro</label>
                <input type="text" id="bairro" name="bairro" placeholder="Bairro" required>
            </div>
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" id="cep" name="cep" placeholder="00000-000" required>
            </div>

            <div class="form-group">
                <label>Conhecimentos</label><br>
                <input type="checkbox" name="conhecimentos[]" value="HTML"> HTML
                <input type="checkbox" name="conhecimentos[]" value="CSS"> CSS
                <input type="checkbox" name="conhecimentos[]" value="JavaScript"> JavaScript
            </div>

            <div class="checkbox">
                <input type="checkbox" id="termos" name="termos" required>
                <label for="termos">Aceito os termos de uso e política de privacidade</label>
            </div>



            
            <button type="submit">Cadastrar</button>
            
            <div class="login-link">
                Já tem uma conta? <a href="login.php">Faça login</a>
            </div>
        </form>
    </div>
    <script src="cadastro.js"></script>
    <link rel="stylesheet" href="cadastro.css">
</body>
</html>