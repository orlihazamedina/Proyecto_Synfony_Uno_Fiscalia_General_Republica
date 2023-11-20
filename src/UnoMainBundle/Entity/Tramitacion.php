<?php

namespace UnoMainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tramitacion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tramitacion
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
     * @ORM\Column(name="TotalContdia", type="integer")
     */
    private $TotalContdia;

    /**
     * @var string
     *
     * @ORM\Column(name="Municipio", type="string", length=255)
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
     * Set municipio
     *
     * @param string $municipio
     *
     * @return Tramitacion
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
    public function getTotalContdia()
    {
        return $this->TotalContdia;
    }
 /**
     * Set TotalContdia
     *
     * @param string $TotalContdia
     *
     * @return Tramitacion
     */
    public function setTotalContdia($TotalContdia)
    {
        $this->TotalContdia = $TotalContdia;

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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

