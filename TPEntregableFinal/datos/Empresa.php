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
        $index = array_search($viaje, $this->viajes);
        if ($index !== false) {
            array_splice($this->viajes, $index, 1);
        }
    }
    public function __toString()
    {
        $empresaInfo = "Id de la empresa: " . $this->getIdEmpresa() . "\n"."Nombre de la empresa: " . $this->getEnombre() . "\nDirección de la empresa: " . $this->getEdireccion() . "\n";

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

    public function agregarEmpresa()
    {
        echo "Nombre empresa" . "\n";
        $nomEmpresa = fgets(STDIN);

        echo "Dirección empresa" . "\n";
        $dirEmpresa = trim(fgets(STDIN));

        $conx = new BaseDatos();
        $resp = $conx->iniciar();

        if ($resp == 1) {
            $sql = $conx->insertarEmpresa($nomEmpresa, $dirEmpresa);
            $id = $conx->EjecutarRetornaId($sql);
            //$id = $conx->devuelveIDInsercion($sql);
            //echo "el id de la inserción es: ".$id."\n";
            if ($id != -1) {
                $this->cargarEmpresa($id, $nomEmpresa, $dirEmpresa);
                echo "Empresa cargada con éxito" . "\n";
                return $this;
            } else {
                echo "Error insertando Empresa" . "\n";
            }
        } else {
            echo "Error conectando a la bd" . "\n";
        }
    }

    public function modificarEmpresa()
    {
        echo "Indique el nombre de la empresa que desea modificar" . "\n";
        $nomEmpresa = trim(fgets(STDIN));

        $conx = new BaseDatos();
        $resp = $conx->iniciar();

        if ($resp == 1) {
            $sql = $conx->buscarEmpresa($nomEmpresa);
            $respSql = $conx->EjecutarConRetorno($sql);
            $idEmpresa = $respSql['idempresa'];
            if ($respSql) {    
                echo "Ingrese un nuevo nombre para la empresa" . "\n";
                $nomEmpresa = fgets(STDIN);

                echo "Nueva dirección de la empresa" . "\n";
                $dirEmpresa = trim(fgets(STDIN));

                $sql = $conx->actualizarEmpresa($respSql['idempresa'], $nomEmpresa, $dirEmpresa);
                $respSql2 = $conx->Ejecutar($sql);
                if ($respSql2 == 1) {
                    echo "Actualización exitosa" . "\n";
                    $this->cargarEmpresa($idEmpresa, $nomEmpresa, $dirEmpresa);
                    return $this;
                } else {
                    echo "Falló actualización" . "\n";
                }
            }
        }
    }


    public function eliminarEmpresa()
    {
        echo "POR UNA CUESTION DE INTEGRIDAD REFERENCIAL PRIMERO VAS A TENER QUE BORRAR LOS VIAJES ASOCIADOS A LA EMPRESA QUE QUERES BORRAR" . "\n";
        echo "Indique el nombre de la empresa que desea eliminar" . "\n";
        $nomEmpresa = trim(fgets(STDIN));
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->buscarEmpresa($nomEmpresa);
            $respSql = $conx->EjecutarConRetorno($sql);
            $idEmpresa = $respSql['idempresa'];
            if ($respSql) {
                $sql2 = $conx->eliminarEmpresa($respSql['idempresa']);
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
}
?>