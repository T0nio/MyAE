<?php

namespace LG\MyAEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="LG\MyAEBundle\Repository\FactureRepository")
 */
class Facture
{
    /**
    * @ORM\ManyToOne(targetEntity="LG\MyAEBundle\Entity\Client", inversedBy="factures")
    * @ORM\JoinColumn(nullable=false)
    */
    private $client;

    /**
    * @ORM\OneToMany(targetEntity="LG\MyAEBundle\Entity\Ligne", mappedBy="facture", cascade={"remove", "persist"})
    * @ORM\OrderBy({"ordre" = "ASC"})
    */
    private $lignes;


    /**
     * @var User $ownedBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="LG\UserBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $ownedBy;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @Gedmo\Slug(fields={"date", "facture_number"})
    * @ORM\Column(length=128, unique=true)
    */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="totalTTC", type="float")
     */
    private $totalTTC;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paiement_date", type="datetime")
     */
    private $paiement_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prestation_date", type="datetime")
     */
    private $prestation_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="penalite_date", type="datetime")
     */
    private $penalite_date;

    /**
     * @var int
     * 
     * @ORM\Column(name="facture_number", type="integer")
     */
    private $facture_number;

    /**
     * @var float
     * 
     * @ORM\Column(name="penalite_taux", type="float")
     */
    private $penalite_taux;


    public function __construct()
    {
        $this->date = new \Datetime();
        $this->prestation_date = new \Datetime();
        $this->paiement_date = new \Datetime();
        $this->penalite_taux = 20;
        $this->penalite_date = new \Datetime();
        $this->penalite_date->add(\DateInterval::createFromDateString('1 month'));
    }


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Facture
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set totalTTC
     *
     * @param float $totalTTC
     *
     * @return Facture
     */
    public function setTotalTTC($totalTTC)
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    /**
     * Get totalTTC
     *
     * @return float
     */
    public function getTotalTTC()
    {
        return $this->totalTTC;
    }

    public function getDateValidite()
    {
        $dateValidite = $this->date;
        $dateValidite->add(new \DateInterval("P".$this->validity."D"));
        return $dateValidite;
    }

    /**
     * Set ownedBy
     *
     * @param string $ownedBy
     *
     * @return Facture
     */
    public function setOwnedBy($ownedBy)
    {
        $this->ownedBy = $ownedBy;

        return $this;
    }

    /**
     * Get ownedBy
     *
     * @return string
     */
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Facture
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set client
     *
     * @param \LG\MyAEBundle\Entity\Client $client
     *
     * @return Facture
     */
    public function setClient(\LG\MyAEBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \LG\MyAEBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add ligne
     *
     * @param \LG\MyAEBundle\Entity\Ligne $ligne
     *
     * @return Facture
     */
    public function addLigne(\LG\MyAEBundle\Entity\Ligne $ligne)
    {
        $this->lignes[] = $ligne;
        $ligne->setFacture($this);

        return $this;
    }

    /**
     * Remove ligne
     *
     * @param \LG\MyAEBundle\Entity\Ligne $ligne
     */
    public function removeLigne(\LG\MyAEBundle\Entity\Ligne $ligne)
    {
        $this->lignes->removeElement($ligne);
    }

    /**
     * Get lignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignes()
    {
        return $this->lignes;
    }


    /**
     * Set paiementDate
     *
     * @param \DateTime $paiementDate
     *
     * @return Facture
     */
    public function setPaiementDate($paiementDate)
    {
        $this->paiement_date = $paiementDate;

        return $this;
    }

    /**
     * Get paiementDate
     *
     * @return \DateTime
     */
    public function getPaiementDate()
    {
        return $this->paiement_date;
    }

    /**
     * Set prestationDate
     *
     * @param \DateTime $prestationDate
     *
     * @return Facture
     */
    public function setPrestationDate($prestationDate)
    {
        $this->prestation_date = $prestationDate;

        return $this;
    }

    /**
     * Get prestationDate
     *
     * @return \DateTime
     */
    public function getPrestationDate()
    {
        return $this->prestation_date;
    }

    /**
     * Set penaliteDate
     *
     * @param \DateTime $penaliteDate
     *
     * @return Facture
     */
    public function setPenaliteDate($penaliteDate)
    {
        $this->penalite_date = $penaliteDate;

        return $this;
    }

    /**
     * Get penaliteDate
     *
     * @return \DateTime
     */
    public function getPenaliteDate()
    {
        return $this->penalite_date;
    }

    /**
     * Set factureNumber
     *
     * @param integer $factureNumber
     *
     * @return Facture
     */
    public function setFactureNumber($factureNumber)
    {
        $this->facture_number = $factureNumber;

        return $this;
    }

    /**
     * Get factureNumber
     *
     * @return integer
     */
    public function getFactureNumber()
    {
        return $this->facture_number;
    }

    /**
     * Set penaliteTaux
     *
     * @param float $penaliteTaux
     *
     * @return Facture
     */
    public function setPenaliteTaux($penaliteTaux)
    {
        $this->penalite_taux = $penaliteTaux;

        return $this;
    }

    /**
     * Get penaliteTaux
     *
     * @return float
     */
    public function getPenaliteTaux()
    {
        return $this->penalite_taux;
    }

}
