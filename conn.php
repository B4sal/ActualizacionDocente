<?php
$enlace = mysqli_connect("localhost", "root", "", "actualizaciondocente");

if (!$enlace) {
    die("No pudo conectarse a la base de datos: " . mysqli_connect_error());
}
?>