<?php

class Viaje
{

    private $id;
    private $destino;
    private $cantMax;
    private $cantPasajeros;
    private $responsable; //objeto reponsable.
    private $costoViaje;
    private $empresa; //objeto empresa

    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter for id
    public function getId()
    {
        return $this->id;
    }
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    public function getEmpresa()
    {
        return $this->empresa;
    }

    public function setResponsable($resp)
    {
        $this->responsable = $resp;
    }

    public function getResponsable()
    {
        return $this->responsable;
    }

    public function __toString()
    {
        $output = "ID del viaje: {$this->id}\n";
        $output .= "Destino: {$this->destino}\n";
        $output .= "Cantidad máxima de pasajeros: {$this->cantMax}\n";
        $output .= "Cantidad de pasajeros: {$this->cantPasajeros}\n";
        $output .= "Responsable: {$this->responsable}\n";
        $output .= "Costo del viaje: {$this->costoViaje}\n";
        return $output;
    }

    // Obtiene el valor de cantMaxPasajeros
    public function getCantMaxPasajeros()
    {
        return $this->cantMax;
    }

    //Obtiene el valor de destino
    public function getDestino()
    {
        return $this->destino;
    }

    //Obtiene el valor de idViaje



    public function cuentaCantPasajeros($cantPasajeros)
    {
        $this->cantPasajeros += $cantPasajeros;
    }

    //Establece el valor de id



    //Establece el destino
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    //Establece el valor de cantMaxPasajeros
    public function setCantMaxPasajeros($cantMax)
    {
        $this->cantMax = $cantMax;
    }
    public function getCostoViaje()
    {
        return $this->costoViaje;
    }

    public function setCostoViaje($costo)
    {
        $this->costoViaje = $costo;
    }
    
        public function venderPasaje($idViaje, $costo)
        {
            $conx = new BaseDatos();
            $resp = $conx->iniciar();
            if ($resp == 1) {
                // Obtener el valor actual de cantPasajeros 
                $sql = "SELECT cantTotalPasajeros FROM viaje WHERE idviaje = $idViaje";
                $respSql = $conx->EjecutarConRetorno($sql);
                if ($respSql) {
                    $nuevoValor = $respSql['cantTotalPasajeros'] + 1;
                    $sql2 = "UPDATE viaje SET cantTotalPasajeros = '$nuevoValor' WHERE idviaje = $idViaje";
                    $isOk = $conx->Ejecutar($sql2);
                    if ($isOk) {
                        $this->cuentaCantPasajeros(1);
                    }
                }
            }
        }
    
    public function __construct()
    {
        $this->id = "";
        $this->destino = "";
        $this->cantMax = "";
        $this->cantPasajeros = 0;
        $this->responsable = new ResponsableV();
        $this->costoViaje = 0;
        $this->empresa = new Empresa();
    }


    /*public function cargarPasajeroVuelo($persona)
    {
        array_push($this->pasajeros, $persona);
    }*/
    public function hayPasajesDisponible()
    {
        return $this->cantPasajeros < $this->cantMax;
    }

