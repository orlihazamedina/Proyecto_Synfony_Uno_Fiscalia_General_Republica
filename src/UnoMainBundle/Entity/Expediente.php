<?php

namespace UnoMainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Expediente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Expediente
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     
     * @var string
     *
     * @ORM\Column(name="numExpediente", type="string", length=255)
     */
    private $numExpediente;

    /**
     * @var string
     *
     * @ORM\Column(name="municipio", type="string", length=255)
     */
    private $municipio;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad", type="string", length=255)
     */
    private $unidad;
    
    /**
     * @var string
     *
     * @ORM\Column(name="delito", type="string", length=255)
     */
    private $delito;
    
     /**
     * @var string
     *
     * @ORM\Column(name="clasificacion", type="string", length=255)
     */
    private $clasificacion;
    

    /**
     * @var integer
     *
     * @ORM\Column(name="acusados", type="integer",nullable=true)
     */
    private $acusados;
    
    
    
     /**
     * @var integer
     *
     * @ORM\Column(name="acusadosp", type="integer",nullable=true)
     */
    private $acusadosp;
   /**
     * @var string
     *
     * @ORM\Column(name="fechainicial", type="string", length=255)
    
     */
     
    private $fechainicial;

    /**
     
     * @var string
     *
     * @ORM\Column(name="fechaentrega", type="string", length=255,nullable=true)
     */
    private $fechaentrega;

     /**
     * @var string
     *
     * @ORM\Column(name="dEVTTP", type="string", length=255,nullable=true)
     */
    private $dEVTTP;

     /**
     * @var string
     *
     * @ORM\Column(name="sIS", type="string", length=255,nullable=true)
     */
    private $sIS;

     /**
     * @var string
     *
     * @ORM\Column(name="dEVFISCAL", type="string", length=255,nullable=true)
     */
    private $dEVFISCAL;

 /**
     * @var string
     *
     * @ORM\Column(name="pronostico", type="string", length=255,nullable=true)
 
  */
    private $pronostico;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255,nullable=true)

     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoExpediente", type="string", length=255 )
     */
    private $tipoExpediente;
    
    
     /**
     * @var string
     *
     * @ORM\Column(name="insertn", type="string", length=255 )
     */
    private $insertn;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numExpediente
     *
     * @param string $numExpediente
     *
     * @return Expediente
     */
    public function setNumExpediente($numExpediente)
    {
        $this->numExpediente = $numExpediente;

        return $this;
    }

    /**
     * Get numExpediente
     *
     * @return string
     */
    public function getNumExpediente()
    {
        return $this->numExpediente;
    }

    /**
     * Set municipio
     *
     * @param string $municipio
     *
     * @return Expediente
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return string
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Set unidad
     *
     * @param string $unidad
     *
     * @return Expediente
     */
    public function setUnidad($unidad)
    {
        $this->unidad = $unidad;

        return $this;
    }

    /**
     * Get unidad
     *
     * @return string
     */
    public function getUnidad()
    {
        return $this->unidad;
    }
 /**
     * Set delito
     *
     * @param string $delito
     *
     * @return Expediente
     */
    public function setDelito($delito)
    {
        $this->delito = $delito;

        return $this;
    }

    /**
     * Get delito
     *
     * @return string
     */
    public function getDelito()
    {
        return $this->delito;
    }

    /**
     * Set acusados
     *
     * @param integer $acusados
     *
     * @return Expediente
     */
    public function setAcusados($acusados)
    {
        $this->acusados = $acusados;

        return $this;
    }

    /**
     * Get acusados
     *
     * @return integer
     */
    public function getAcusados()
    {
        return $this->acusados;
    }
    
     /**
     * Set acusadosp
     *
     * @param integer $acusadosp
     *
     * @return Expediente
     */
    public function setAcusadosp($acusadosp)
    {
        $this->acusadosp = $acusadosp;

        return $this;
    }

    /**
     * Get acusadosp
     *
     * @return integer
     */
    public function getAcusadosp()
    {
        return $this->acusadosp;
    }

    /**
     * Set fechainicial
     *
     * @param \DateTime $fechainicial
     *
     * @return Expediente
     */
    public function setFechainicial($fechainicial)
    {
        $this->fechainicial = $fechainicial;

        return $this;
    }

    /**
     * Get fechainicial
     *
     * @return \DateTime
     */
    public function getFechainicial()
    {
        return $this->fechainicial;
    }

    /**
     * Set fechaentrega
     *
     * @param \DateTime $fechaentrega
     *
     * @return Expediente
     */
    public function setFechaentrega($fechaentrega)
    {
        $this->fechaentrega = $fechaentrega;

        return $this;
    }

    /**
     * Get fechaentrega
     *
     * @return \DateTime
     */
    public function getFechaentrega()
    {
        return $this->fechaentrega;
    }

    /**
     * Set dEVTTP
     *
     * @param \DateTime $dEVTTP
     *
     * @return Expediente
     */
    public function setDEVTTP($dEVTTP)
    {
        $this->dEVTTP = $dEVTTP;

        return $this;
    }

    /**
     * Get dEVTTP
     *
     * @return \DateTime
     */
    public function getDEVTTP()
    {
        return $this->dEVTTP;
    }

    /**
     * Set sIS
     *
     * @param \DateTime $sIS
     *
     * @return Expediente
     */
    public function setSIS($sIS)
    {
        $this->sIS = $sIS;

        return $this;
    }

    /**
     * Get sIS
     *
     * @return \DateTime
     */
    public function getSIS()
    {
        return $this->sIS;
    }

    /**
     * Set dEVFISCAL
     *
     * @param \DateTime $dEVFISCAL
     *
     * @return Expediente
     */
    public function setDEVFISCAL($dEVFISCAL)
    {
        $this->dEVFISCAL = $dEVFISCAL;

        return $this;
    }

    /**
     * Get dEVFISCAL
     *
     * @return \DateTime
     */
    public function getDEVFISCAL()
    {
        return $this->dEVFISCAL;
    }

    /**
     * Set pronostico
     *
     * @param \DateTime $pronostico
     *
     * @return Expediente
     */
    public function setPronostico($pronostico)
    {
        $this->pronostico = $pronostico;

        return $this;
    }

    /**
     * Get pronostico
     *
     * @return \DateTime
     */
    public function getPronostico()
    {
        return $this->pronostico;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Expediente
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set tipoExpediente
     *
     * @param string $tipoExpediente
     *
     * @return Expediente
     */
    public function setTipoExpediente($tipoExpediente)
    {
        $this->tipoExpediente = $tipoExpediente;

        return $this;
    }

    /**
     * Get tipoExpediente
     *
     * @return string
     */
    public function getTipoExpediente()
    {
        return $this->tipoExpediente;
    }
    
    
      /**
     * Set insertn
     *
     * @param string $insertn
     *
     * @return Expediente
     */
    public function setInsertn($insertn)
    {
        $this->insertn = $insertn;

        return $this;
    }

    /**
     * Get insertn
     *
     * @return string
     */
    public function getInsertn()
    {
        return $this->insertn;
    }
    
     /**
     * Set clasificacion
     *
     * @param string $clasificacion
     *
     * @return Expediente
     */
    public function setClasificacion($clasificacion)
    {
        $this->clasificacion = $clasificacion;

        return $this;
    }

    /**
     * Get clasificacion
     *
     * @return string
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    } 
    
}

