<?php

class Viaje
{
    /**
     * Variables instancia de la clase Viaje
     * int $id
     * string $destino
     * int $cantMaxPasajeros
   
     */
    private $id;
    private $destino;
    private $cantMax;
    private $cantPasajeros;
    private $pasajeros = array();
    private $responsable;
    private $costoViaje;
    //private $costosAbonados;


    private $idEmpresa;

    //...
    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter for id
    public function getId()
    {
        return $this->id;
    }
    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }

    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }
    //...

    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    public function getResponsable()
    {
        return $this->responsable;
    }
    public function setCantPasajeros($cantPasajeros)
    {
        $this->cantPasajeros = $cantPasajeros;
    }

    public function getcantPasajeros()
    {
        return $this->cantPasajeros;
    }
    //
    //GETTERS
    // 

    public function __toString()
    {
        $output = "ID del viaje: {$this->id}\n";
        $output .= "Destino: {$this->destino}\n";
        $output .= "Cantidad máxima de pasajeros: {$this->cantMax}\n";
        $output .= "Cantidad de pasajeros: {$this->cantPasajeros}\n";
        $output .= "Responsable: {$this->responsable}\n";
        $output .= "Costo del viaje: {$this->costoViaje}\n";
        //$output .= "Costos abonados: {$this->costosAbonados}\n";
        $output .= "Pasajeros:\n";

        foreach ($this->pasajeros as $pasajero) {
            $output .= $pasajero->getApellido() . ", " . $pasajero->getNombre() . "\n";
        }

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
    public function getIdViaje()
    {
        return $this->id;
    }


    public function cuentaCantPasajeros($cantPasajeros)
    {
        $this->cantPasajeros += $cantPasajeros;
    }

    public function getPasajeros()
    {
        return $this->pasajeros;
    }

    //
    //SETTERS
    //
    public function setPasajeros($pasajeros)
    {
        $this->pasajeros = $pasajeros;
    }

    //Establece el valor de id

    public function setIdViaje($id)
    {
        $this->id = $id;
    }

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
        $this->pasajeros = array();
        $this->responsable = "";
        $this->costoViaje = 0;
        //$this->costosAbonados = 0;
        $this->idEmpresa = 0;
    }


    public function cargarPasajeroVuelo($persona)
    {
        array_push($this->pasajeros, $persona);
    }
    public function hayPasajesDisponible()
    {
        return $this->cantPasajeros < $this->cantMax;
    }
    public function insertViaje($id, $idEmpresa, $responsable, $destino, $cantMax, $costoViaje, $cantPasajeros)
    {
        $this->setId($id);
        $this->setIdEmpresa($idEmpresa);
        $this->setResponsable($responsable);
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMax);
        $this->setCostoViaje($costoViaje);
        $this->setCantPasajeros($cantPasajeros);
    }
    function agregarViaje($destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje)
    {
        $isOk = null;
        //conectarme a la bd para insertar el registro.
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->insertarViaje($destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje);
            $respSql = $conx->EjecutarRetornaId($sql);
            if ($respSql != -1) {
                $this->insertViaje($respSql, $idEmpresa, $idResponsable, $destino, $cantMax, $costoViaje, 0);
                $isOk = $this;
            }
        }
        return $isOk;
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
                    $responsable = $respSql['rnumeroempleado'];
                    $costoViaje = $respSql['vimporte'];
                    $cantPasajeros = $respSql['cantTotalPasajeros'];
                    $this->insertViaje($id, $idEmpresa, $responsable, $destino, $cantMax, $costoViaje, $cantPasajeros);
                    $isEncontrado = $this;
                }
            }

        }
        return $isEncontrado;
    }


    public function modificarViaje($idViaje, $destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje)
    {
        $isEncontrado = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->searchViaje($idViaje);
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql) {

                $sql = $this->actualizarViaje($idViaje, $destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje);
                $respSql2 = $conx->Ejecutar($sql);
                if ($respSql2 == 1) {

                    $this->insertViaje($idViaje, $idEmpresa, $idResponsable, $destino, $cantMax, $costoViaje, 0);
                    return $this;
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

            //aca debo borrar los pasajeros antes que el viaje
            //$pasajero = new Pasajero();
            //$resp = $pasajero->eliminarPasajerosViaje($idViaje);
            //if ($resp) { //si borró todos los pasajeros del viaje avanza.
            $sql = $this->dltViaje($idViaje);
            $borraIsOk = $conx->Ejecutar($sql);
            if ($borraIsOk == 1) {
                $isBorrado = true;
            }
        }
        return $isBorrado;
    }
    /*
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
                        $destino = $row['vdestino'];
                        $cantMax = $row['vcantmaxpasajeros'];
                        $idEmpresa = $row['idempresa'];
                        $idResponsable = $row['rnumeroempleado'];
                        $costoViaje = $row['vimporte'];
                        $cantPasajeros = $row['cantTotalPasajeros'];

                        $viaje = new Viaje();
                        $viaje->insertViaje($id, $destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje, $cantPasajeros);


                        // Cargar los pasajeros del viaje
                        $consultaPasajeros = "SELECT * FROM pasajero WHERE idviaje = " . $id;
                        $base->Ejecutar($consultaPasajeros);
                        while ($rowPasajero = $base->Registro()) {
                            $dni = $rowPasajero['pdocumento'];
                            $nombre = $rowPasajero['pnombre'];
                            $apellido = $rowPasajero['papellido'];
                            $tel = $rowPasajero['ptelefono'];
                            $nroVuelo = $rowPasajero['idviaje'];

                            $pasajero = new Pasajero();
                            $pasajero->cargarPersona($dni, $nombre, $apellido, $tel, $nroVuelo);
                        }


                        array_push($arregloViaje, $viaje);
                    }
                } else {
                    // Manejar el error en caso de fallo en la ejecución de la consulta
                    // Puedes utilizar $base->getError() para obtener el mensaje de error
                }
            } else {
                // Manejar el error en caso de fallo en la conexión a la base de datos
                // Puedes utilizar $base->getError() para obtener el mensaje de error
            }

            return $arregloViaje;
        }
    */

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






//codigo realizado para trabajar con colecciones en el objeto TestViaje.php, a partir de añadir una bd al sistema esto
//queda deprecado.




/*

function buscarViaje($listaViajes, $nroVuelo)
{
for ($i = 0; $i < count($listaViajes); $i++) { $encontro=recuperarViaje($listaViajes[$i], $nroVuelo); if ($encontro) {
    return $listaViajes[$i]; } } } function recuperarViaje($viaje, $nroVuelo) { $id=$viaje->getIdViaje();
    if ($viaje->getIdViaje() == $nroVuelo) {
    //echo "encontró!!";
    return true;
    } else {
    //echo "NOOOO encontró";
    return false;
    }

    //$des = $viaje->getDestino();
    //echo "Destino: " . $des;
    }


    function listarViajes($listaViajes)
    {
    for ($i = 0; $i < count($listaViajes); $i++) { $viaje=$listaViajes[$i]; echo "Datos viaje: " . "\n" . "Destino: " .
        $viaje->getDestino() . "\n" . "Cantidad máxima de pasajeros: " . $viaje->getCantMaxPasajeros() . "\n";
        }
        }
        */
?>