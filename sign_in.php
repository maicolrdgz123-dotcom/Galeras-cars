<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header('refresh:0; url=index.php');
}

include __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['passwd'] ?? '';

if (!$email || !$password) {
    echo "<script>alert('Por favor complete todos los campos.'); window.location.href='login.php';</script>";
    exit;
}

$enc_pass = md5($password);

$sql_login = "SELECT u.id, u.email, u.firstname || ' ' || u.lastname AS full_name FROM users u WHERE u.email = '$email' AND u.password = '$enc_pass'";
$res_login = pg_query_params($local_conn, $sql_login, []);

if (!$res_login) {
    $row = pg_fetch_assoc($res_login);
    echo 'Error en la consulta de login.';
    exit;
}

if (pg_num_rows($res_login) > 0) {
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_email'] = $row['email'];
    $_SESSION['user_name'] = $row['full_name'];
    header('Location: index.php');
    exit;
}

echo "<script>alert('Correo o contraseña inválidos.'); window.location.href='login.php';</script>";
exit;
?>
