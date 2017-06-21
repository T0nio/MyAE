<?php

namespace LG\MyAEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ligne
 *
 * @ORM\Table(name="ligne")
 * @ORM\Entity(repositoryClass="LG\MyAEBundle\Repository\LigneRepository")
 */
class Ligne
{
    /**
    * @ORM\ManyToOne(targetEntity="LG\MyAEBundle\Entity\Devis", inversedBy="lignes")
    */
    private $devis;

    /**
    * @ORM\ManyToOne(targetEntity="LG\MyAEBundle\Entity\Facture", inversedBy="lignes")
    */
    private $facture;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer")
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="pu", type="float", nullable=true)
     */
    private $pu;

    /**
     * @var float
     *
     * @ORM\Column(name="remise", type="float", nullable=true)
     */
    private $remise;

    /**
     * @var array
     *
     * @ORM\Column(name="subtotals", type="array")
     */
    private $subtotals;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Ligne
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return Ligne
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Ligne
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Ligne
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set pu
     *
     * @param float $pu
     *
     * @return Ligne
     */
    public function setPu($pu)
    {
        $this->pu = $pu;

        return $this;
    }

    /**
     * Get pu
     *
     * @return float
     */
    public function getPu()
    {
        return $this->pu;
    }

    /**
     * Set remise
     *
     * @param float $remise
     *
     * @return Ligne
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return float
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * Set subtotals
     *
     * @param array $subtotals
     *
     * @return Ligne
     */
    public function setSubtotals($subtotals)
    {
        $this->subtotals = $subtotals;

        return $this;
    }

    /**
     * Get subtotals
     *
     * @return array
     */
    public function getSubtotals()
    {
        return $this->subtotals;
    }

    /**
     * Set devis
     *
     * @param \LG\MyAEBundle\Entity\Devis $devis
     *
     * @return Ligne
     */
    public function setDevis(\LG\MyAEBundle\Entity\Devis $devis)
    {
        $this->devis = $devis;

        return $this;
    }

    /**
     * Get devis
     *
     * @return \LG\MyAEBundle\Entity\Devis
     */
    public function getDevis()
    {
        return $this->devis;
    }

    /**
     * Set facture
     *
     * @param \LG\MyAEBundle\Entity\Devis $devis
     *
     * @return Ligne
     */
    public function setFacture(\LG\MyAEBundle\Entity\Facture $facture)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return \LG\MyAEBundle\Entity\Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

    public function getTotal()
    {
        return $this->pu * $this->quantity * (100 - $this->remise ) / 100;
    }
}
