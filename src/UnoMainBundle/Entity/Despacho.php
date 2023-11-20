<?php

namespace UnoMainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Despacho
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Despacho
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
     * @ORM\Column(name="DespachadosDia", type="integer")
     */
    private $despachadosDia;


     /**
     * @var integer
     *
     * @ORM\Column(name="TotalSIS60dias", type="integer")
     */
    private $totalsis60dias;

    
    /**
     * @var integer
     *
     * @ORM\Column(name="Devtrirecibidas60dias", type="integer")
     */
    private $devtrirecibidas60dias;

    

    /**
     * @var integer
     *
     * @ORM\Column(name="Concluciones", type="integer")
     */
    private $concluciones;

    /**
     * @var integer
     *
     * @ORM\Column(name="Sobreseimientos", type="integer")
     */
    private $sobreseimientos;

    /**
     * @var integer
     *
     * @ORM\Column(name="inhibitorias", type="integer")
     */
    private $inhibitorias;

    /**
     * @var integer
     *
     * @ORM\Column(name="ochotres", type="integer")
     */
    private $ochotres;

    /**
     * @var integer
     *
     * @ORM\Column(name="Otros", type="integer")
     */
    private $otros;

    /**
     * @var integer
     *
     * @ORM\Column(name="RemitidosFactura", type="integer")
     */
    private $remitidosFactura;

     /**
     * @var string
     *
     * @ORM\Column(name="municipio", type="string", length=255)
     */
    private $municipio;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="termmas10dias", type="integer")
     */
    private $termmas10dias;


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
     * Set despachadosDia
     *
     * @param integer $despachadosDia
     *
     * @return Despacho
     */
    public function setDespachadosDia($despachadosDia)
    {
        $this->despachadosDia = $despachadosDia;

        return $this;
    }

    /**
     * Get despachadosDia
     *
     * @return integer
     */
    public function getDespachadosDia()
    {
        return $this->despachadosDia;
    }

    
    /**
     * Set devtrirecibidas60dias
     *
     * @param integer $devtrirecibidas60dias
     *
     * @return Despacho
     */
    public function setDevtrirecibidas60dias($devtrirecibidas60dias)
    {
        $this->devtrirecibidas60dias = $devtrirecibidas60dias;

        return $this;
    }

    /**
     * Get devtrirecibidas60dias
     *
     * @return integer
     */
    public function getDevtrirecibidas60dias()
    {
        return $this->devtrirecibidas60dias;
    }

   

    /**
     * Set concluciones
     *
     * @param integer $concluciones
     *
     * @return Despacho
     */
    public function setConcluciones($concluciones)
    {
        $this->concluciones = $concluciones;

        return $this;
    }

    /**
     * Get concluciones
     *
     * @return integer
     */
    public function getConcluciones()
    {
        return $this->concluciones;
    }

    /**
     * Set sobreseimientos
     *
     * @param integer $sobreseimientos
     *
     * @return Despacho
     */
    public function setSobreseimientos($sobreseimientos)
    {
        $this->sobreseimientos = $sobreseimientos;

        return $this;
    }

    /**
     * Get sobreseimientos
     *
     * @return integer
     */
    public function getSobreseimientos()
    {
        return $this->sobreseimientos;
    }

    /**
     * Set inhibitorias
     *
     * @param integer $inhibitorias
     *
     * @return Despacho
     */
    public function setInhibitorias($inhibitorias)
    {
        $this->inhibitorias = $inhibitorias;

        return $this;
    }

    /**
     * Get inhibitorias
     *
     * @return integer
     */
    public function getInhibitorias()
    {
        return $this->inhibitorias;
    }

    /**
     * Set ochotres
     *
     * @param integer $ochotres
     *
     * @return Despacho
     */
    public function setochotres($ochotres)
    {
        $this->ochotres = $ochotres;

        return $this;
    }

    /**
     * Get 83
     *
     * @return integer
     */
    public function getochotres()
    {
        return $this->ochotres;
    }

    /**
     * Set otros
     *
     * @param integer $otros
     *
     * @return Despacho
     */
    public function setOtros($otros)
    {
        $this->otros = $otros;

        return $this;
    }

    /**
     * Get otros
     *
     * @return integer
     */
    public function getOtros()
    {
        return $this->otros;
    }

    /**
     * Set remitidosFactura
     *
     * @param integer $remitidosFactura
     *
     * @return Despacho
     */
    public function setRemitidosFactura($remitidosFactura)
    {
        $this->remitidosFactura = $remitidosFactura;

        return $this;
    }

    /**
     * Get remitidosFactura
     *
     * @return integer
     */
    public function getRemitidosFactura()
    {
        return $this->remitidosFactura;
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
     * Set termmas10dias
     *
     * @param integer $termmas10dias
     *
     * @return Despacho
     */
    public function setTermmas10dias($termmas10dias)
    {
        $this->termmas10dias = $termmas10dias;

        return $this;
    }

    /**
     * Get termmas10dias
     *
     * @return integer
     */
    public function getTermmas10dias()
    {
        return $this->termmas10dias;
    }



    /**
     * Set totalsis60dias
     *
     * @param integer $totalsis60dias
     *
     * @return Despacho
     */
    public function setTotalSIS60dias($totalsis60dias)
    {
        $this->totalsis60dias = $totalsis60dias;

        return $this;
    }

    /**
     * Get totalsis60dias
     *
     * @return integer
     */
    public function getTotalSIS60dias()
    {
        return $this->totalsis60dias;
    }





    
}

