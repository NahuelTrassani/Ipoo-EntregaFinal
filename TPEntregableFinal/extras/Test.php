<?php

$servername = "localhost"; // Nombre del servidor
$username = "root"; // Nombre de usuario de MySQL
$password = "112233"; // Contraseña de MySQL
$dbname = "EmpresaViajes";
// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);
// Seleccionar la base de datos
//$conn->select_db($dbname);
// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
} else {
    echo "Conexión exitosa";
}
insertarEmpresa('empresa1', 'dir1');
insertarResponsable(123, 'aaa', 'bbb');
insertarViaje('nqn', 12, 1, 1, 1500);
insertarPasajero(123, 'qqq', 'zzz', 123, 1);
function insertarEmpresa($nombre, $direccion)
{
    global $conn;
    $sql = "INSERT INTO empresa (enombre, edireccion) VALUES ('$nombre', '$direccion')";
    if ($conn->query($sql) === TRUE) {
        echo "Empresa insertada exitosamente";
    } else {
        echo "Error al insertar empresa: " . $conn->error;
    }
}

function insertarResponsable($licencia, $nombre, $apellido)
{
    global $conn;
    $sql = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) VALUES ($licencia, '$nombre', '$apellido')";
    if ($conn->query($sql) === TRUE) {
        echo "Responsable insertado exitosamente";
    } else {
        echo "Error al insertar responsable: " . $conn->error;
    }
}
function insertarViaje($destino, $cantPasajeros, $idEmpresa, $idResponsable, $importe)
{
    global $conn;
    $sql = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) VALUES ('$destino', $cantPasajeros, $idEmpresa, $idResponsable, $importe)";
    if ($conn->query($sql) === TRUE) {
        echo "Viaje insertado exitosamente";
    } else {
        echo "Error al insertar viaje: " . $conn->error;
    }
}
function insertarPasajero($documento, $nombre, $apellido, $telefono, $idViaje)
{
    global $conn;
    $sql = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) VALUES ('$documento', '$nombre', '$apellido', $telefono, $idViaje)";
    if ($conn->query($sql) === TRUE) {
        echo "Pasajero insertado exitosamente";
    } else {
        echo "Error al insertar pasajero: " . $conn->error;
    }
}