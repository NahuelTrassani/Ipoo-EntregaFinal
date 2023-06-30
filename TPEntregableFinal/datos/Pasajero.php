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
    private $mensajeoperacion;

    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }
    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }
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

    public function agregarPasajero($dni, $nombre, $apellido, $telefono, $idViaje)
    {
        $pasajero = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->insertarPasajero($dni, $nombre, $apellido, $telefono, $idViaje);
            $respSql = $conx->Ejecutar($sql);
            if ($respSql == 1) {
                $this->cargarPersona($dni, $nombre, $apellido, $telefono, $idViaje);
                $pasajero = $this;
            }
        }
        return $pasajero;
    }


    public function buscarPasajero($documento)
    {
        $isEncontrado = false;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->searchPasajero($documento);
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql !== false) {

                if (!empty($respSql)) {
                    $dni = $respSql['pdocumento'];
                    $nombre = $respSql['pnombre'];
                    $apellido = $respSql['papellido'];
                    $telefono = $respSql['ptelefono'];
                    $idViaje = $respSql['idviaje'];
                    $this->cargarPersona($dni, $nombre, $apellido, $telefono, $idViaje);
                    $isEncontrado = $this;
                }
            }
        }
        return $isEncontrado;
    }

    public function modificarPasajero($dniPasj, $idViaje, $nombre, $apellido, $telefono)
    {
        $pasajero = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->actualizarPasajero($dniPasj, $nombre, $apellido, $telefono, $idViaje);
            $respSql = $conx->Ejecutar($sql);
            if ($respSql == 1) {
                $this->cargarPersona($dniPasj, $nombre, $apellido, $telefono, $idViaje);
                $pasajero = $this;
            }
        }
        return $pasajero;
    }
    public static function listar($condicion = "")
    {
        $arregloPersona = null;
        $base = new BaseDatos();
        $consultaPersonas = "Select * from pasajero ";
        if ($condicion != "") {
            $consultaPersonas = $consultaPersonas . ' where ' . $condicion;
        }
        $consultaPersonas .= " order by papellido ";
        //echo $consultaPersonas;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPersonas)) {
                $arregloPersona = array();
                while ($row2 = $base->Registro()) {

                    $dni = $row2['pdocumento'];
                    $nombre = $row2['pnombre'];
                    $apellido = $row2['papellido'];
                    $telefono = $row2['ptelefono'];
                    $idViaje = $row2['idviaje'];

                    $pasajero = new Pasajero();
                    $pasajero->cargarPersona($dni, $nombre, $apellido, $telefono, $idViaje);
                    array_push($arregloPersona, $pasajero);

                }
            }
        }

        return $arregloPersona;
    }

    /*
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
           */
    /*
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
*/
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
    function listarPasajeros()
    {
        $sql = "SELECT * FROM pasajero";
        return $sql;
    }

    function searchPasajero($documento)
    {
        $sql = "SELECT * FROM pasajero WHERE pdocumento = '$documento'";
        return $sql;
    }

}
?>