<?php

namespace UnoMainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Traza
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Traza
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
     * @ORM\Column(name="fecha", type="string", length=255)
    
     */
     
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantexpedientes", type="integer")
     */
    private $cantexpedientes;
    
    
     /**
     * @var string
     *
     * @ORM\Column(name="municipio", type="string", length=255)
     */
    private $municipio;

    
     /**
     * @var integer
     *
     * @ORM\Column(name="tdtico", type="integer",nullable=true)
     */
    private $tdtico;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="tpnr", type="integer",nullable=true)
     */
    private $tpnr;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="inidtico", type="integer",nullable=true)
     */
    private $inidtico;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="inipnr", type="integer",nullable=true)
     */
    private $inipnr;

    
    
    
    
    
    

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
     * @param string $fecha
     *
     * @return Expediente
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set cantexpedientes
     *
     * @param integer $cantexpedientes
     *
     * @return Traza
     */
    public function setCantexpedientes($cantexpedientes)
    {
        $this->cantexpedientes = $cantexpedientes;

        return $this;
    }

    /**
     * Get cantexpedientes
     *
     * @return integer
     */
    public function getCantexpedientes()
    {
        return $this->cantexpedientes;
    }
    
    
     /**
     * Set tdtico
     *
     * @param integer $tdtico
     *
     * @return Expediente
     */
    public function setTdtico($tdtico)
    {
        $this->tdtico = $tdtico;

        return $this;
    }

    /**
     * Get tdtico
     *
     * @return integer
     */
    public function getTdtico()
    {
        return $this->tdtico;
    }

    
     /**
     * Set tpnr
     *
     * @param integer $tpnr
     *
     * @return Expediente
     */
    public function setTpnr($tpnr)
    {
        $this->tpnr = $tpnr;

        return $this;
    }

    /**
     * Get tpnr
     *
     * @return integer
     */
    public function getTpnr()
    {
        return $this->tpnr;
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
     * Set inidtico
     *
     * @param integer $inidtico
     *
     * @return Expediente
     */
    public function setInidtico($inidtico)
    {
        $this->inidtico = $inidtico;

        return $this;
    }

    /**
     * Get inidtico
     *
     * @return integer
     */
    public function getInidtico()
    {
        return $this->inidtico;
    }
     
     
    /**
     * Set inipnr
     *
     * @param integer $inipnr
     *
     * @return Expediente
     */
    public function setInipnr($inipnr)
    {
        $this->inipnr = $inipnr;

        return $this;
    }

    /**
     * Get inipnr
     *
     * @return integer
     */
    public function getInipnr()
    {
        return $this->inipnr;
    }
       
    
    
    
    
}

