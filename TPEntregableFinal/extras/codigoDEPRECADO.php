<?php



//ejemplo de conexion y listar empresas alojadas en bd.

/*
$conx = new BaseDatos();
$resp = $conx->iniciar();
//$sql = $conx->insertarEmpresa('emp3','dir3');
//$respSql =$conx->Ejecutar($sql);


// Llamada a la función listarEmpresas y obtención de la consulta SQL
$sqlListarEmpresas = $conx->listarEmpresas();

// Ejecución de la consulta
$resultadoListarEmpresas = $conx->Ejecutar($sqlListarEmpresas);

if ($resultadoListarEmpresas) {
    if ($conx->getResult()->num_rows > 0) {
        // Iterar sobre los registros
        while ($fila = $conx->getResult()->fetch_assoc()) {
            // Acceder a los valores de cada columna
            $idEmpresa = $fila['idempresa'];
            $nombre = $fila['enombre'];
            $direccion = $fila['edireccion'];

            // Mostrar la información
            echo "ID Empresa: $idEmpresa\n";
            echo "Nombre: $nombre\n";
            echo "Dirección: $direccion\n";
            echo "\n";
        }
    } else {
        echo "No se encontraron resultados";
    }
} else {
    echo "Error al listar empresas: " . $conx->ERROR;
}
$opcion = 0;
*/













//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 













//cargar pasajero DEPRECADO

 /*
            echo "Para cargar una persona, antes debe indicar a continuación el vuelo donde desea ubicarlo: " . "\n";
            $nroVuelo = trim(fgets(STDIN));
            $vuelo = buscarViaje($listaViajes, $nroVuelo);
            //busca el vuelo
            if (gettype($vuelo) === 'object' && get_class($vuelo) === 'Viaje') {
                if ($vuelo->hayPasajesDisponible()) {
                    echo "Hay pasajes disponibles para el vuelo seleccionado" . "\n";
                    //si encontro el vuelo debe revisar que el pasajero no exista aun-.

                    $pasajerosVuelo = $vuelo->getPasajeros();
                    if (!empty($pasajerosVuelo)) {
                        //si encontró pasajeros busca el indicado
                        echo "Ingrese el dni del pasajero que desea cargar: " . "\n";
                        $n = trim(fgets(STDIN));
                        $condicion = validarPasajeroEnViaje($pasajerosVuelo, $n);
                        //si lo encuentra no lo puede volver a cargar
                        if ($condicion) {
                            echo "El pasajero ya se encuentra cargado en el vuelo" . "\n";
                        } else {


                            //si encontró el vuelo y el pasajero no se encuentra en el, lo carga
                            $pasajero = agregarPasajero($n, $listaViajes, $nroVuelo);

                            echo "Indique que tipo de pasaje quiere comprar:\n" . "1. VIP\n" . "2. Necesidades Especiales\n";
                            $tipoPasaje = fgets(STDIN);

                            if ($tipoPasaje == 1) {
                                echo "Indique su número de viajero frecuente: ";
                                $numVip = fgets(STDIN);

                                echo "Indique la cantidad de millas que tiene acumuladas: ";
                                $cantMillas = fgets(STDIN);

                                $objPasajero1 = new PasajeroVIP($pasajero->getDni(), $pasajero->getNombre(), $pasajero->getApellido(), $pasajero->getTelefono(), $pasajero->getNroVuelo(), $pasajero->getNumeroAsiento(), $pasajero->getNumeroTicket(), $numVip, $cantMillas);
                                //vender pasaje
                                $vuelo->venderPasaje($objPasajero1);


                            } elseif ($tipoPasaje == 2) {
                                echo "Indique si utiliza silla de ruedas:\n " . "1. Si\n" . "2. No\n";
                                $sillaRuedas = fgets(STDIN);

                                echo "Indique si necesita asistencia para embarcar:\n " . "1. Si\n" . "2. No\n";
                                $asistenciaEmbarque = fgets(STDIN);

                                echo "Indique si necesita comida especial:\n " . "1. Si\n" . "2. No\n";
                                $comidaEsp = fgets(STDIN);

                                $objPasajero2 = new PasajeroNecesidadesEspeciales($pasajero->getDni(), $pasajero->getNombre(), $pasajero->getApellido(), $pasajero->getTelefono(), $pasajero->getNroVuelo(), $pasajero->getNumeroAsiento(), $pasajero->getNumeroTicket(), $sillaRuedas, $asistenciaEmbarque, $comidaEsp);
                                //vender pasaje
                                $vuelo->venderPasaje($objPasajero2);
                                //agrega el pasajero a la coleccion de pasajeros en un vuelo.
                                $vuelo->cargarPasajeroVuelo($objPasajero2);
                                $vuelo->cuentaCantPasajeros(1);
                            } else {
                                //vender pasaje
                                $vuelo->venderPasaje($pasajero);
                                //agrega el pasajero a la coleccion de pasajeros en un vuelo.
                                $vuelo->cargarPasajeroVuelo($pasajero);
                                $vuelo->cuentaCantPasajeros(1);
                            }

                        }
                    } else {
                        //si encontró el vuelo y el pasajero no se encuentra en el, lo carga
                        $pasajeroNew = agregarPasajero("", $listaViajes, $nroVuelo);

                        echo "Indique que tipo de pasaje quiere comprar:\n" . "1. VIP\n" . "2. Necesidades Especiales\n";
                        $tipoPasaje = fgets(STDIN);

                        if ($tipoPasaje == 1) {
                            echo "Indique su número de viajero frecuente: ";
                            $numVip = fgets(STDIN);

                            echo "Indique la cantidad de millas que tiene acumuladas: ";
                            $cantMillas = fgets(STDIN);

                            $objPasajero1 = new PasajeroVIP($pasajeroNew->getDni(), $pasajeroNew->getNombre(), $pasajeroNew->getApellido(), $pasajeroNew->getTelefono(), $pasajeroNew->getNroVuelo(), $pasajeroNew->getNumeroAsiento(), $pasajeroNew->getNumeroTicket(), $numVip, $cantMillas);
                            //vender pasaje
                            $vuelo->venderPasaje($objPasajero1);
                            //agrega el pasajero a la coleccion de pasajeros en un vuelo.
                            $vuelo->cargarPasajeroVuelo($objPasajero1);
                            $vuelo->cuentaCantPasajeros(1);

                        } elseif ($tipoPasaje == 2) {
                            echo "Indique si utiliza silla de ruedas:\n " . "1. Si\n" . "2. No\n";
                            $sillaRuedas = fgets(STDIN);

                            echo "Indique si necesita asistencia para embarcar:\n " . "1. Si\n" . "2. No\n";
                            $asistenciaEmbarque = fgets(STDIN);

                            echo "Indique si necesita comida especial:\n " . "1. Si\n" . "2. No\n";
                            $comidaEsp = fgets(STDIN);

                            $objPasajero2 = new PasajeroNecesidadesEspeciales($pasajeroNew->getDni(), $pasajeroNew->getNombre(), $pasajeroNew->getApellido(), $pasajeroNew->getTelefono(), $pasajeroNew->getNroVuelo(), $pasajeroNew->getNumeroAsiento(), $pasajeroNew->getNumeroTicket(), $sillaRuedas, $asistenciaEmbarque, $comidaEsp);
                            //vender pasaje
                            $vuelo->venderPasaje($objPasajero2);
                            //agrega el pasajero a la coleccion de pasajeros en un vuelo.
                            $vuelo->cargarPasajeroVuelo($objPasajero2);
                            $vuelo->cuentaCantPasajeros(1);
                        } else {
                            echo "Error ingresando tipo de pasajero\n";
                        }
                    }
                } else {
                    echo "No hay pasajes disponibles para el vuelo seleccionado" . "\n";
                    // ...
                }
            } else {
                echo "No se encontro el vuelo" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros);
            //vuelve a llamar al menu con las colecciones.
            break;
            */



              /*
            //MODIFICAR PERSONA.  DEPRECADO
            echo "Ingrese el dni del pasajero que desea modificar" . "\n";
            $n = trim(fgets(STDIN));
            $pers1 = buscarPasajero($listaPasajeros, $n);
            if (!empty($pers1)) {
                echo "Se encontro al pasajero con " . "\n" . "Nombre: " . $pers1->getNombre() . "\n" . " Apellido: " . $pers1->getApellido() . "\n" . "Nro de vuelo: " . $pers1->getVuelo() . "\n";
                modificarPasajero($listaPasajeros, $n);
            } else {
                echo "No se encontró al pasajero" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros);*/





