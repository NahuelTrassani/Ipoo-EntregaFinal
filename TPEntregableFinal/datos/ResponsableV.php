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
        return "\n" . "Responsable: " . $this->apellido . ", " . $this->nombre . "\n" . "Id de empleado: " . $this->idEmpleado . "Numero de licencia: " . $this->numLicencia . "\n";
    }

   
    public function insertResponsable($idEmpleado,$nombre, $apellido, $numLicencia){		
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

    public function cargarResponsable()
    {
        echo "Ingrese los datos del responsable del vuelo: " . "\n";

        echo "Numero de licencia" . "\n";
        $numLicencia = fgets(STDIN);

        echo "Nombre" . "\n";
        $nombre = trim(fgets(STDIN));

        echo "Apellido: " . "\n";
        $apellido = trim(fgets(STDIN));
        /*
        echo "numEmpleado: " . "\n";
        $numEmpleado = fgets(STDIN);
        */
        //conectarme a la bd para insertar el registro.
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->insertarResponsable($numLicencia, $nombre, $apellido);
            $respSql = $conx->EjecutarRetornaId($sql);
            if ($respSql != -1) {
                echo "Responsable cargado con éxito";
                $this->insertResponsable($respSql, $nombre, $apellido, $numLicencia);
                return $this;
            } else {
                echo "error insertando responsable";
            }
        } else {
            echo "error conectando a la bd";
        }
    }
}

?>