    public function cargarViaje($id, $empresa, $responsable, $destino, $cantMax, $costoViaje, $cantPasajeros)
    {
        $this->setId($id);
        $this->setEmpresa($empresa);
        $this->setResponsable($responsable);
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMax);
        $this->setCostoViaje($costoViaje);
    }

    function agregarViaje($destino, $cantMax, $empresa, $responsable, $costoViaje)
    {
        $idEmp = $empresa->getIdEmpresa();
        $idResp = $responsable->getIdEmpleado();
        $isOk = null;
        //conectarme a la bd para insertar el registro.
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->insertarViaje($destino, $cantMax, $idEmp, $idResp, $costoViaje);
            //respSql es el Id que retorna de la consulta.
            $respSql = $conx->EjecutarRetornaId($sql);
            if ($respSql != -1) {
                $viaje = new Viaje();
                $viaje->cargarViaje($respSql, $destino, $cantMax, $empresa, $responsable, $costoViaje, 0);
                $isOk = $viaje;
            }
        }
        return $isOk;
    }



    public function modificarViaje($idViaje, $destino, $cantMax, $empresa, $responsable, $costoViaje)
    {

   
        $isEncontrado = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->searchViaje($idViaje);
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql) {
                $sql = $this->actualizarViaje($idViaje, $destino, $cantMax, $empresa->getIdEmpresa(), $responsable->getIdEmpleado(), $costoViaje);
                $respSql2 = $conx->Ejecutar($sql);

                if ($respSql2 == 1) {
                    $viaje = new Viaje();
                    $viaje->cargarViaje($idViaje, $destino, $cantMax, $empresa, $responsable, $costoViaje, 0);
                    $isEncontrado = $viaje;
                }
            }
        }
        return $isEncontrado;
    }

    public function eliminarViaje($idViaje)
    {
        $isBorrado = false;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->dltViaje($idViaje); //consulta sql
            $borraIsOk = $conx->Ejecutar($sql);
            if ($borraIsOk == 1) {
                $isBorrado = true;
            }
        }
        return $isBorrado;
    }

    function buscarViaje($idViaje)
    {
        $isEncontrado = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->searchViaje($idViaje); //metodo de acceso a la bd
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql !== false) {
                // La consulta se ejecutó correctamente y se obtuvo un resultado
                if (!empty($respSql)) { //si no está vacio muestra el viaje encontrado, de lo contrario avisa que no coincide la busqueda.
                    $id = $respSql['idviaje'];
                    $destino = $respSql['vdestino'];
                    $cantMax = $respSql['vcantmaxpasajeros'];
                    $idEmpresa = $respSql['idempresa'];
                    $idResponsable = $respSql['rnumeroempleado'];
                    $costoViaje = $respSql['vimporte'];
                    $cantPasajeros = $respSql['cantTotalPasajeros'];

                    $empresa = new Empresa();
                    $emp = $empresa->buscarEmpresa($idEmpresa);
                    

                    $responsable = new ResponsableV();
                    $resp = $responsable->buscarResponsable($idResponsable);
                    

                    $this->cargarViaje($id, $emp, $resp, $destino, $cantMax, $costoViaje, $cantPasajeros);
                    $isEncontrado = $this;
                }
            }

        }
        return $isEncontrado;
    }

    public static function listar($condicion = "")
    {
        $arregloViaje = null;
        $base = new BaseDatos();
        $consultaViajes = "SELECT * FROM viaje ";
        if ($condicion != "") {
            $consultaViajes = $consultaViajes . ' WHERE ' . $condicion;
        }
        $consultaViajes .= " ORDER BY vdestino ";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViajes)) {
                $arregloViaje = array();
                while ($row = $base->Registro()) {
                    $id = $row['idviaje'];
                    //buscar viaje
                    $viaje = new Viaje();
                    $viaje->buscarViaje($id);
                    
                    /*
                        $pasajero = new Pasajero();
                        $cond = "idviaje = $id";
                        $colPasajeros = $pasajero->listar($cond);

                        $viaje->setPasajeros($colPasajeros);*/
                    array_push($arregloViaje, $viaje);
                }
            }
        }

        return $arregloViaje;
    }

    // Funciones para la tabla 'viaje'

    function insertarViaje($destino, $cantPasajeros, $idEmpresa, $idResponsable, $importe)
    {
        $sql = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) VALUES ('$destino', $cantPasajeros, $idEmpresa, $idResponsable, $importe)";
        return $sql;
    }

    function actualizarViaje($idViaje, $destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje)
    {
        $sql = "UPDATE viaje
        SET vdestino = '" . $destino . "',
            vcantmaxpasajeros = " . $cantMax . ",
            idempresa = " . $idEmpresa . ",
            rnumeroempleado = " . $idResponsable . ",
            vimporte = " . $costoViaje . ",
            cantTotalPasajeros = 0
        WHERE idviaje = " . $idViaje;
        return $sql;
    }
    function dltViaje($id)
    {
        $sql = "DELETE FROM viaje WHERE idviaje = $id";
        return $sql;
    }
    function listarViajes()
    {
        $sql = "SELECT * FROM viaje";
        return $sql;
    }

    function searchViaje($id)
    {
        $sql = "SELECT * FROM viaje WHERE idviaje = $id";
        return $sql;
    }
}
?>