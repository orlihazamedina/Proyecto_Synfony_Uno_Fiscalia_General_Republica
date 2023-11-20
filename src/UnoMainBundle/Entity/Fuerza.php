<?php

namespace UnoMainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fuerza
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Fuerza
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
     * @var integer
     *
     * @ORM\Column(name="TotalEspecialidad", type="integer")
     */
    private $totalEspecialidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="DedControl", type="integer",nullable=true)
     */
    private $dedControl;

    /**
     * @var integer
     *
     * @ORM\Column(name="DedDespacho", type="integer",nullable=true)
     */
    private $dedDespacho;

    /**
     * @var integer
     *
     * @ORM\Column(name="DedJuicios", type="integer",nullable=true)
     */
    private $dedJuicios;

    /**
     * @var integer
     *
     * @ORM\Column(name="OtrasActividades", type="integer",nullable=true)
     */
    private $otrasActividades;

    /**
     * @var integer
     *
     * @ORM\Column(name="Totaljuicios", type="integer",nullable=true)
     */
    private $totaljuicios;

    /**
     * @var integer
     *
     * @ORM\Column(name="Sumario", type="integer",nullable=true)
     */
    private $sumario;

    /**
     * @var integer
     *
     * @ORM\Column(name="Ordinario", type="integer",nullable=true)
     */
    private $ordinario;

    /**
     * @var integer
     *
     * @ORM\Column(name="TribProvOrd", type="integer",nullable=true)
     */
    private $tribProvOrd;

    /**
     * @var integer
     *
     * @ORM\Column(name="TribProvApel", type="integer",nullable=true)
     */
    private $tribProvApel;

    /**
     * @var integer
     *
     * @ORM\Column(name="IncidenciasReport", type="integer",nullable=true)
     */
    private $incidenciasReport;

 /**
     * @var string
     *
     * @ORM\Column(name="municipio", type="string", length=255)
     */
    private $municipio;
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
     * Set totalEspecialidad
     *
     * @param integer $totalEspecialidad
     *
     * @return Fuerza
     */
    public function setTotalEspecialidad($totalEspecialidad)
    {
        $this->totalEspecialidad = $totalEspecialidad;

        return $this;
    }

    /**
     * Get totalEspecialidad
     *
     * @return integer
     */
    public function getTotalEspecialidad()
    {
        return $this->totalEspecialidad;
    }

    /**
     * Set dedControl
     *
     * @param integer $dedControl
     *
     * @return Fuerza
     */
    public function setDedControl($dedControl)
    {
        $this->dedControl = $dedControl;

        return $this;
    }

    /**
     * Get dedControl
     *
     * @return integer
     */
    public function getDedControl()
    {
        return $this->dedControl;
    }

    /**
     * Set dedDespacho
     *
     * @param integer $dedDespacho
     *
     * @return Fuerza
     */
    public function setDedDespacho($dedDespacho)
    {
        $this->dedDespacho = $dedDespacho;

        return $this;
    }

    /**
     * Get dedDespacho
     *
     * @return integer
     */
    public function getDedDespacho()
    {
        return $this->dedDespacho;
    }

    /**
     * Set dedJuicios
     *
     * @param integer $dedJuicios
     *
     * @return Fuerza
     */
    public function setDedJuicios($dedJuicios)
    {
        $this->dedJuicios = $dedJuicios;

        return $this;
    }

    /**
     * Get dedJuicios
     *
     * @return integer
     */
    public function getDedJuicios()
    {
        return $this->dedJuicios;
    }

    /**
     * Set otrasActividades
     *
     * @param integer $otrasActividades
     *
     * @return Fuerza
     */
    public function setOtrasActividades($otrasActividades)
    {
        $this->otrasActividades = $otrasActividades;

        return $this;
    }

    /**
     * Get otrasActividades
     *
     * @return integer
     */
    public function getOtrasActividades()
    {
        return $this->otrasActividades;
    }

    /**
     * Set totaljuicios
     *
     * @param integer $totaljuicios
     *
     * @return Fuerza
     */
    public function setTotaljuicios($totaljuicios)
    {
        $this->totaljuicios = $totaljuicios;

        return $this;
    }

    /**
     * Get totaljuicios
     *
     * @return integer
     */
    public function getTotaljuicios()
    {
        return $this->totaljuicios;
    }

    /**
     * Set sumario
     *
     * @param integer $sumario
     *
     * @return Fuerza
     */
    public function setSumario($sumario)
    {
        $this->sumario = $sumario;

        return $this;
    }

    /**
     * Get sumario
     *
     * @return integer
     */
    public function getSumario()
    {
        return $this->sumario;
    }

    /**
     * Set ordinario
     *
     * @param integer $ordinario
     *
     * @return Fuerza
     */
    public function setOrdinario($ordinario)
    {
        $this->ordinario = $ordinario;

        return $this;
    }

    /**
     * Get ordinario
     *
     * @return integer
     */
    public function getOrdinario()
    {
        return $this->ordinario;
    }

    /**
     * Set tribProvOrd
     *
     * @param integer $tribProvOrd
     *
     * @return Fuerza
     */
    public function setTribProvOrd($tribProvOrd)
    {
        $this->tribProvOrd = $tribProvOrd;

        return $this;
    }

    /**
     * Get tribProvOrd
     *
     * @return integer
     */
    public function getTribProvOrd()
    {
        return $this->tribProvOrd;
    }

    /**
     * Set tribProvApel
     *
     * @param integer $tribProvApel
     *
     * @return Fuerza
     */
    public function setTribProvApel($tribProvApel)
    {
        $this->tribProvApel = $tribProvApel;

        return $this;
    }

    /**
     * Get tribProvApel
     *
     * @return integer
     */
    public function getTribProvApel()
    {
        return $this->tribProvApel;
    }

    /**
     * Set incidenciasReport
     *
     * @param integer $incidenciasReport
     *
     * @return Fuerza
     */
    public function setIncidenciasReport($incidenciasReport)
    {
        $this->incidenciasReport = $incidenciasReport;

        return $this;
    }

    /**
     * Get incidenciasReport
     *
     * @return integer
     */
    public function getIncidenciasReport()
    {
        return $this->incidenciasReport;
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
    
    
}

