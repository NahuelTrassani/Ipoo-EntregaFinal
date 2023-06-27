<?php

include "datos/Pasajero.php";
include "datos/Viaje.php";
include "datos/ResponsableV.php";
//include "datos/PasajeroVip.php";
//include "datos/PasajeroEspecial.php";
include "datos/BaseDatos.php";
include "datos/Empresa.php";

//VOY A INSTANCIAR TODO AL PRINCIPIO DE LA EJECUCION PARA TENER LAS COLECCIONES DE ENTRADA E IR AGREGANDO INsTANCIAS.
$conx = new BaseDatos();
$resp = $conx->iniciar();
if ($resp == 1) {
    $colEmpresas = inicializarEmpresas($conx);
    $colChoferes = inicializarResponsable($conx);
    $colViajes = inicializarViajes($conx);
    $colPasajeros = inicializarPasajero($conx);
    //una vez que tengo la coleccion de todas las tablas, tengo que instanciar los viajes para las empresas y los pasajeros a los viajes.
    foreach ($colEmpresas as $emp) {

        $idEmpresa = $emp->getIdEmpresa();
        foreach ($colViajes as $viaje) {
            if ($viaje->getIdEmpresa() == $idEmpresa) {
                $emp->setViaje($viaje);
            }
        }
        //aca se agregaron los viajes correspondientes a cada empresa.
        //echo $emp;
    }

    foreach ($colViajes as $viaje) {
        $idViaje = $viaje->getIdViaje();
        foreach ($colPasajeros as $pasajero) {
            if ($pasajero->getVuelo() == $idViaje) {
                $viaje->cargarPasajeroVuelo($pasajero);
            }
        }
        //aca se agregaron los pasajeros correspondientes a cada viaje.
        //echo $viaje;
    }

    //ok. Empieza el programa.
    menu($colEmpresas);

} else {
    echo "Sin conexión para arrancar el programa" . "\n";
}

