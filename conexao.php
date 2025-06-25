<?php
$host = "localhost";
$user = "root";
$senha = "";
$dbname = "WorkNet";


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $senha);
    
    $conn ->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
}

 catch (PDOException $e) {
    error_log($e->getMessage());
    echo "Erro de conexão. Tente novamente mais tarde.";
}


?>