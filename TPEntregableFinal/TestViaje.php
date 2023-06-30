<?php

/*
 * la solicitud de info en el test  ---OOOKKKK
 * delegacion corregir, no es id sino objetos ---OOOKKKK
 * manejo de errores ---OOOKKKK
 * hacer listar ---OOOKKKK
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

$empresa = new Empresa();
$colEmpresas = $empresa->listar();
//print_r($colEmpresas);

//ok. Empieza el programa.
menu($colEmpresas);




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
            if ($dltEmp > 0) {
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
                //echo "el id de la empresa seleccionada es: ". $idEmpresa. "\n";
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
                            if ($empresa->getIdEmpresa() == $idEmpresa) {
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

                            //print_r($colViajes); //no guarda el viaje en la col
                            foreach ($colViajes as $key => $viajeAux) { {
                                    if ($viajeAux->getId() == $updViaje->getId()) {
                                        $colViajes[$key] = $updViaje; // Actualizar el viaje en la colección de viajes de la empresa
                                        break; // Salir del bucle una vez que se ha encontrado el viaje
                                    }

                                }
                            }
                            // Actualizar la colección de viajes en la instancia de la empresa
                            $empresa->setViaje($colViajes);
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
                        $empresa->eliminarViaje($esElViaje);
                    }
                    echo "Viaje eliminado con éxito";
                }
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

                        //borrar pasajero col vieja e insertarlo en la col nueva
                        foreach ($colEmpresas as $emp) {
                            $colViajesEmp = $emp->getViajes();
                            foreach ($colViajesEmp as $viajesEmp) {
                                if ($viajesEmp->getIdViaje() == $oldIdViaje) {
                                    $colPasajeros = $viajesEmp->getPasajeros();
                                    foreach ($colPasajeros as $key => $pasajero) {
                                        if ($pasajero->getDni() == $dniPasj) {
                                            unset($colPasajeros[$key]); // Eliminar el pasajero de la colección
                                            break; // Salir del bucle una vez que se ha encontrado y eliminado el pasajero
                                        }
                                    }
                                    $viajesEmp->setPasajeros($colPasajeros); // Actualizar la colección de pasajeros del viaje
                                    echo "Éxito! El pasajero ha sido eliminado.\n";
                                }
                            }
                        }
                        //inserta al pasajero en la nueva col de viajes.
                        foreach ($colEmpresas as $emp) {
                            $colViajesEmp = $emp->getViajes();
                            foreach ($colViajesEmp as $viajesEmp) {
                                if ($viajesEmp->getIdViaje() == $idViaje) {
                                    $colPasajeros = $viajesEmp->getPasajeros();
                                    $colPasajeros[] = $updPasajero; // Agregar el nuevo pasajero a la colección de pasajeros del viaje
                                    $viajesEmp->setPasajeros($colPasajeros); // Actualizar la colección de pasajeros del viaje
                                    echo "Éxito! Los datos del pasajero cargado son: " . $updPasajero . "\n";
                                    break; // Salir del bucle una vez que se ha encontrado el viaje correspondiente
                                }
                            }
                        }

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


?>