function menu($colEmpresas)
{
    echo "\n" . "¡Bienvenido/a!" . "\n";
    echo "Seleccione una opción para continuar: " . "\n" . "\n" .

        "       ║       EMPRESA      ║" . "\n" . "\n" .

        "║  1   ║ Cargar Empresa" . "\n" .
        "║  2   ║ Modificar Empresa" . "\n" .
        "║  3   ║ Eliminar Empresa" . "\n" . "\n" .

        "       ║        VIAJE        ║" . "\n" . "\n" .

        "║  4   ║ Cargar Viaje" . "\n" .
        "║  5   ║ Modificar Viaje" . "\n" .
        "║  6   ║ Eliminar Viaje" . "\n" .
        "║  7   ║ Buscar Viaje" . "\n" . "\n" .

        "       ║      RESPONSABLE     ║" . "\n" . "\n" .

        "║  8   ║ Cargar Chofer/Piloto" . "\n" . "\n" .

        "       ║        PASAJERO       ║" . "\n" . "\n" .

        "║  9   ║ Cargar Pasajero" . "\n" .
        "║  10  ║ Modificar Pasajero" . "\n" .
        "║  11  ║ Buscar Pasajero" . "\n" . "\n" .


        "     ║         SALIR        ║" . "\n" . "\n" .
        "║  0  ║ Salir                               " . "\n";


    $opcion = fgets(STDIN);


    switch ($opcion) {
        case 0:
            exit; //finalizar ejecución.
        case 1:
            echo "eligió la opción 'Cargar Empresa'" . "\n";

            $empresa = new Empresa();
            $newEmp = $empresa->agregarEmpresa();
            $colEmpresas[] = $newEmp;
            echo "Los datos de la empresa cargada son: " . $newEmp . "\n";

            //cargué la empresa en la base de datos y tambien se agregó a la coleccion de empresas que tengo en memoria

            menu($colEmpresas);
            break;
        case 2:
            echo "eligió la opción 'Modificar Empresa'" . "\n";
            $empresa = new Empresa();
            $updEmp = $empresa->modificarEmpresa();
            foreach ($colEmpresas as $key => $emp) {
                if ($emp->getIdEmpresa() == $updEmp->getIdEmpresa()) {
                    $colEmpresas[$key] = $updEmp;
                }
            }
            //modifiqué la empresa en bd y tambien la instancia en col empresas 

            menu($colEmpresas);
            break;
        case 3:
            echo "eligió la opción 'Eliminar Empresa'" . "\n";
            $empresa = new Empresa();
            $dltEmp = $empresa->eliminarEmpresa();
            echo "la empresa q se borró tiene id: " . $dltEmp . "\n";
            foreach ($colEmpresas as $key => $emp) {
                if ($emp->getIdEmpresa() == $dltEmp) {
                    unset($colEmpresas[$key]);
                }
            }
            //eliminé la empresa de la bd y tambien borré su aparición en la coleccion.

            menu($colEmpresas);
            break;
        case 4:
            echo "eligió la opción 'Cargar Viaje'" . "\n";
            //ahora toca implementar para cada viaje, contenido dentro del arreglo $colEmpresas.
            $viaje = new Viaje();
            $newViaje = $viaje->agregarViaje();
            $id = $newViaje->getIdEmpresa();
            foreach ($colEmpresas as $empresa) {
                if ($empresa->getIdEmpresa() == $id) {
                    $empresa->setViaje($newViaje);
                }
            }
            echo "Los datos del viaje cargado son: " . $newViaje . "\n";
            //agregué un viaje en la bd, tambien en la coleccion de viajes de la empresa.

            menu($colEmpresas);
            break;
        case 5:
            echo "eligió la opción 'Modificar Viaje'" . "\n";
            $viaje = new Viaje();
            $updViaje = $viaje->modificarViaje();
            foreach ($colEmpresas as $empresa) {
                $colViajes = $empresa->getViajes();
                foreach ($colViajes as $key => $viaje) {
                    if ($viaje->getId() == $updViaje->getId()) {
                        $colViajes[$key] = $updViaje; // Actualizar el viaje en la colección de viajes de la empresa
                        break; // Salir del bucle una vez que se ha encontrado el viaje
                    }
                }

                $empresa->setViaje($colViajes); // Actualizar la colección de viajes de la empresa
            }
            //modifiqué un viaje en la bd y su aparicion en la coleccion viajes de la empresa.


            menu($colEmpresas);
            break;
        case 6:
            echo "eligió la opción 'Eliminar Viaje'" . "\n";
            $viaje = new Viaje();
            $dltViaje = $viaje->eliminarViaje();
            echo "el viaje q se borró tiene id: " . $dltViaje . "\n";
            foreach ($colEmpresas as $empresa) {
                $colViajes = $empresa->getViajes();

                foreach ($colViajes as $key => $viaje) {
                    if ($viaje->getId() == $dltViaje) {
                        unset($colViajes[$key]); // Eliminar el viaje de la colección de viajes de la empresa
                        break; // Salir del bucle una vez que se ha encontrado y eliminado el viaje
                    }
                }

                $empresa->setViaje($colViajes); // Actualizar la colección de viajes de la empresa
            }
            //eliminé el viaje de la bd, entonces elimina la aparición del viaje en la coleccion de viajes de la empresa
            //print_r($colEmpresas);

            menu($colEmpresas);
            break;
        case 7:
            echo "Eligió la opción 'Buscar Viaje'" . "\n";
            $viaje = new Viaje();
            $respSql = $viaje->buscarViajeV2($colEmpresas);
            if (!empty($respSql)) {
                print_r($respSql);
            }
            menu($colEmpresas);
            break;
        case 8:
            echo "eligió la opción 'Cargar Responsable Viaje'" . "\n";
            $chofer = new ResponsableV();
            $newChofer = $chofer->cargarResponsable();
            echo "Los datos del responsable cargado son: " . $newChofer . "\n";
            menu($colEmpresas);
            break;
        case 9:
            echo "eligió la opción 'Cargar Pasajero'" . "\n";
            $pasajero = new Pasajero();
            $newPasajero = $pasajero->agregarPasajero();
            if (!empty($newPasajero)) {
                //obtener id viaje del pasajero
                $idViaje = $newPasajero->getVuelo();

                foreach ($colEmpresas as $emp) {
                    $colViajesEmp = $emp->getViajes();
                    foreach ($colViajesEmp as $viajesEmp) {
                        if ($viajesEmp->getIdViaje() == $idViaje) {
                            $colPasajeros = $viajesEmp->getPasajeros();
                            $colPasajeros[] = $newPasajero; // Agregar el nuevo pasajero a la colección de pasajeros del viaje
                            $viajesEmp->setPasajeros($colPasajeros); // Actualizar la colección de pasajeros del viaje
                            echo "Los datos del pasajero cargado son: " . $newPasajero . "\n";
                            break; // Salir del bucle una vez que se ha encontrado el viaje correspondiente
                        }
                    }
                }
            }

            //print_r($colEmpresas);
            menu($colEmpresas);
            break;
        case 10:
            echo "eligió la opción 'Modificar Pasajero'" . "\n";
            echo "Ingrese el dni del pasajero que desea modificar" . "\n";
            $documento = trim(fgets(STDIN));
            $pasajero = new Pasajero();
            $respSql = $pasajero->buscarPasajerov2($colEmpresas, $documento);
            $dniPasj = $respSql->getDni();
            $oldIdViaje = $respSql->getVuelo();
            $colEmpresas = $pasajero->modificarPasajero($dniPasj, $oldIdViaje, $colEmpresas);
            //print_r($colEmpresas);
            menu($colEmpresas);
            break;
        case 11:
            echo "eligió la opción 'Buscar Pasajero'" . "\n";
            echo "Ingrese el dni del pasajero que desea buscar" . "\n";
            $documento = trim(fgets(STDIN));
            $pasajero = new Pasajero();
            $respPasajero = $pasajero->buscarPasajerov2($colEmpresas, $documento);
            echo "El pasajero encontrado: " . "\n" . $respPasajero . "\n";
            menu($colEmpresas);
            break;
        default:
            echo "Debe elegir una opción valida";
    }
}


