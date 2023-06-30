<?php

/*
 * la solicitud de info en el test  ---OOOKKKK
 * delegacion corregir, no es id sino objetos ---OOOKKKK
 * manejo de errores ---OOOKKKK
 * hacer listar 
 * no retorno adentro de un for ---OOOKKKK
 * no haces MOR ---OOOKKKK
 * No modificar la clase base de datos para insert de viaje/y demas tablas, ni insert    ---OOOKKKK
 * no echo ni retorno en la misma funcion ---OOOKKKK
 */

include "datos/Pasajero.php";
include "datos/Viaje.php";
include "datos/ResponsableV.php";
include "datos/BaseDatos.php";
include "datos/Empresa.php";


$pasajeros = new Pasajero();
$colPasajeros = $pasajeros->listar();
print_r($colPasajeros);

/*
$obj_Empresa = new Empresa();

//Busco todas las personas almacenadas en la BD
$colEmpresas = $obj_Empresa->obtenerEmpresas();
foreach ($colEmpresas as $unaEmpresa) {

    echo $unaEmpresa;
    echo "-------------------------------------------------------";
}
$obj_Viaje = new Viaje();

//Busco todas las personas almacenadas en la BD
$colViajes = $obj_Viaje->listar();
foreach ($colViajes as $unViaje) {

    echo $unViaje;
    echo "-------------------------------------------------------";
}
*/


//VOY A INSTANCIAR TODO AL PRINCIPIO DE LA EJECUCION PARA TENER LAS COLECCIONES DE ENTRADA E IR AGREGANDO INsTANCIAS.
//$conx = new BaseDatos();
//$resp = $conx->iniciar();
//if ($resp == 1) {

    /*  $colEmpresas = inicializarEmpresas($conx);
      $colChoferes = inicializarResponsable($conx);
      $colViajes = inicializarViajes($conx);
      $colPasajeros = inicializarPasajero($conx);
      print_r($colPasajeros);
      //una vez que tengo la coleccion de todas las tablas, tengo que instanciar los viajes para las empresas y los pasajeros a los viajes.
     /* foreach ($colEmpresas as $emp) {

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
  */
    //ok. Empieza el programa.
    //menu($colEmpresas);



