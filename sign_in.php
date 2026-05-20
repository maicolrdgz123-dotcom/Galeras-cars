<?php
include __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['passwd'] ?? '';

if (!$email || !$password) {
    echo "<script>alert('Por favor complete todos los campos.'); window.location.href='login.html';</script>";
    exit;
}

$enc_pass = md5($password);

$sql_login = 'SELECT u.id, u.firstname, u.lastname FROM users u WHERE u.email = $1 AND u.password = $2';
$res_login = pg_query_params($local_conn, $sql_login, [$email, $enc_pass]);

if (!$res_login) {
    echo 'Error en la consulta de login.';
    exit;
}

if (pg_num_rows($res_login) > 0) {
    header('Location: index.html');
    exit;
}

echo "<script>alert('Correo o contraseña inválidos.'); window.location.href='login.html';</script>";
exit;
?>