function inicializarEmpresas($conx)
{
    $sql = "SELECT * FROM empresa";
    $colEmpresasData = $conx->EjecutarConRetornoBidimensional($sql);
    $colEmpresas = array(); // Colección de instancias de la clase Empresa

    foreach ($colEmpresasData as $empresaData) {
        $empresa = new Empresa();
        $empresa->setIdEmpresa($empresaData['idempresa']);
        $empresa->setEnombre($empresaData['enombre']);
        $empresa->setEdireccion($empresaData['edireccion']);

        $colEmpresas[] = $empresa;
    }
    return $colEmpresas;
}

function inicializarViajes($conx)
{
    $sql2 = "SELECT * FROM viaje";
    $colViajesData = $conx->EjecutarConRetornoBidimensional($sql2); //el array de viajes debe tener la col pasajeros.
    foreach ($colViajesData as $viajesData) {
        $viaje = new Viaje();
        $viaje->setIdViaje($viajesData['idviaje']);
        $viaje->setDestino($viajesData['vdestino']);
        $viaje->setCantMaxPasajeros($viajesData['vcantmaxpasajeros']);
        $viaje->setResponsable($viajesData['rnumeroempleado']);
        $viaje->setCostoViaje($viajesData['vimporte']);
        $viaje->setCantPasajeros($viajesData['cantTotalPasajeros']);
        $viaje->setIdEmpresa($viajesData['idempresa']);
        $colViajes[] = $viaje;
    }
    return $colViajes;
}

function inicializarResponsable($conx)
{
    $sql3 = "SELECT * FROM responsable";
    $colChoferesData = $conx->EjecutarConRetornoBidimensional($sql3);
    foreach ($colChoferesData as $choferesData) {
        $responsable = new ResponsableV();
        $responsable->setIdEmpleado($choferesData['rnumeroempleado']);
        $responsable->setNumLicencia($choferesData['rnumerolicencia']);
        $responsable->setNombre($choferesData['rnombre']);
        $responsable->setApellido($choferesData['rapellido']);


        $colResponsables[] = $responsable;
    }
    return $colResponsables;
}

function inicializarPasajero($conx)
{
    $sql4 = "SELECT * FROM pasajero";
    $colPasajerosData = $conx->EjecutarConRetornoBidimensional($sql4);
    foreach ($colPasajerosData as $pasajerosData) {
        $pasajero = new Pasajero();
        $pasajero->setDni($pasajerosData['pdocumento']);
        $pasajero->setNombre($pasajerosData['pnombre']);
        $pasajero->setApellido($pasajerosData['papellido']);
        $pasajero->setTelefono($pasajerosData['ptelefono']);
        $pasajero->setNroVuelo($pasajerosData['idviaje']);

        $colPasajeros[] = $pasajero;
    }
    return $colPasajeros;
}

?>