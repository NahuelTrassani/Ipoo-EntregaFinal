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
        $this->viajes = $viaje;
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
                $emp = new Empresa();
                $emp->cargarEmpresa($id, $nomEmpresa, $dirEmpresa);
                $retorno = $emp;
            }
        }
        return $retorno;
    }

    public function modificarEmpresa($id, $newNomEmpresa, $dirEmpresa)
    {
        $isOk = "";
        $conx = new BaseDatos();
        $resp = $conx->iniciar();

        if ($resp == 1) {
            $sql = $this->searchEmpresa($id);
            $respSql = $conx->EjecutarConRetorno($sql);

            if ($respSql) {
                
                $sql = $this->actualizarEmpresa($id, $newNomEmpresa, $dirEmpresa);
                $respSql2 = $conx->Ejecutar($sql);
                if ($respSql2 == 1) {
                    $emp = new Empresa();
                    $emp->cargarEmpresa($id, $newNomEmpresa, $dirEmpresa);
                    $isOk = $emp;
                }
            }
        }
        return $isOk;
    }


    public function eliminarEmpresa($id)
    {

        $isBorrado = 0;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->searchEmpresa($id);
            $respSql = $conx->EjecutarConRetorno($sql);

            if ($respSql) {
                $idEmpresa = $respSql['idempresa'];
                //debo llamar al de viaje para borrar todos los viajes y pasajeros.
                $sql2 = $this->borrarEmpresa($idEmpresa);
                $borraIsOk = $conx->Ejecutar($sql2);
                if ($borraIsOk == 1) {
                    $isBorrado = $idEmpresa;
                }

            }
        }
        return $isBorrado;
    }
    function buscarEmpresa($idEmpresa)
    {
        $isEncontrado = null;
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $this->searchEmpresa($idEmpresa); //metodo de acceso a la bd
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql !== false) {
                // La consulta se ejecutó correctamente y se obtuvo un resultado
                if (!empty($respSql)) {
                    $idEmpresa = $respSql['idempresa'];
                    $enombre = $respSql['enombre'];
                    $edireccion = $respSql['edireccion'];

                    $emp = new Empresa();
                    $emp->cargarEmpresa($idEmpresa, $enombre, $edireccion);
                    $isEncontrado = $emp;
                }
            }
        }
        return $isEncontrado;
    }

    public static function listar($condicion = "")
    {
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresa = "Select * from empresa ";
        if ($condicion != "") {
            $consultaEmpresa = $consultaEmpresa . ' where ' . $condicion;
        }
        $consultaEmpresa .= " order by enombre ";
        //echo $consultaPersonas;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                $arregloEmpresa = array();
                while ($row2 = $base->Registro()) {
                    $idEmpresa = $row2['idempresa'];
                    $emp = new Empresa();
                    $empresa = $emp->buscarEmpresa($idEmpresa);
                    $emp->cargarEmpresa($idEmpresa, $empresa->getEnombre(), $empresa->getEdireccion());
                    /*
                                        La empresa no contiene viajes, los viajes tienen la referencia a empresa.
                                        esto no hace falta.
                                        
                                        $viaje = new Viaje();
                                        $cond = "idempresa = $idEmpresa";
                                        $colViajeAux = $viaje->listar($cond);

                                        $emp->setViaje($colViajeAux);*/
                    array_push($arregloEmpresa, $emp);
                }
            }

        }
        return $arregloEmpresa;
    }





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

    function searchEmpresa($id)
    {
        $sql = "SELECT * FROM empresa WHERE idempresa = $id";
        return $sql;
    }

}
?>