//buscar PERSONA. DEPRECADO
            /*
            echo "Ingrese el dni del pasajero que desea buscar" . "\n";
            $n = trim(fgets(STDIN));
            $pers1 = buscarPasajero($listaPasajeros, $n);
            if (!empty($pers1)) {
                // echo "Se encontro al pasajero con " . "\n" . "Nombre: " . $pers1->getNombre() . "\n" . " Apellido: " . $pers1->getApellido() . "\n" . "Nro de vuelo: " . $pers1->getVuelo() . "\n";
            } else {
                echo "No se encontró al pasajero" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros);*/







//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 
//DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO DEPRECADO 








            
/*
 

function buscarPasajero($listaPasajeros, $n)
{
    for ($i = 0; $i < count($listaPasajeros); $i++) {
        $persona = $listaPasajeros[$i];
        if (trim($persona->getDni()) === $n) {
            return $persona;
        }
    }
}

/*
function validarPasajeroEnViaje($listaPasajeros, $n)
{
    $condicion = false;
    for ($i = 0; $i < count($listaPasajeros); $i++) {

        $persona = $listaPasajeros[$i];
        if (trim($persona->getDni()) === $n) {
            //echo "Se encontro al pasajero con " . "\n". "Nombre: " . $persona->getNombre() . "\n". " Apellido: " . $persona->getApellido() ."\n". "Nro de vuelo: ".$persona->getVuelo()."\n";
            $condicion = true;
        }

    }
    return $condicion;
}

/*

function modificarPasajero($listaPasajeros, $n)
{

    for ($i = 0; $i < count($listaPasajeros); $i++) {

        $persona = $listaPasajeros[$i];
        if (trim($persona->getDni()) === $n) {

            echo "Indique el nombre del pasajero: ";
            $nombre = trim(fgets(STDIN));
            $persona->setNombre($nombre);

            echo "Indique el apellido del pasajero: ";
            $apellido = trim(fgets(STDIN));
            $persona->setApellido($apellido);

            echo "Indique el teléfono del pasajero: ";
            $telefono = fgets(STDIN);
            $persona->setTelefono($telefono);
        }

    }
}


function listarPasajeros($colPasajeros)
{
    for ($i = 0; $i < count($colPasajeros); $i++) {
        $persona = $colPasajeros[$i];
        echo "Datos Pasajero: " . "\n" . "Nombre: " . $persona->getNombre() . "\n" . " Apellido: " . $persona->getApellido() . "\n";
    }

}

*/

?>