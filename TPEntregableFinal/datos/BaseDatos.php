<?php
/* IMPORTANTE !!!!  Clase para (PHP 5, PHP 7)*/

class BaseDatos
{
    private $HOSTNAME;
    private $BASEDATOS;
    private $USUARIO;
    private $CLAVE;
    private $CONEXION;
    private $QUERY;
    private $RESULT;
    private $ERROR;
    /**
     * Constructor de la clase que inicia ls variables instancias de la clase
     * vinculadas a la coneccion con el Servidor de BD
     */
    public function __construct()
    {
        $this->HOSTNAME = "localhost";
        $this->BASEDATOS = "EmpresaViajes";
        $this->USUARIO = "root";
        $this->CLAVE = "112233";
        $this->RESULT = 0;
        $this->QUERY = "";
        $this->ERROR = "";
    }
    /**
     * Funcion que retorna una cadena
     * con una peque�a descripcion del error si lo hubiera
     *
     * @return string
     */
    public function getError()
    {
        return "\n" . $this->ERROR;

    }
    public function getResult()
    {
        return $this->RESULT;
    }
    /**
     * Inicia la coneccion con el Servidor y la  Base Datos Mysql.
     * Retorna true si la coneccion con el servidor se pudo establecer y false en caso contrario
     *
     * @return boolean
     */
    public function Iniciar()
    {
        $resp = false;
        $conexion = mysqli_connect($this->HOSTNAME, $this->USUARIO, $this->CLAVE, $this->BASEDATOS);
        if ($conexion) {
            if (mysqli_select_db($conexion, $this->BASEDATOS)) {
                $this->CONEXION = $conexion;
                unset($this->QUERY);
                unset($this->ERROR);
                $resp = true;
            } else {
                $this->ERROR = mysqli_errno($conexion) . ": " . mysqli_error($conexion);
            }
        } else {
            $this->ERROR = mysqli_errno($conexion) . ": " . mysqli_error($conexion);
        }
        return $resp;
    }

