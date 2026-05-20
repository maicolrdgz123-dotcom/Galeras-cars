<?php
include __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.html');
    exit;
}

$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['mphone'] ?? '');
$password = $_POST['passwd'] ?? '';

if (!$fname || !$lname || !$email || !$phone || !$password) {
    echo 'Error: Complete todos los campos.';
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Error: El correo electrónico no es válido.';
    exit;
}

$enc_pass = md5($password);

// Verificar email
$check_email_sql = 'SELECT 1 FROM users WHERE email = $1';
$res_email = pg_query_params($local_conn, $check_email_sql, [$email]);
if ($res_email && pg_num_rows($res_email) > 0) {
    echo "Error: El correo electrónico '$email' ya se encuentra registrado.";
    exit;
}

// Verificar teléfono
$check_phone_sql = 'SELECT 1 FROM users WHERE mobile_phone = $1';
$res_phone = pg_query_params($local_conn, $check_phone_sql, [$phone]);
if ($res_phone && pg_num_rows($res_phone) > 0) {
    echo "Error: El número de celular '$phone' ya se encuentra registrado.";
    exit;
}

// Insertar usuario
$insert_sql = 'INSERT INTO users (firstname, lastname, email, mobile_phone, password) VALUES ($1, $2, $3, $4, $5)';
$res_insert = pg_query_params($local_conn, $insert_sql, [$fname, $lname, $email, $phone, $enc_pass]);

if (!$res_insert) {
    echo 'Error: No se pudo guardar el registro en la base de datos.';
    exit;
}

// Intentar sincronizar con Supabase si la conexión está disponible
if (isset($supa_conn) && $supa_conn) {
    $res_supa = pg_query_params($supa_conn, $insert_sql, [$fname, $lname, $email, $phone, $enc_pass]);
    if (!$res_supa) {
        echo 'Registro guardado localmente, pero falló el guardado en la nube.';
        exit;
    }
}

echo 'Registro exitoso.';
?>