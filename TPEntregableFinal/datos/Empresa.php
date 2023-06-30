<?php

class Empresa
{
    private $idEmpresa;
    private $enombre;
    private $edireccion;
    private $viajes;

    public function __construct()
    {
        $this->enombre = "";
        $this->edireccion = "";
        $this->viajes = array();
    }
    public function getViajes()
    {
        return $this->viajes;
    }

    public function setViaje($viaje)
    {
        $this->viajes[] = $viaje;
    }

    public function eliminarViaje($viaje)
    {
        //busco el indice del objeto en el array
        $index = array_search($viaje, $this->viajes);
        if ($index !== false) {
            //si encontró el indice borra 1 elemento usando array_splice
            array_splice($this->viajes, $index, 1);
        }
    }
    public function __toString()
    {
        $empresaInfo = "Id de la empresa: " . $this->getIdEmpresa() . "\n" . "Nombre de la empresa: " . $this->getEnombre() . "\nDirección de la empresa: " . $this->getEdireccion() . "\n";

        if (!empty($this->viajes)) {
            $empresaInfo .= "Viajes:\n";
            foreach ($this->viajes as $viaje) {
                $empresaInfo .= $viaje->__toString() . "\n";
            }
        } else {
            $empresaInfo .= "No se han registrado viajes.\n";
        }

        return $empresaInfo;
    }

    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }

    public function getEnombre()
    {
        return $this->enombre;
    }

    public function setEnombre($enombre)
    {
        $this->enombre = $enombre;
    }

    public function getEdireccion()
    {
        return $this->edireccion;
    }

    public function setEdireccion($edireccion)
    {
        $this->edireccion = $edireccion;
    }
    public function cargarEmpresa($idEmpresa, $enombre, $edireccion)
    {
        $this->setIdEmpresa($idEmpresa);
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
    }

    public function agregarEmpresa($nomEmpresa, $dirEmpresa)
    {
        $retorno = "";
        $conx = new BaseDatos();
        $resp = $conx->iniciar();

        if ($resp == 1) {
            $sql = $this->insertarEmpresa($nomEmpresa, $dirEmpresa);
            $id = $conx->EjecutarRetornaId($sql);
            if ($id != -1) {
                $this->cargarEmpresa($id, $nomEmpresa, $dirEmpresa);
                $retorno = $this;
            }
        }
        return $retorno;
    }

    public function modificarEmpresa($nomEmpresa, $newNomEmpresa, $dirEmpresa)
    {
        $isOk = "";
        $conx = new BaseDatos();
        $resp = $conx->iniciar();

        if ($resp == 1) {
            $sql = $this->searchEmpresa($nomEmpresa);
            $respSql = $conx->EjecutarConRetorno($sql);

            if ($respSql) {
                $idEmpresa = $respSql['idempresa'];
                $sql = $this->actualizarEmpresa($idEmpresa, $newNomEmpresa, $dirEmpresa);
                $respSql2 = $conx->Ejecutar($sql);
                if ($respSql2 == 1) {

                    $this->cargarEmpresa($idEmpresa, $nomEmpresa, $dirEmpresa);
                    $isOk = $this;
                }
            }
        }
        return $isOk;
    }


    public function eliminarEmpresa($nomEmpresa)
    {


        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->searchEmpresa($nomEmpresa);
            $respSql = $conx->EjecutarConRetorno($sql);

            if ($respSql) {
                $idEmpresa = $respSql['idempresa'];
                //debo llamar al de viaje para borrar todos los viajes y pasajeros.
                $sql2 = $this->borrarEmpresa($idEmpresa);
                $borraIsOk = $conx->Ejecutar($sql2);
                if ($borraIsOk == 1) {
                    echo "Se borró la empresa de manera exitosa" . "\n";
                    return $idEmpresa;
                } else {
                    echo "Falló el borrado de la empresa" . "\n";
                    return 0;
                }

            }
        }
    }
    function buscarEmpresa($nomEmpresa)
    {
        $isEncontrado = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->searchEmpresa($nomEmpresa); //metodo de acceso a la bd
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql !== false) {
                // La consulta se ejecutó correctamente y se obtuvo un resultado
                if (!empty($respSql)) {
                    $idEmpresa = $respSql['idempresa'];
                    $enombre = $respSql['enombre'];
                    $edireccion = $respSql['edireccion'];
                    $this->cargarEmpresa($idEmpresa, $enombre, $edireccion);
                    $isEncontrado = $this;
                }
            }
        }
        return $isEncontrado;
    }

/*
    public static function listar($condicion = "")
    {
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresas = "SELECT * FROM empresa ";
        if ($condicion != "") {
            $consultaEmpresas = $consultaEmpresas . ' WHERE ' . $condicion;
        }
        $consultaEmpresas .= " ORDER BY enombre ";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresas)) {
                $arregloEmpresa = array();
                while ($row = $base->Registro()) {
                    $id = $row['idempresa'];
                    $nombre = $row['enombre'];
                    $direccion = $row['edireccion'];

                    $empresa = new Empresa();
                    $empresa->cargarEmpresa($id, $nombre, $direccion);

                    // Cargar los viajes de la empresa
                    $consultaViajes = "SELECT * FROM viaje WHERE idempresa = " . $id;
                    $base->Ejecutar($consultaViajes);
                    while ($rowViaje = $base->Registro()) {
                        $viaje = new Viaje();
                        // Cargar los datos del viaje...

                        // Agregar el viaje a la empresa
                        $empresa->setViaje($viaje);
                    }

                    array_push($arregloEmpresa, $empresa);
                }
            } else {
                // Manejar el error en caso de fallo en la ejecución de la consulta
                // Puedes utilizar $base->getError() para obtener el mensaje de error
            }
        } else {
            // Manejar el error en caso de fallo en la conexión a la base de datos
            // Puedes utilizar $base->getError() para obtener el mensaje de error
        }

        return $arregloEmpresa;
    }
    */
    /*

        public function obtenerEmpresas()
        {
            $empresas = array();
             $conx = new BaseDatos();
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
        */

    // ejecucciones sql

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

    function borrarEmpresa($id)
    {
        $sql = "DELETE FROM empresa WHERE idempresa = $id";
        return $sql;
    }

    function listarEmpresas()
    {
        $sql = "SELECT * FROM empresa";
        return $sql;
    }

    function searchEmpresa($enombre)
    {
        $sql = "SELECT * FROM empresa WHERE enombre like '%$enombre%'";
        return $sql;
    }

}
?>