    /**
     * Ejecuta una consulta en la Base de Datos.
     * Recibe la consulta en una cadena enviada por parametro.
     *MODIFICADO PARA DEVOLVER EL ID DE LA CONSULTA EN CASO DE EJECUCION SATISFACTORIA. -1 SI DA ERROR.
     * @param string $consulta
     * @return boolean
     */
    public function EjecutarRetornaId($consulta)
    {
        $resp = false;
        unset($this->ERROR);
        $this->QUERY = $consulta;
        $ejecutar = mysqli_query($this->CONEXION, $consulta);
        if ($this->RESULT = $ejecutar) {
            //$resp = true;
            $id = $this->devuelveIDInsercion($ejecutar);
            
        } else {
            $id = -1;
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        return $id;
    }
/**
     * Ejecuta una consulta en la Base de Datos.
     * Recibe la consulta en una cadena enviada por parametro.
     *MODIFICADO PARA DEVOLVER EL ID DE LA CONSULTA EN CASO DE EJECUCION SATISFACTORIA. -1 SI DA ERROR.
     * @param string $consulta
     * @return boolean
     */
    public function Ejecutar($consulta)
    {
        $resp = false;
        unset($this->ERROR);
        $this->QUERY = $consulta;
        $ejecutar = mysqli_query($this->CONEXION, $consulta);
        if ($this->RESULT = $ejecutar) {
            $resp = true;
        } else {
            $id = -1;
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        return $resp;
    }
    //a diferencia de ejecutar que devuelve un booleano si fue correcta la consulta,
    //aplico este metodo que devuelve el registro encontrado a partir de una consulta de busqueda.
    public function EjecutarConRetorno($consulta)
    {
        $resp = false;
        unset($this->ERROR);
        $this->QUERY = $consulta;
        
        $result = mysqli_query($this->CONEXION, $consulta);
        
        if ($result) {
            $resp = mysqli_fetch_assoc($result); // Devuelve el resultado de la consulta como un array asociativo
        } else {
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        
        return $resp;
    }

    /*devuelve todas las instancias que cumplen la condición de la consulta en lugar de solo una como EjecutarConRetorno. 
    cada fila corresponde a una instancia encontrada, y cada columna será un campo de la instancia*/
    public function EjecutarConRetornoBidimensional($consulta)
{
    $resp = false;
    unset($this->ERROR);
    $this->QUERY = $consulta;
    
    $result = mysqli_query($this->CONEXION, $consulta);
    
    if ($result) {
        $resp = mysqli_fetch_all($result, MYSQLI_ASSOC); // Devuelve todos los resultados de la consulta como un array asociativo
    } else {
        $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
    }
    
    return $resp;
}
    /**
     * Devuelve un registro retornado por la ejecucion de una consulta
     * el puntero se despleza al siguiente registro de la consulta
     *
     * @return boolean
     */
    public function Registro()
    {
        $resp = null;
        if ($this->RESULT) {
            unset($this->ERROR);
            if ($temp = mysqli_fetch_assoc($this->RESULT)) {
                $resp = $temp;
            } else {
                mysqli_free_result($this->RESULT);
            }
        } else {
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        return $resp;
    }

    /**
     * Devuelve el id de un campo autoincrement utilizado como clave de una tabla
     * Retorna el id numerico del registro insertado, devuelve null en caso que la ejecucion de la consulta falle
     *  Cuando la  clave de una tabla es un atributo autoincrement obtienes el valor luego de la inserción con la función devuelveIdEjecuta de la clase BaseDatos
     * @param string $consulta
     * @return int id de la tupla insertada
     */
    public function devuelveIDInsercion($ejecutar)
    {
        $resp = null;
        unset($this->ERROR);
        //$this->QUERY = $consulta;
        if ($this->RESULT = $ejecutar) {
            $id = mysqli_insert_id($this->CONEXION);
            $resp = $id;
        } else {
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);

        }
        return $resp;
    }


    // Funciones para la tabla 'empresa'

    function insertarEmpresa($nombre, $direccion)
    {
        $sql = "INSERT INTO empresa (enombre, edireccion) VALUES ('$nombre', '$direccion')";
        return $sql;
    }
    function actualizarEmpresa($id, $nombre, $direccion)
    {
        $sql = "UPDATE empresa SET enombre = '$nombre', edireccion = '$direccion' WHERE idempresa = $id";
        return $sql;
    }

    function eliminarEmpresa($id)
    {
        $sql = "DELETE FROM empresa WHERE idempresa = $id";
        return $sql;
    }

    function listarEmpresas()
    {
        $sql = "SELECT * FROM empresa";
        return $sql;
    }

    function buscarEmpresa($enombre)
    {
        $sql = "SELECT * FROM empresa WHERE enombre like '%$enombre%'";
        return $sql;
    }

    // Funciones para la tabla 'responsable'

    function insertarResponsable($licencia, $nombre, $apellido)
    {
        $sql = "INSERT INTO responsable (rnumerolicencia, rnombre, rapellido) VALUES ($licencia, '$nombre', '$apellido')";
        return $sql;
    }

    function actualizarResponsable($id, $licencia, $nombre, $apellido)
    {
        $sql = "UPDATE responsable SET rnumerolicencia = $licencia, rnombre = '$nombre', rapellido = '$apellido' WHERE rnumeroempleado = $id";
        return $sql;
    }

    function eliminarResponsable($id)
    {
        $sql = "DELETE FROM responsable WHERE rnumeroempleado = $id";
        return $sql;
    }

    function listarResponsables()
    {
        $sql = "SELECT * FROM responsable";
        return $sql;
    }

    function buscarResponsable($id)
    {
        $sql = "SELECT * FROM responsable WHERE rnumeroempleado = $id";
        return $sql;
    }
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

    function eliminarViaje($id)
    {
        $sql = "DELETE FROM viaje WHERE idviaje = $id";
        return $sql;
    }
    function listarViajes()
    {
        $sql = "SELECT * FROM viaje";
        return $sql;
    }

    function buscarViaje($id)
    {
        $sql = "SELECT * FROM viaje WHERE idviaje = $id";
        return $sql;
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

    function buscarPasajero($documento)
    {
        $sql = "SELECT * FROM pasajero WHERE pdocumento = '$documento'";
        return $sql;
    }


    public function obtenerEmpresas()
    {
        $empresas = array();

        // Realiza la consulta para obtener las empresas
        $consulta = "SELECT * FROM empresa";
        $resultado = $this->CONEXION->query($consulta);

        // Verifica si la consulta devuelve resultados
        if ($resultado->num_rows > 0) {
            // Recorre los resultados y crea objetos Empresa
            while ($fila = $resultado->fetch_assoc()) {
                $empresa = new Empresa();
                $empresa->setIdEmpresa($fila['idempresa']);
                $empresa->setEnombre($fila['enombre']);
                $empresa->setEdireccion($fila['edireccion']);
                $empresas[] = $empresa;
            }
        }

        return $empresas;
    }
}
?>