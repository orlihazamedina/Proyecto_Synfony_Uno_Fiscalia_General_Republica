<?php

namespace UnoMainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cambio
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Cambio
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
     * @ORM\Column(name="dia", type="string", length=255)
     */
    private $dia;

    /**
     * @var integer
     *
     * @ORM\Column(name="expediente", type="integer")
     */
    private $expediente;

    /**
     * @var string
     *
     * @ORM\Column(name="estadoinicial", type="string", length=255)
     */
    private $estadoinicial;

    /**
     * @var string
     *
     * @ORM\Column(name="paso", type="string", length=255)
     */
    private $paso;
    
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dia
     *
     * @param string $dia
     *
     * @return Cambio
     */
    public function setDia($dia)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return string
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set expediente
     *
     * @param string $expediente
     *
     * @return Cambio
     */
    public function setExpediente($expediente)
    {
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get expediente
     *
     * @return string
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * Set estadoinicial
     *
     * @param string $estadoinicial
     *
     * @return Cambio
     */
    public function setEstadoinicial($estadoinicial)
    {
        $this->estadoinicial = $estadoinicial;

        return $this;
    }

    /**
     * Get estadoinicial
     *
     * @return string
     */
    public function getEstadoinicial()
    {
        return $this->estadoinicial;
    }

    /**
     * Set paso
     *
     * @param string $paso
     *
     * @return Cambio
     */
    public function setPaso($paso)
    {
        $this->paso = $paso;

        return $this;
    }

    /**
     * Get paso
     *
     * @return string
     */
    public function getPaso()
    {
        return $this->paso;
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
    
}

