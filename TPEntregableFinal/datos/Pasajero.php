<?php

class Pasajero
{
    private $dni;
    private $nombre;
    private $apellido;
    private $telefono;
    private $nroVuelo;
    private $numeroAsiento;
    private $numeroTicket;

    //
    //GETTERS
    // 

    public function darPorcentajeIncremento()
    {
        return 10; // Porcentaje de incremento para pasajeros comunes
    }

    //recupera dni
    public function __toString()
    {
        return "{$this->dni}" . "\n" . "{$this->nombre}" . "\n" . "{$this->apellido}";
    }
    public function getVuelo()
    {
        return $this->nroVuelo;
    }
    public function getDni()
    {
        return $this->dni;
    }

    // recupera nombre

    public function getNombre()
    {
        return $this->nombre;
    }


    // recupera apellido

    public function getApellido()
    {
        return $this->apellido;
    }


    // recupera telefono

    public function getTelefono()
    {
        return $this->telefono;
    }



    //
    //SETTERS
    // 

    //Establece el valor de documento

    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    public function setVuelo($nroVuelo)
    {
        $this->nroVuelo = $nroVuelo;
    }
    // Establece el valor de nombre

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    // Establece el valor de apellido

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }


    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function getNroVuelo()
    {
        return $this->nroVuelo;
    }

    public function setNroVuelo($nroVuelo)
    {
        $this->nroVuelo = $nroVuelo;
    }

    public function getNumeroAsiento()
    {
        return $this->numeroAsiento;
    }

    public function setNumeroAsiento($numeroAsiento)
    {
        $this->numeroAsiento = $numeroAsiento;
    }

    public function getNumeroTicket()
    {
        return $this->numeroTicket;
    }

    public function setNumeroTicket($numeroTicket)
    {
        $this->numeroTicket = $numeroTicket;
    }

    public function __construct()
    {
        $this->dni = 0;
        $this->nombre = "";
        $this->apellido = "";
        $this->telefono = 0;
        $this->nroVuelo = 0;
        $this->numeroAsiento = 0;
        $this->numeroTicket = 0;
    }

    public function cargarPersona($dni, $nombre, $apellido, $telefono, $nroVuelo)
    {
        $this->setDni($dni);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setTelefono($telefono);
        $this->setVuelo($nroVuelo);
    }

    function agregarPasajero()
    {

        echo "Indique el Dni del pasajero (numérico): " . "\n";
        $dni = trim(fgets(STDIN));
        $respSql = $this->buscarPasajero($dni);
        if ($respSql) {
            echo "el pasajero ya se encuentra cargado. VIAJE EN PASAJERO ES UN FK NO PRIMARY, POR LO TANTO NO PUEDO REPETIR EL DNI, POR ENDE EL PASAJERO NO PUEDE ESTAR EN MAS DE UN VIAJE." . "\n";
        } else {

            echo "Indique el nombre del pasajero: " . "\n";
            $nombre = trim(fgets(STDIN));

            echo "Indique el apellido del pasajero: " . "\n";
            $apellido = trim(fgets(STDIN));

            echo "Indique el teléfono del pasajero: " . "\n";
            $telefono = fgets(STDIN);

            echo "SE BUSCARA EL VIAJE PARA INSERTAR AL PASAJERO." . "\n";
            echo "TENGA EN CUENTA QUE EL ID DEBE EXISTIR EN LA TABLA CORRESPONDIENTE PORQUE NO ESTOY VALIANDO QUE HAYA ERROR." . "\n";
            $viaje = new Viaje();
            $respSql = $viaje->buscarViaje();
            $idViaje = $respSql['idviaje'];
            $cantMax = $respSql['vcantmaxpasajeros'];
            $cantTotal = $respSql['cantTotalPasajeros'];
            if ($cantTotal < $cantMax) {
                $conx = new BaseDatos();
                $resp = $conx->iniciar();
                if ($resp == 1) {
                    $sql = $conx->insertarPasajero($dni, $nombre, $apellido, $telefono, $idViaje);
                    $respSql = $conx->Ejecutar($sql);
                    if ($respSql == 1) {
                        $this->cargarPersona($dni, $nombre, $apellido, $telefono, $idViaje);
                        $costo = $this->darPorcentajeIncremento();
                        $respuesta = $viaje->venderPasajeColeccion($costo);
                        $viaje->venderPasaje($idViaje);
                        echo "Pasajero cargado con éxito" . "\n";
                        echo "el monto total a pagar es  " . $respuesta . "\n";
                        return $this;
                    } else {
                        echo "Error insertando Pasajero" . "\n";
                    }
                } else {
                    echo "Error conectando a la bd" . "\n";
                }
            } else {
                echo "El viaje llegó al límite de su capacidad" . "\n";
            }

        }
    }

    function buscarPasajero($documento)
    {

        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->buscarPasajero($documento);
            $respSql = $conx->EjecutarConRetornoBidimensional($sql);
            if ($respSql !== false) {
                // La consulta se ejecutó correctamente y se obtuvo un resultado
                if (!empty($respSql)) { //si no está vacio muestra el pasajero encontrado, de lo contrario avisa que no coincide la busqueda.
                    print_r($respSql);
                    // $pasajero = $respSql['pdocumento'];

                    return $respSql;
                } else {
                    echo "No se encontró pasajero para ese dni" . "\n";
                }
            } else {
                echo "la consulta no se ejecutó correctamente" . "\n";
            }
        }
    }

    function buscarPasajeroV2($colEmpresas, $documento)
    {
        foreach ($colEmpresas as $empresas) {
            $viajes = $empresas->getViajes();
            foreach ($viajes as $viaje) {
                $pasajeros = $viaje->getPasajeros();
                foreach ($pasajeros as $pasajero) {
                    if ($pasajero->getDni() == $documento) {
                        return $pasajero;
                    }
                }
            }
        }
    }

    function modificarPasajero($documento, $oldIdViaje, $colEmpresas)
    {

        echo "Indique el nombre del pasajero: " . "\n";
        $nombre = trim(fgets(STDIN));

        echo "Indique el apellido del pasajero: " . "\n";
        $apellido = trim(fgets(STDIN));

        echo "Indique el teléfono del pasajero: " . "\n";
        $telefono = fgets(STDIN);

        echo "SE BUSCARA EL VIAJE PARA INSERTAR AL PASAJERO." . "\n";
        echo "TENGA EN CUENTA QUE EL ID DEBE EXISTIR EN LA TABLA CORRESPONDIENTE PORQUE NO ESTOY VALIANDO QUE HAYA ERROR." . "\n";

        $viaje = new Viaje();
        $respSql = $viaje->buscarViaje();
        $idViaje = $respSql['idviaje'];
        $isOk = false;

        if ($oldIdViaje == $idViaje) { //si cambia de vuelo tiene que eliminarlo del vuelo anterior y depositarlo en el nuevo vuelo
            $isOk = true;
        } else {
            $isOk = false;
            //echo "eliminiar al usuario de la col viajes para insertarlo en la col viajes nueva asignada.";
            $this->borrarPasajeroV2($colEmpresas, $documento);
            // Buscar la nueva empresa basándote en el idViaje seleccionado
            foreach ($colEmpresas as $empresa) {
                foreach ($empresa->getViajes() as $viaje) {
                    if ($viaje->getId() == $idViaje) {
                        $nuevaEmpresa = $empresa;
                        break 2; // Salir de ambos bucles
                    }
                }
            }
            // Verificar si se encontró la nueva empresa correspondiente
            if ($nuevaEmpresa != null) {

                // Insertar al usuario en la nueva colección de viajes seleccionada
                foreach ($nuevaEmpresa->getViajes() as $viaje) {
                    if ($viaje->getId() == $idViaje) {
                        $viaje->cargarPasajeroVuelo($this);
                        $isOk = true;
                        break; // Salir del bucle
                    }
                }
            } else {
                echo "No se encontró la empresa correspondiente al nuevo viaje seleccionado.";
            }
        }
        //echo "salio todo piola".$isOk."\n";
        if ($isOk) {
            $conx = new BaseDatos();
            $resp = $conx->iniciar();
            if ($resp == 1) {
                $sql = $conx->actualizarPasajero($documento, $nombre, $apellido, $telefono, $idViaje);
                $respSql = $conx->Ejecutar($sql);
                if ($respSql == 1) {
                    echo "Pasajero actualizado con éxito" . "\n";
                    $this->cargarPersona($documento, $nombre, $apellido, $telefono, $idViaje);
                    return $colEmpresas;
                } else {
                    echo "Error en la actualización de pasajero" . "\n";
                }
            }
        }
    }

    function eliminarPasajerosViaje($idViaje)
    {

        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = "SELECT * FROM pasajero WHERE idviaje = $idViaje";
            $pasajeros = $conx->EjecutarConRetornoBidimensional($sql);
            if (count($pasajeros) == 0) {
                // No hay pasajeros para eliminar, se devuelve true
                return true;
            }
            //print_r($pasajeros);
            $contBorrados = 0;
            foreach ($pasajeros as $pasajero) {
                $sql2 = $conx->eliminarPasajero($pasajero['pdocumento']);
                $borroPasajero = $conx->Ejecutar($sql2);
                if ($borroPasajero == 1) {
                    $contBorrados++;
                }
            }
            // Se compara la cantidad de pasajeros eliminados con la cantidad total de pasajeros
            if ($contBorrados == count($pasajeros)) {
                return true; // Se han eliminado todos los pasajeros
            } else {
                return false; // No se han podido eliminar todos los pasajeros
            }

        }
    }
    function borrarPasajeroV2($colEmpresas, $documento)
    {
        foreach ($colEmpresas as $empresas) {
            $viajes = $empresas->getViajes();
            foreach ($viajes as $viaje) {
                $pasajeros = $viaje->getPasajeros();
                foreach ($pasajeros as $key => $pasajero) {
                    if ($pasajero->getDni() == $documento) {
                        unset($pasajeros[$key]);
                    }
                }
                $viaje->setPasajeros($pasajeros); // Actualiza el arreglo de pasajeros del viaje
            }
        }
    }
}
?>