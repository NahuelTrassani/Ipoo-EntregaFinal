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

/*
// Crear la base de datos
$dbname = "EmpresaViajes"; // Nombre de la base de datos
$sql = "CREATE DATABASE $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos creada exitosamente";
} else {
    echo "Error al crear la base de datos: " . $conn->error;
}
*/




// Funciones para la tabla 'empresa'

function insertarEmpresa($nombre, $direccion)
{
    $sql = "INSERT INTO empresa (enombre, edireccion) VALUES ('$nombre', '$direccion')";
    return $sql;
}
function actualizarEmpresa($id, $nombre, $direccion)
{
    $sql = "UPDATE empresa SET enombre = '$nombre', edireccion = '$direccion' WHERE idempresa = $id";
    return $sql;
}

function eliminarEmpresa($id)
{
    $sql = "DELETE FROM empresa WHERE idempresa = $id";
    return $sql;
}

// Funciones para la tabla 'responsable'

function insertarResponsable($licencia, $nombre, $apellido)
{
    $sql = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) VALUES ($licencia, '$nombre', '$apellido')";
    return $sql;
}

function actualizarResponsable($id, $licencia, $nombre, $apellido)
{
    $sql = "UPDATE responsable SET rnumerolicencia = $licencia, rnombre = '$nombre', rapellido = '$apellido' WHERE rnumeroempleado = $id";
    return $sql;
}

function eliminarResponsable($id)
{
    $sql = "DELETE FROM responsable WHERE rnumeroempleado = $id";
    return $sql;
}

// Funciones para la tabla 'viaje'

function insertarViaje($destino, $cantPasajeros, $idEmpresa, $idResponsable, $importe)
{
    $sql = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) VALUES ('$destino', $cantPasajeros, $idEmpresa, $idResponsable, $importe)";
    return $sql;
}

function actualizarViaje($id, $destino, $cantPasajeros, $idEmpresa, $idResponsable, $importe)
{
    $sql = "UPDATE viaje SET vdestino = '$destino', vcantmaxpasajeros = $cantPasajeros, idempresa = $idEmpresa, rnumeroempleado = $idResponsable, vimporte = $importe WHERE idviaje = $id";
    return $sql;
}

function eliminarViaje($id)
{
    $sql = "DELETE FROM viaje WHERE idviaje = $id";
    return $sql;
}

// Funciones para la tabla 'pasajero'

function insertarPasajero($documento, $nombre, $apellido, $telefono, $idViaje)
{
    $sql = "INSERT INTO pasajero (pdocumento, pnombre, papellido, ptelefono, idviaje) VALUES ('$documento', '$nombre', '$apellido', $telefono, $idViaje)";
    return $sql;
}

function actualizarPasajero($documento, $nombre, $apellido, $telefono, $idViaje)
{
    $sql = "UPDATE pasajero SET pnombre = '$nombre', papellido = '$apellido', ptelefono = $telefono, idviaje = $idViaje WHERE pdocumento = '$documento'";
    return $sql;
}

function eliminarPasajero($documento)
{
    $sql = "DELETE FROM pasajero WHERE pdocumento = '$documento'";
    return $sql;
}

?>