<?php
class ResponsableV
{
    private $idEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;

    public function __construct()
    {
        $this->nombre = "";
        $this->apellido = "";
        $this->idEmpleado = 0;
        $this->numLicencia = 0;
    }

    public function __toString()
    {
        return "\n" . "Responsable: " . $this->apellido . ", " . $this->nombre . "\n" . "Id de empleado: " . $this->idEmpleado ."\n" . "Numero de licencia: " . $this->numLicencia . "\n";
    }


    public function insertResponsable($idEmpleado, $nombre, $apellido, $numLicencia)
    {
        $this->setIdEmpleado($idEmpleado);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNumLicencia($numLicencia);
    }

    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    public function getNumLicencia()
    {
        return $this->numLicencia;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }

    public function setNumLicencia($numLicencia)
    {
        $this->numLicencia = $numLicencia;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function cargarResponsable($numLicencia, $nombre, $apellido)
    {
        $isOk = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->insertarResponsable($numLicencia, $nombre, $apellido);
            $respSql = $conx->EjecutarRetornaId($sql);
           
            if ($respSql != -1) {
                $responsable = new ResponsableV();
                $responsable->insertResponsable($respSql, $nombre, $apellido, $numLicencia);
                $isOk = $responsable;
            }
        }
        return $isOk;
    }

    function buscarResponsable($idResponsable)
    {
        $isEncontrado = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();

        if ($resp == 1) {
            $sql = $this->searchResponsable($idResponsable); //metodo de acceso a la bd
            $respSql = $conx->EjecutarConRetorno($sql);

            if ($respSql !== false) {
                // La consulta se ejecutó correctamente y se obtuvo un resultado
                if ($respSql) {

                    $numEmpleado = $respSql['rnumeroempleado'];
                    $numLicencia = $respSql['rnumerolicencia'];
                    $nombre = $respSql['rnombre'];
                    $apellido = $respSql['rapellido'];
                    $responsable = new ResponsableV();
                    $responsable->insertResponsable($numEmpleado, $nombre, $apellido, $numLicencia);
                    $isEncontrado = $responsable;
                }
            }
        }
        return $isEncontrado;
    }
    public static function listar($condicion = "")
    {
        $arregloResponsables = null;
        $base = new BaseDatos();
        $consultaResponsables = "SELECT * FROM responsable ";
        if ($condicion != "") {
            $consultaResponsables = $consultaResponsables . ' WHERE ' . $condicion;
        }
        $consultaResponsables .= " ORDER BY rapellido ";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsables)) {
                $arregloResponsables = array();
                while ($row = $base->Registro()) {
                    $numEmpleado = $row['rnumeroempleado'];

                    $responsable = new ResponsableV();
                    $responsable->buscarResponsable($numEmpleado);

                    $numLicencia = $responsable->getNumLicencia();
                    $nombre = $responsable->getNombre();
                    $apellido = $responsable->getApellido();

                    $responsable->insertResponsable($numEmpleado, $nombre, $apellido, $numLicencia);
                    array_push($arregloResponsables, $responsable);
                }
            }
        }

        return $arregloResponsables;
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

    function searchResponsable($id)
    {
       
        $sql = "SELECT * FROM responsable WHERE rnumeroempleado = $id";
        return $sql;
         /*
        $sql = "SELECT * FROM responsable WHERE rnombre = '$nombre'";
        return $sql;*/
    }
}
?>