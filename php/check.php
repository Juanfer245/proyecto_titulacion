<?php
include "./conexion.php";
if (isset($_POST['email']) &&isset($_POST['password'])) {
    $resultado = $conexion->query("select * from usuario where
    email = '".$_POST['email']."' and
    password = '".sha1($_POST['password'])."' ") or die ($conexion->error);
if (mysqli_num_rows($resultado)>0) {
    $datos_usuario=mysqli_fetch_row($resultado);
    $nombre = $datos_usuario[1];
    $id_usuario=$datos_usuario[0];
    $email = $datos_usuario[3];
    $imagen_perfil = $datos_usuario[5];
    die('Bienvenido '.$nombre);
}else {
header("Location:../login.php?error=Credenciales incorrectas");
}

}else {
    header("../login.php");
}
?>