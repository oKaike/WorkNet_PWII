<?php
session_start();
include_once 'conexao.php';

$erro = '';
$email_cookie = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim(strtolower($_POST['email']));
    $senha = $_POST['senha'];
    $lembrar = isset($_POST['remember']) ? true : false;
    
    // Validação básica
    if (empty($email) || empty($senha)) {
        $erro = "Email e senha são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id_user, nome_user, senha_user FROM usuario WHERE email_user = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($senha, $usuario['senha_user'])) {
                    // Login bem-sucedido
                    $_SESSION['id_user'] = $usuario['id_user'];
                    $_SESSION['nome_user'] = $usuario['nome_user'];
                    
                    // Configurar cookies se solicitado
                    if ($lembrar) {
                        setcookie('email', $email, time() + (86400 * 30), "/", "", false, true); // 30 dias, httpOnly
                        // Não salvar senha em cookie por segurança
                    } else {
                        // Limpar cookies se não quer lembrar
                        setcookie('email', '', time() - 3600, "/");
                    }
                    
                    header('Location: index.php');
                    exit();
                } else {
                    $erro = "Senha incorreta!";
                }
            } else {
                $erro = "E-mail não encontrado!";
            }
        } catch (PDOException $e) {
            $erro = "Erro no sistema. Tente novamente.";
            error_log("Login Error: " . $e->getMessage());
        }
    }
}

// Verificar se há mensagem de erro da sessão
if (isset($_SESSION['error'])) {
    $erro = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        
        <?php if (!empty($erro)): ?>
            <div class="alert">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Seu e-mail</label>
                <input type="email" id="email" name="email" placeholder="exemplo@dominio.com" 
                       value="<?php echo htmlspecialchars($email_cookie); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Sua senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" value="1" 
                       <?php echo !empty($email_cookie) ? 'checked' : ''; ?>>
                <label for="remember">Manter-me logado</label>
            </div>
            
            <button type="submit">Logar</button>
        </form>
        
        <div class="registrar-link">
            Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>
