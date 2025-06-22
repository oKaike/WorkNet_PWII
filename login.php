<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        
        <form>
            <div class="form-group">
                <label for="email">Seu e-mail</label>
                <input type="email" id="email" placeholder="exemplo@dominio.com">
            </div>
            
            <div class="form-group">
                <label for="password">Sua senha</label>
                <input type="password" id="password" placeholder="Digite sua senha">
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember">
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

<?php

session_start(); // Inicia a sessão para armazenar os dados do usuário

include_once 'conexao.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $lembrar = isset($_POST['remember']) ? true : false;

    try {

        $stmt = $conn->prepare("SELECT id_user, nome_user, senha_user FROM usuario WHERE email_user = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        // Verificando se o usuário existe
        if ($stmt->rowCount() > 0) {

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificando a senha
            if (password_verify($senha, $usuario['senha_user'])) {
                
                $_SESSION['id_user'] = $usuario['id_user'];
                $_SESSION['nome_user'] = $usuario['nome_user'];
                
                if ($lembrar) {
                    setcookie('email', $email, time() + 86400 * 30, "/"); // Lembrar por 30 dias
                    setcookie('senha', $senha, time() + 86400 * 30, "/"); // Lembrar por 30 dias
                }
                
                header('Location: tela_principal.html');
                exit();
            } else {

                $_SESSION['error'] = "Senha incorreta!";
                header('Location: login.html');
                exit();
            }
        } else {
            // Usuário não encontrado
            $_SESSION['error'] = "E-mail não encontrado!";
            header('Location: login.php');
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

