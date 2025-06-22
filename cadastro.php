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

    // Opcional: conhecimento
    $conhecimentos = isset($_POST['conhecimentos']) ? json_encode($_POST['conhecimentos']) : null;

    // Endereço JSON
    $endereco = json_encode([
        'cep' => $cep,
        'estado' => $estado,
        'cidade' => $cidade,
        'bairro' => $bairro
    ]);

    // id_cliente fictício (ajuste se precisar)
    $id_cliente = null;

    // Verificação de email e CPF duplicado
    $check = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email_user = :email OR cpf_user = :cpf");
    $check->execute([':email' => $email, ':cpf' => $cpf]);

    if ($check->fetchColumn() > 0) {
        echo "E-mail ou CPF já está cadastrado.";
        exit();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO usuario (
            id_cliente, nome_user, email_user, senha_user, data_nasc, sexo_user, telefone_user,
            estado_civil, cpf_user, rg_user, nacionalidade_user, endereco_user, conhecimentos
        ) VALUES (
            :id_cliente, :nome, :email, :senha, :data_nasc, :sexo, :telefone,
            :estado_civil, :cpf, :rg, :nacionalidade, :endereco, :conhecimentos
        )");

        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':estado_civil', $estado_civil);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':nacionalidade', $nacionalidade);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':conhecimentos', $conhecimentos);

        $stmt->execute();

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
            <!-- Campo Nome Completo -->
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
            </div>
            
            <!-- Campo E-mail -->
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            </div>
            
            <!-- Campo Senha -->
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>
            </div>
            
            <!-- Campo Data de Nascimento -->
            <div class="form-group">
                <label for="nascimento">Data de Nascimento</label>
                <input type="date" id="nascimento" name="nascimento" required>
            </div>

            <!-- Campo Sexo -->
            <div class="form-group">
                <label for="sexo">Sexo</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Selecione...</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                </select>
            </div>

            <!-- Campo Telefone -->
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
            </div>

            <!-- Campo Estado Civil -->
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

            <!-- Campo CPF -->
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
            </div>

            <!-- Campo RG -->
            <div class="form-group">
                <label for="rg">RG</label>
                <input type="text" id="rg" name="rg" placeholder="00.000.000-0" required>
            </div>

            <!-- Campo Nacionalidade -->
            <div class="form-group">
                <label for="nacionalidade">Nacionalidade</label>
                <input type="text" id="nacionalidade" name="nacionalidade" placeholder="Digite sua nacionalidade" required>
            </div>

            <!-- Campos de Endereço -->
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
                        <!-- Adicione este dentro da <form> -->
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" id="cep" name="cep" placeholder="00000-000" required>
            </div>

            <!-- Exemplo de campo conhecimentos (opcional) -->
            <div class="form-group">
                <label>Conhecimentos</label><br>
                <input type="checkbox" name="conhecimentos[]" value="HTML"> HTML
                <input type="checkbox" name="conhecimentos[]" value="CSS"> CSS
                <input type="checkbox" name="conhecimentos[]" value="JavaScript"> JavaScript
            </div>

            <!-- Termos de Uso -->
            <div class="checkbox">
                <input type="checkbox" id="termos" name="termos" required>
                <label for="termos">Aceito os termos de uso e política de privacidade</label>
            </div>



            
            <button type="submit">Cadastrar</button>
            
            <div class="login-link">
                Já tem uma conta? <a href="login.html">Faça login</a>
            </div>
        </form>
    </div>
    <script src="cadastro.js"></script>
    <link rel="stylesheet" href="cadastro.css">
</body>
</html>

