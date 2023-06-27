<?php
require_once "Pasajero.php";
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
    private $costosAbonados;


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
        $output .= "Costos abonados: {$this->costosAbonados}\n";
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
    public function getCostosAbonados()
    {
        return $this->costosAbonados;
    }

    public function setCostosAbonados($costos)
    {
        $this->costosAbonados = $costos;
    }


    public function venderPasajeColeccion($costo)
    {

        //calcula segun el porcentaje de incremento
        echo "porcentaje aumento: " . $costo . "\n";
        $costoViaje = $this->getCostoViaje();
        echo "costo: " . $costoViaje . "\n";
        $costoFinal = $costoViaje + $costoViaje * ($costo / 100);
        echo "costoFinal: " . $costoFinal . "\n";
        $this->setCostoViaje($costoFinal);

        // Actualizar los costos abonados
        $this->costosAbonados += $costoFinal;

        $this->cuentaCantPasajeros(1);

        return $costoFinal;

    }

    public function venderPasaje($idViaje)
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
                $conx->Ejecutar($sql2);
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
        $this->costosAbonados = 0;
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
    public function insertViaje($id, $idEmpresa, $responsable, $destino, $cantMax, $costoViaje)
    {
        $this->setId($id);
        $this->setIdEmpresa($idEmpresa);
        $this->setResponsable($responsable);
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMax);
        $this->setCostoViaje($costoViaje);
    }
    function agregarViaje()
    {
        echo "Debe indicar el id de la empresa y del responsable del viaje" . "\n";
        echo "TENGA EN CUENTA QUE EL ID DEBE EXISTIR EN LA TABLA CORRESPONDIENTE PORQUE NO ESTOY VALIANDO QUE HAYA ERROR." . "\n";
        //antes debo recuperar la empresa y el responsable.
        echo "Empresa" . "\n";
        $idEmpresa = trim(fgets(STDIN));

        echo "Responsable" . "\n";
        $idResponsable = trim(fgets(STDIN));

        echo "Indique el destino del viaje: " . "\n";
        $destino = trim(fgets(STDIN));

        echo "Indique la capacidad máxima de personas que tiene el viaje: ";
        $cantMax = fgets(STDIN);

        echo "Indique el precio del viaje: " . "\n";
        $costoViaje = fgets(STDIN);

        //conectarme a la bd para insertar el registro.
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->insertarViaje($destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje);
            $respSql = $conx->EjecutarRetornaId($sql);
            if ($respSql != -1) {
                echo "Viaje cargado con éxito" . "\n";
                $this->insertViaje($respSql, $idEmpresa, $idResponsable, $destino, $cantMax, $costoViaje);
                return $this;
            } else {
                echo "Error insertando Viaje" . "\n";
            }
        } else {
            echo "Error conectando a la bd" . "\n";
        }
    }

    function buscarViaje()
    {

        echo "Ingrese el 'idViaje' que desea buscar: " . "\n";
        $idViaje = trim(fgets(STDIN));

        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->buscarViaje($idViaje); //metodo de acceso a la bd
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql !== false) {
                // La consulta se ejecutó correctamente y se obtuvo un resultado
                if (!empty($respSql)) { //si no está vacio muestra el viaje encontrado, de lo contrario avisa que no coincide la busqueda.
                    //print_r($respSql);
                    return $respSql;
                } else {
                    echo "No se encontró viaje para ese id" . "\n";
                }
            } else {
                echo "la consulta no se ejecutó correctamente" . "\n";
            }

        }
    }
    function buscarViajeV2($colEmpresas)
    {
        echo "Ingrese el 'idViaje' que desea buscar: " . "\n";
        $idViaje = trim(fgets(STDIN));
        foreach ($colEmpresas as $empresas) {
            $viajes = $empresas->getViajes();
            foreach ($viajes as $viaje) {
                $id = $viaje->getId();
                if ($id == $idViaje) {
                    return $viaje;
                }
            }
        }
    }

    public function modificarViaje()
    {
        echo "Indique el id del viaje que desea modificar" . "\n";
        $idViaje = trim(fgets(STDIN));

        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->buscarViaje($idViaje);
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql) {
                echo "Ingrese un nuevo destino para el viaje" . "\n";
                $destino = fgets(STDIN);

                echo "Indique la cantidad maxima de pasajeros" . "\n";
                $cantMax = trim(fgets(STDIN));

                echo "Indique el id de la Empresa (Recuerde que no se validan los id referenciados que no existan en la tabla)" . "\n";
                $idEmpresa = trim(fgets(STDIN));

                echo "Indique el id del Responsable  (Recuerde que no se validan los id referenciados que no existan en la tabla)" . "\n";
                $idResponsable = trim(fgets(STDIN));

                echo "Indique el precio del viaje: " . "\n";
                $costoViaje = fgets(STDIN);

                $sql = $conx->actualizarViaje($respSql['idviaje'], $destino, $cantMax, $idEmpresa, $idResponsable, $costoViaje);
                $respSql2 = $conx->Ejecutar($sql);
                if ($respSql2 == 1) {
                    echo "Actualización exitosa" . "\n";
                    $this->insertViaje($respSql['idviaje'], $idEmpresa, $idResponsable, $destino, $cantMax, $costoViaje);
                    return $this;
                } else {
                    echo "Falló actualización" . "\n";
                }
            }
        }
    }

    public function eliminarViaje()
    {
        echo "POR UNA CUESTION DE INTEGRIDAD REFERENCIAL borro los pasajeros para eliminar el viaje." . "\n";
        echo "Indique el id del viaje que desea eliminar" . "\n";
        $idViaje = trim(fgets(STDIN));
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {

            //aca debo borrar los pasajeros antes que el viaje
            $pasajero = new Pasajero();
            $resp = $pasajero->eliminarPasajerosViaje($idViaje);
            if ($resp) { //si borró todos los pasajeros del viaje avanza.
                $sql = $conx->eliminarViaje($idViaje);
                $borraIsOk = $conx->Ejecutar($sql);
                if ($borraIsOk == 1) {
                    echo "Se borró el viaje de manera exitosa" . "\n";
                    return $idViaje;
                } else {
                    echo "Falló el borrado del viaje" . "\n";
                    return 0;
                }

            }
        }
    }
}






//codigo realizado para trabajar con colecciones en el objeto TestViaje.php, a partir de añadir una bd al sistema esto queda deprecado.




/*

function buscarViaje($listaViajes, $nroVuelo)
{
    for ($i = 0; $i < count($listaViajes); $i++) {
        $encontro = recuperarViaje($listaViajes[$i], $nroVuelo);
        if ($encontro) {
            return $listaViajes[$i];
        }
    }
}



function recuperarViaje($viaje, $nroVuelo)
{
    $id = $viaje->getIdViaje();
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
    for ($i = 0; $i < count($listaViajes); $i++) {
        $viaje = $listaViajes[$i];
        echo "Datos viaje: " . "\n" . "Destino: " . $viaje->getDestino() . "\n" . "Cantidad máxima de pasajeros: " . $viaje->getCantMaxPasajeros() . "\n";
    }
}
*/
?>