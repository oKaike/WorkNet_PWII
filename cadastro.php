<?php
session_start();
include_once 'conexao.php';

// Variável para armazenar mensagens de erro
$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug: verificar se os dados estão chegando
    error_log("POST recebido: " . print_r($_POST, true));
    
    // Validação básica dos campos obrigatórios
    $campos_obrigatorios = ['nome', 'email', 'senha', 'nascimento', 'sexo', 'telefone', 'estado_civil', 'cpf', 'rg', 'nacionalidade', 'cep', 'estado', 'cidade', 'bairro'];
    
    foreach ($campos_obrigatorios as $campo) {
        if (empty($_POST[$campo])) {
            $erro = "O campo '$campo' é obrigatório.";
            break;
        }
    }
    
    // Se não há erros de validação, processar os dados
    if (empty($erro)) {
        try {
            // Coletar e sanitizar dados
            $nome = trim($_POST['nome']);
            $email = trim(strtolower($_POST['email']));
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $data_nasc = $_POST['nascimento'];
            $sexo = $_POST['sexo'];
            $telefone = $_POST['telefone'];
            $estado_civil = $_POST['estado_civil'];
            $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
            $rg = $_POST['rg'];
            $nacionalidade = $_POST['nacionalidade'];
            $cep = preg_replace('/[^0-9]/', '', $_POST['cep']);
            $estado = $_POST['estado'];
            $cidade = $_POST['cidade'];
            $bairro = $_POST['bairro'];

            // Validar CPF (11 dígitos)
            if (strlen($cpf) != 11) {
                $erro = "CPF deve ter 11 dígitos.";
            }
            
            // Validar CEP (8 dígitos)
            if (strlen($cep) != 8) {
                $erro = "CEP deve ter 8 dígitos.";
            }
            
            // Validar email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erro = "Email inválido.";
            }
            
            if (empty($erro)) {
                // Conhecimentos (opcional)
                $conhecimentos = isset($_POST['conhecimentos']) ? json_encode($_POST['conhecimentos']) : null;

                // Endereço JSON
                $endereco = json_encode([
                    'cep' => $cep,
                    'estado' => $estado,
                    'cidade' => $cidade,
                    'bairro' => $bairro
                ]);

                // Verificação de email e CPF duplicado
                $check = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE email_user = :email OR cpf_user = :cpf");
                $check->bindParam(':email', $email);
                $check->bindParam(':cpf', $cpf);
                $check->execute();

                if ($check->fetchColumn() > 0) {
                    $erro = "E-mail ou CPF já está cadastrado.";
                } else {
                    // Inserir no banco de dados (sem id_cliente pois é opcional)
                    $stmt = $conn->prepare("INSERT INTO usuario (
                        id_cliente, nome_user, email_user, senha_user, data_nasc, sexo_user, telefone_user,
                        estado_civil, cpf_user, rg_user, nacionalidade_user, endereco_user, conhecimentos
                    ) VALUES (
                        NULL, :nome, :email, :senha, :data_nasc, :sexo, :telefone,
                        :estado_civil, :cpf, :rg, :nacionalidade, :endereco, :conhecimentos
                    )");

                    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
                    $stmt->bindParam(':data_nasc', $data_nasc, PDO::PARAM_STR);
                    $stmt->bindParam(':sexo', $sexo, PDO::PARAM_STR);
                    $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                    $stmt->bindParam(':estado_civil', $estado_civil, PDO::PARAM_STR);
                    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
                    $stmt->bindParam(':rg', $rg, PDO::PARAM_STR);
                    $stmt->bindParam(':nacionalidade', $nacionalidade, PDO::PARAM_STR);
                    $stmt->bindParam(':endereco', $endereco, PDO::PARAM_STR);
                    $stmt->bindParam(':conhecimentos', $conhecimentos, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        $sucesso = "Cadastro realizado com sucesso!";
                        // Redirecionar após 2 segundos
                        header("refresh:2;url=index.php");
                    } else {
                        $erro = "Erro ao executar a inserção no banco de dados.";
                        error_log("Erro SQL: " . print_r($stmt->errorInfo(), true));
                    }
                }
            }
        } catch (PDOException $e) {
            $erro = "Erro no banco de dados: " . $e->getMessage();
            error_log("PDO Error: " . $e->getMessage());
        }
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
    <style>
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Criar Nova Conta</h1>
        
        <?php if (!empty($erro)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($sucesso); ?>
                <br>Redirecionando para a página de login...
            </div>
        <?php endif; ?>
        
        <form id="form-cadastro" method="POST" action="cadastro.php">
            <!-- Campo Nome Completo -->
            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" 
                       value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
            </div>
            
            <!-- Campo E-mail -->
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            
            <!-- Campo Senha -->
            <div class="form-group">
                <label for="senha">Senha *</label>
                <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>
            </div>
            
            <!-- Campo Data de Nascimento -->
            <div class="form-group">
                <label for="nascimento">Data de Nascimento *</label>
                <input type="date" id="nascimento" name="nascimento" 
                       value="<?php echo isset($_POST['nascimento']) ? htmlspecialchars($_POST['nascimento']) : ''; ?>" required>
            </div>

            <!-- Campo Sexo -->
            <div class="form-group">
                <label for="sexo">Sexo *</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Selecione...</option>
                    <option value="masculino" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                    <option value="feminino" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] == 'feminino') ? 'selected' : ''; ?>>Feminino</option>
                    <option value="outro" <?php echo (isset($_POST['sexo']) && $_POST['sexo'] == 'outro') ? 'selected' : ''; ?>>Outro</option>
                </select>
            </div>

            <!-- Campo Telefone -->
            <div class="form-group">
                <label for="telefone">Telefone *</label>
                <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" 
                       value="<?php echo isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : ''; ?>" required>
            </div>

            <!-- Campo Estado Civil -->
            <div class="form-group">
                <label for="estado_civil">Estado Civil *</label>
                <select id="estado_civil" name="estado_civil" required>
                    <option value="">Selecione...</option>
                    <option value="solteiro" <?php echo (isset($_POST['estado_civil']) && $_POST['estado_civil'] == 'solteiro') ? 'selected' : ''; ?>>Solteiro(a)</option>
                    <option value="casado" <?php echo (isset($_POST['estado_civil']) && $_POST['estado_civil'] == 'casado') ? 'selected' : ''; ?>>Casado(a)</option>
                    <option value="divorciado" <?php echo (isset($_POST['estado_civil']) && $_POST['estado_civil'] == 'divorciado') ? 'selected' : ''; ?>>Divorciado(a)</option>
                    <option value="viuvo" <?php echo (isset($_POST['estado_civil']) && $_POST['estado_civil'] == 'viuvo') ? 'selected' : ''; ?>>Viúvo(a)</option>
                </select>
            </div>

            <!-- Campo CPF -->
            <div class="form-group">
                <label for="cpf">CPF *</label>
                <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" 
                       value="<?php echo isset($_POST['cpf']) ? htmlspecialchars($_POST['cpf']) : ''; ?>" required>
            </div>

            <!-- Campo RG -->
            <div class="form-group">
                <label for="rg">RG *</label>
                <input type="text" id="rg" name="rg" placeholder="00.000.000-0" 
                       value="<?php echo isset($_POST['rg']) ? htmlspecialchars($_POST['rg']) : ''; ?>" required>
            </div>

            <!-- Campo Nacionalidade -->
            <div class="form-group">
                <label for="nacionalidade">Nacionalidade *</label>
                <input type="text" id="nacionalidade" name="nacionalidade" placeholder="Digite sua nacionalidade" 
                       value="<?php echo isset($_POST['nacionalidade']) ? htmlspecialchars($_POST['nacionalidade']) : ''; ?>" required>
            </div>

            <!-- Campo CEP -->
            <div class="form-group">
                <label for="cep">CEP *</label>
                <input type="text" id="cep" name="cep" placeholder="00000-000" 
                       value="<?php echo isset($_POST['cep']) ? htmlspecialchars($_POST['cep']) : ''; ?>" required>
            </div>

            <!-- Campos de Endereço -->
            <div class="form-group">
                <label for="estado">Estado *</label>
                <input type="text" id="estado" name="estado" placeholder="Estado" 
                       value="<?php echo isset($_POST['estado']) ? htmlspecialchars($_POST['estado']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade *</label>
                <input type="text" id="cidade" name="cidade" placeholder="Cidade" 
                       value="<?php echo isset($_POST['cidade']) ? htmlspecialchars($_POST['cidade']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="bairro">Bairro *</label>
                <input type="text" id="bairro" name="bairro" placeholder="Bairro" 
                       value="<?php echo isset($_POST['bairro']) ? htmlspecialchars($_POST['bairro']) : ''; ?>" required>
            </div>

            <!-- Campo conhecimentos (opcional) -->
            <div class="form-group">
                <label>Conhecimentos (opcional)</label><br>
                <input type="checkbox" name="conhecimentos[]" value="HTML" 
                       <?php echo (isset($_POST['conhecimentos']) && in_array('HTML', $_POST['conhecimentos'])) ? 'checked' : ''; ?>> HTML
                <input type="checkbox" name="conhecimentos[]" value="CSS"
                       <?php echo (isset($_POST['conhecimentos']) && in_array('CSS', $_POST['conhecimentos'])) ? 'checked' : ''; ?>> CSS
                <input type="checkbox" name="conhecimentos[]" value="JavaScript"
                       <?php echo (isset($_POST['conhecimentos']) && in_array('JavaScript', $_POST['conhecimentos'])) ? 'checked' : ''; ?>> JavaScript
            </div>

            <!-- Termos de Uso -->
            <div class="checkbox">
                <input type="checkbox" id="termos" name="termos" required>
                <label for="termos">Aceito os termos de uso e política de privacidade *</label>
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