function menu($colEmpresas)
{
    echo "\n" . "¡Bienvenido/a!" . "\n";
    echo "Seleccione una opción para continuar: " . "\n" . "\n" .

        "       ║       EMPRESA      ║" . "\n" . "\n" .

        "║  1   ║ Cargar Empresa" . "\n" .
        "║  2   ║ Modificar Empresa" . "\n" .
        "║  3   ║ Eliminar Empresa" . "\n" .
        "║  99  ║ Buscar Empresa" . "\n" . "\n" .

        "       ║        VIAJE        ║" . "\n" . "\n" .

        "║  4   ║ Cargar Viaje" . "\n" .
        "║  5   ║ Modificar Viaje" . "\n" .
        "║  6   ║ Eliminar Viaje" . "\n" .
        "║  7   ║ Buscar Viaje" . "\n" . "\n" .

        "       ║      RESPONSABLE     ║" . "\n" . "\n" .

        "║  8   ║ Cargar Chofer/Piloto" . "\n" .
        "║  100 ║ Buscar Chofer/Piloto" . "\n" . "\n" .

        "       ║        PASAJERO       ║" . "\n" . "\n" .

        "║  9   ║ Cargar Pasajero" . "\n" .
        "║  10  ║ Modificar Pasajero" . "\n" .
        "║  11  ║ Buscar Pasajero" . "\n" . "\n" .

        "║  1111  ║ print Empresa" . "\n" . "\n" .

        "║         SALIR        ║" . "\n" . "\n" .
        "║  0  ║ Salir                               " . "\n";


    $opcion = fgets(STDIN);


    switch ($opcion) {
        case 1111:
            print_r($colEmpresas);
            menu($colEmpresas);
            break;
        case 0:
            exit; //finalizar ejecución.
        case 1:
            echo "eligió la opción 'Cargar Empresa'" . "\n";
            echo "Nombre empresa" . "\n";
            $nomEmpresa = fgets(STDIN);

            echo "Dirección empresa" . "\n";
            $dirEmpresa = trim(fgets(STDIN));

            $empresa = new Empresa();
            $newEmp = $empresa->agregarEmpresa($nomEmpresa, $dirEmpresa);
            if ($newEmp) {
                $colEmpresas[] = $newEmp;
                echo "Los datos de la empresa cargada son: " . $newEmp . "\n";

            } else {
                echo "No se pudo cargar la empresa";
            }
            //cargué la empresa en la base de datos y tambien se agregó a la coleccion de empresas que tengo en memoria

            menu($colEmpresas);
            break;
        case 2:
            echo "eligió la opción 'Modificar Empresa'" . "\n";

            echo "Indique el nombre de la empresa que desea modificar" . "\n";
            $nomEmpresa = trim(fgets(STDIN));

            echo "Ingrese un nuevo nombre para la empresa" . "\n";
            $newNomEmpresa = fgets(STDIN);

            echo "Nueva dirección de la empresa" . "\n";
            $dirEmpresa = trim(fgets(STDIN));

            $empresa = new Empresa();
            $updEmp = $empresa->modificarEmpresa($nomEmpresa, $newNomEmpresa, $dirEmpresa);
            if ($updEmp) {
                foreach ($colEmpresas as $key => $emp) {
                    if ($emp->getIdEmpresa() == $updEmp->getIdEmpresa()) {
                        $colEmpresas[$key] = $updEmp;
                    }
                }
            } else {
                echo "No se pudo modificar la empresa";
            }
            //modifiqué la empresa en bd y tambien la instancia en col empresas 

            menu($colEmpresas);
            break;
        case 3:
            echo "eligió la opción 'Eliminar Empresa'" . "\n";
            echo "Indique el nombre de la empresa que desea eliminar" . "\n";
            $nomEmpresa = trim(fgets(STDIN));
            $empresa = new Empresa();
            $dltEmp = $empresa->eliminarEmpresa($nomEmpresa);
            if ($dltEmp) {
                echo "la empresa q se borró tiene id: " . $dltEmp . "\n";
                foreach ($colEmpresas as $key => $emp) {
                    if ($emp->getIdEmpresa() == $dltEmp) {
                        unset($colEmpresas[$key]);
                    }
                }
            } else {
                echo "No se pudo eliminar la empresa";
            }
            //eliminé la empresa de la bd y tambien borré su aparición en la coleccion.

            menu($colEmpresas);
            break;
        case 99:
            //buscar empresa
            echo "Eligió la opción 'Buscar empresa'" . "\n";
            echo "Ingrese el nombre de la empresa que desea buscar: " . "\n";
            $nomEmpresa = trim(fgets(STDIN));
            $empresa = new Empresa();
            $respSql = $empresa->buscarEmpresa($nomEmpresa);
            if ($respSql) {
                echo "A continuación se muestra la empresa encontrada";
                print_r($respSql);
            } else {
                echo "No se encontró la empresa";
            }
            menu($colEmpresas);
            break;
        case 4:
            echo "eligió la opción 'Cargar Viaje'" . "\n";
            //ahora toca implementar para cada viaje, contenido dentro del arreglo $colEmpresas.

            echo "Debe indicar la empresa y el responsable del viaje" . "\n";

            echo "Ingrese el nombre de la empresa: " . "\n";
            $nomEmpresa = trim(fgets(STDIN));
            $empresa = new Empresa();
            $parmEmpresa = $empresa->buscarEmpresa($nomEmpresa);
            if ($parmEmpresa) {
                $idEmpresa = $parmEmpresa->getIdEmpresa();
                echo "Ingrese el nombre del Responsable: " . "\n";
                $nombre = trim(fgets(STDIN));
                $responsable = new ResponsableV();
                $parmResponsable = $responsable->buscarResponsable($nombre);
                if ($parmResponsable) {
                    $idResponsable = $parmResponsable->getIdEmpleado();

                    echo "Indique el destino del viaje: " . "\n";
                    $destino = trim(fgets(STDIN));

                    echo "Indique la capacidad máxima de personas que tiene el viaje: ";
                    $cantMax = fgets(STDIN);

                    echo "Indique el precio del viaje: " . "\n";
                    $costoViaje = fgets(STDIN);

                    $viaje = new Viaje();
                    $newViaje = $viaje->agregarViaje($destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje);
                    if ($newViaje) {
                        $id = $newViaje->getIdEmpresa();
                        foreach ($colEmpresas as $empresa) {
                            if ($empresa->getIdEmpresa() == $id) {
                                $empresa->setViaje($newViaje);
                            }
                        }
                        echo "Los datos del viaje cargado son: " . $newViaje . "\n";
                    } else {
                        echo "No se pudo cargar el viaje";
                    }
                } else {
                    echo "No se encontró responsable";
                }
            } else {
                echo "No se encontró la empresa";
            }
            menu($colEmpresas);
            break;
        case 5:
            echo "eligió la opción 'Modificar Viaje'" . "\n";
            echo "Debe indicar la empresa y el responsable del viaje" . "\n";

            echo "Ingrese el nombre de la empresa: " . "\n";
            $nomEmpresa = trim(fgets(STDIN));
            $empresa = new Empresa();
            $parmEmpresa = $empresa->buscarEmpresa($nomEmpresa);
            if ($parmEmpresa) {
                $idEmpresa = $parmEmpresa->getIdEmpresa();
                echo "Ingrese el nombre del Responsable: " . "\n";
                $nombre = trim(fgets(STDIN));
                $responsable = new ResponsableV();
                $parmResponsable = $responsable->buscarResponsable($nombre);
                if ($parmResponsable) {
                    $idResponsable = $parmResponsable->getIdEmpleado();

                    echo "Indique el id del viaje que desea modificar" . "\n";
                    $idViaje = trim(fgets(STDIN));

                    echo "Ingrese un nuevo destino para el viaje" . "\n";
                    $destino = fgets(STDIN);

                    echo "Indique la cantidad maxima de pasajeros" . "\n";
                    $cantMax = trim(fgets(STDIN));

                    echo "Indique el precio del viaje: " . "\n";
                    $costoViaje = fgets(STDIN);

                    $viaje = new Viaje();
                    $updViaje = $viaje->modificarViaje($idViaje, $destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje);
                    if ($updViaje) {
                        foreach ($colEmpresas as $empresa) {
                            $colViajes = $empresa->getViajes();
                            foreach ($colViajes as $key => $viaje) {
                                if ($viaje->getId() == $updViaje->getId()) {
                                    $colViajes[$key] = $updViaje; // Actualizar el viaje en la colección de viajes de la empresa
                                    break; // Salir del bucle una vez que se ha encontrado el viaje
                                }
                            }
                        }
                        echo "Los datos del viaje modificado son: " . $updViaje . "\n";
                    } else {
                        echo "No se pudo modificar el viaje";
                    }
                } else {
                    echo "No se encontró responsable";
                }
            } else {
                echo "No se encontró la empresa";
            }
            menu($colEmpresas);
            break;
        case 6:
            echo "eligió la opción 'Eliminar Viaje'" . "\n";


            echo "Indique el id del viaje que desea eliminar" . "\n";
            $idViaje = trim(fgets(STDIN));

            $viaje = new Viaje();
            $dltViaje = $viaje->eliminarViaje($idViaje);

            if ($dltViaje) {
                foreach ($colEmpresas as $empresa) {
                    $colViajes = $empresa->getViajes();

                    foreach ($colViajes as $viaje) {
                        if ($viaje->getId() == $idViaje) {
                            $esElViaje = $viaje;
                            break;
                        }
                    }
                    $empresa->eliminarViaje($esElViaje);
                }
                echo "Viaje eliminado con éxito";
            } else {
                echo "No se pudo eliminar el viaje";
            }
            //print_r($colEmpresas);
            menu($colEmpresas);
            break;
        case 7:
            echo "Eligió la opción 'Buscar Viaje'" . "\n";
            echo "Ingrese el 'idViaje' que desea buscar: " . "\n";
            $idViaje = trim(fgets(STDIN));
            $viaje = new Viaje();
            $respSql = $viaje->buscarViaje($idViaje);
            if ($respSql) {
                echo "A continuación se muestra el viaje encontrado";
                print_r($respSql);
            } else {
                echo "No se encontró el viaje";
            }
            menu($colEmpresas);
            break;
        case 8:
            echo "eligió la opción 'Cargar Responsable Viaje'" . "\n";
            echo "Ingrese los datos del responsable del vuelo: " . "\n";

            echo "Numero de licencia" . "\n";
            $numLicencia = fgets(STDIN);

            echo "Nombre" . "\n";
            $nombre = trim(fgets(STDIN));

            echo "Apellido: " . "\n";
            $apellido = trim(fgets(STDIN));

            $chofer = new ResponsableV();
            $newChofer = $chofer->cargarResponsable($numLicencia, $nombre, $apellido);
            if ($newChofer) {
                echo "Los datos del responsable cargado son: " . $newChofer . "\n";
            }
            menu($colEmpresas);
            break;

        case 100:
            echo "Eligió la opción 'Buscar Responsable'" . "\n";
            echo "Ingrese el nombre del Responsable que desea buscar: " . "\n";
            $nombre = trim(fgets(STDIN));
            $responsable = new ResponsableV();
            $respSql = $responsable->buscarResponsable($nombre);
            if ($respSql) {
                echo "A continuación se muestra el responsable encontrado" . $respSql . "\n";
            } else {
                echo "No se encontró el responsable";
            }
            menu($colEmpresas);
            break;
        case 9:
            echo "eligió la opción 'Cargar Pasajero'" . "\n";
            $pasajero = new Pasajero();
            echo "Indique el Dni del pasajero (numérico): " . "\n";
            $dni = trim(fgets(STDIN));
            $respSql = $pasajero->buscarPasajero($dni);
            if ($respSql) {
                echo "el pasajero ya se encuentra cargado. VIAJE EN PASAJERO ES UN FK NO PRIMARY, POR LO TANTO NO PUEDO REPETIR EL DNI, POR ENDE EL PASAJERO NO PUEDE ESTAR EN MAS DE UN VIAJE." . "\n";
            } else {
                echo "Indique el vuelo donde quiere ubicar al pasajero: " . "\n";
                $idViaje = trim(fgets(STDIN));
                $viaje = new Viaje();
                $viajeAux = $viaje->buscarViaje($idViaje);
                if ($viajeAux) {
                    $idViaje = $viajeAux->getIdViaje();
                    $cantMax = $viajeAux->getCantMaxPasajeros();
                    $cantTotal = $viajeAux->getcantPasajeros();
                    $costo = $viajeAux->getCostoViaje();
                    if ($cantTotal < $cantMax) {
                        echo "Indique el nombre del pasajero: " . "\n";
                        $nombre = trim(fgets(STDIN));

                        echo "Indique el apellido del pasajero: " . "\n";
                        $apellido = trim(fgets(STDIN));

                        echo "Indique el teléfono del pasajero: " . "\n";
                        $telefono = fgets(STDIN);

                        $insPasajero = $pasajero->agregarPasajero($dni, $nombre, $apellido, $telefono, $idViaje);
                        if (!empty($insPasajero)) {
                            $viaje->venderPasaje($idViaje, $costo);

                            foreach ($colEmpresas as $emp) {
                                $colViajesEmp = $emp->getViajes();
                                foreach ($colViajesEmp as $viajesEmp) {
                                    if ($viajesEmp->getIdViaje() == $idViaje) {
                                        $colPasajeros = $viajesEmp->getPasajeros();
                                        $colPasajeros[] = $insPasajero; // Agregar el nuevo pasajero a la colección de pasajeros del viaje
                                        $viajesEmp->setPasajeros($colPasajeros); // Actualizar la colección de pasajeros del viaje
                                        echo "Éxito! Los datos del pasajero cargado son: " . $insPasajero . "\n";
                                        break; // Salir del bucle una vez que se ha encontrado el viaje correspondiente
                                    }
                                }
                            }

                        } else {
                            echo "No se pudo cargar al pasajero";
                        }

                    } else {
                        echo "El viaje llegó al límite de su capacidad" . "\n";
                    }
                } else {
                    echo "No se encontró el viaje";
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
            $respPasajero = $pasajero->buscarPasajero($documento);
            if ($respPasajero) {
                $dniPasj = $respPasajero->getDni();
                $oldIdViaje = $respPasajero->getVuelo();
                echo "Indique el vuelo donde quiere ubicar al pasajero: " . "\n";
                $idViaje = trim(fgets(STDIN));
                $viaje = new Viaje();
                $viajeAux = $viaje->buscarViaje($idViaje);
                if ($viajeAux) {
                    $idViaje = $viajeAux->getIdViaje();
                    $cantMax = $viajeAux->getCantMaxPasajeros();
                    $cantTotal = $viajeAux->getcantPasajeros();
                    $costo = $viajeAux->getCostoViaje();
                    if ($cantTotal < $cantMax) {
                        echo "Indique el nombre del pasajero: " . "\n";
                        $nombre = trim(fgets(STDIN));

                        echo "Indique el apellido del pasajero: " . "\n";
                        $apellido = trim(fgets(STDIN));

                        echo "Indique el teléfono del pasajero: " . "\n";
                        $telefono = fgets(STDIN);
                        //$colEmpresas = $pasajero->modificarPasajero($dniPasj, $oldIdViaje, $colEmpresas,$nombre, $apellido, $telefono);

                        $updPasajero = $pasajero->modificarPasajero($dniPasj, $idViaje, $nombre, $apellido, $telefono);

                        //borrar al pasajero de la coleccion del viaje y actualizar

                        /*
                        podria preguntar al pasajero si quiere cambiar de vuelo para poder reubicarlo, 
                        pero no lo voy a dejar, para eso se debe eliminar el pasajero del vuelo y volver a cargar
               */



                        //$colEmpresas = $pasajero->modificarPasajero($dniPasj, $oldIdViaje, $colEmpresas, $nombre, $apellido, $telefono);
                        //$updPasajero = $pasajero->modificarPasajero($dniPasj, $idViaje, $nombre, $apellido, $telefono);

                    } else {
                        echo "No se encontró al pasajero";
                    }
                }
            }
            //print_r($colEmpresas);
            menu($colEmpresas);
            break;
        case 11:
            echo "eligió la opción 'Buscar Pasajero'" . "\n";
            echo "Ingrese el dni del pasajero que desea buscar" . "\n";
            $documento = trim(fgets(STDIN));
            $pasajero = new Pasajero();
            $respPasajero = $pasajero->buscarPasajero($documento);
            if ($respPasajero) {
                echo "El pasajero encontrado: " . "\n" . $respPasajero . "\n";
            } else {
                echo "No se encontró al pasajero";
            }
            menu($colEmpresas);
            break;
        default:
            echo "Debe elegir una opción valida";
    }


}



/*
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

    $colPasajeros = []; // Colección para almacenar las instancias de Pasajero

    foreach ($colPasajerosData as $pasajerosData) {
        $pasajeroData = [
            'dni' => $pasajerosData['pdocumento'],
            'nombre' => $pasajerosData['pnombre'],
            'apellido' => $pasajerosData['papellido'],
            'telefono' => $pasajerosData['ptelefono'],
            'nroVuelo' => $pasajerosData['idviaje']
        ];

        $colPasajeros[] = $pasajeroData;
    }
    
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
}*/


?>