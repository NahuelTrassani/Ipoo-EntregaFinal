<?php

$servername = "localhost"; // Nombre del servidor
$username = "root"; // Nombre de usuario de MySQL
$password = "112233"; // Contraseña de MySQL


// Crear la conexión
$conn = new mysqli($servername, $username, $password);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
echo "Conexión exitosa";

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

$dbname = "EmpresaViajes";
// Seleccionar la base de datos
$conn->select_db($dbname);
/*

// Crear la tabla 'empresa'
$sql = "CREATE TABLE empresa(
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'empresa' creada exitosamente";
} else {
    echo "Error al crear la tabla 'empresa': " . $conn->error;
}


// Crear la tabla 'responsable'
$sql = "CREATE TABLE responsable (
    rnumeroempleado bigint AUTO_INCREMENT,
    rnumerolicencia bigint,
	rnombre varchar(150), 
    rapellido  varchar(150), 
    PRIMARY KEY (rnumeroempleado)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'responsable' creada exitosamente";
} else {
    echo "Error al crear la tabla 'responsable': " . $conn->error;
}


// Crear la tabla 'viaje'
$sql = "CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT, //codigo de viaje
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    cantTotalPasajeros int,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT = 1;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'viaje' creada exitosamente";
} else {
    echo "Error al crear la tabla 'viaje': " . $conn->error;
}
*/

// Crear la tabla 'pasajero'
$sql = "CREATE TABLE pasajero (
    pdocumento varchar(15),
    pnombre varchar(150), 
    papellido varchar(150), 
	ptelefono int, 
	idviaje bigint,
    PRIMARY KEY (pdocumento),
	FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)	
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'pasajero' creada exitosamente";
} else {
    echo "Error al crear la tabla 'pasajero': " . $conn->error;
}

?>