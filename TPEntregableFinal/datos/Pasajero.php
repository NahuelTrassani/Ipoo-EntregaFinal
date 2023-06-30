<?php

class Pasajero
{
    private $dni;
    private $nombre;
    private $apellido;
    private $telefono;
    private $viaje;//intancia de objeto
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
        return $this->viaje->getId();
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

    public function setVuelo($idViaje)
    {
        $this->viaje->setId($idViaje);
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
        $this->viaje = new Viaje();
        $this->numeroAsiento = 0;
        $this->numeroTicket = 0;
    }

    public function cargarPersona($dni, $nombre, $apellido, $telefono, $idViaje)
    {
        $this->setDni($dni);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setTelefono($telefono);
        $this->setVuelo($idViaje);
    }

    public function agregarPasajero($dni, $nombre, $apellido, $telefono, $viaje)
    {
        $idViaje = $viaje->getId();
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