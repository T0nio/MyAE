<?php

namespace LG\MyAEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Devis
 *
 * @ORM\Table(name="devis")
 * @ORM\Entity(repositoryClass="LG\MyAEBundle\Repository\DevisRepository")
 */
class Devis
{
    /**
    * @ORM\ManyToOne(targetEntity="LG\MyAEBundle\Entity\Client", inversedBy="deviss")
    * @ORM\JoinColumn(nullable=false)
    */
    private $client;

    /**
    * @ORM\OneToMany(targetEntity="LG\MyAEBundle\Entity\Ligne", mappedBy="devis", cascade={"remove", "persist"})
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(length=128, unique=true)
    */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="validity", type="integer")
     */
    private $validity;

    /**
     * @var float
     *
     * @ORM\Column(name="totalTTC", type="float")
     */
    private $totalTTC;

    /**
     * @var float
     *
     * @ORM\Column(name="markup", type="float")
     */
    private $markup;

    /**
     * @var boolean
     *
     * @ORM\Column(name="remisable", type="boolean")
     */
    private $remisable;


    public function __construct()
    {
        $this->date = new \Datetime();
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
     * Set name
     *
     * @param string $name
     *
     * @return Devis
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Devis
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
     * Set description
     *
     * @param string $description
     *
     * @return Devis
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Devis
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
     * Set validity
     *
     * @param integer $validity
     *
     * @return Devis
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * Get validity
     *
     * @return int
     */
    public function getValidity()
    {
        return $this->validity;
    }

    /**
     * Set totalTTC
     *
     * @param float $totalTTC
     *
     * @return Devis
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

    /**
     * Set markup
     *
     * @param float $markup
     *
     * @return Devis
     */
    public function setMarkup($markup)
    {
        $this->markup = $markup;

        return $this;
    }

    /**
     * Get markup
     *
     * @return float
     */
    public function getMarkup()
    {
        return $this->markup;
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
     * @return Devis
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
     * @return Devis
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
     * @return Devis
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
     * @return Devis
     */
    public function addLigne(\LG\MyAEBundle\Entity\Ligne $ligne)
    {
        $this->lignes[] = $ligne;
        $ligne->setDevis($this);

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
     * Set remisable
     *
     * @param boolean $remisable
     *
     * @return Devis
     */
    public function setRemisable($remisable)
    {
        $this->remisable = $remisable;

        return $this;
    }

    /**
     * Get remisable
     *
     * @return boolean
     */
    public function getRemisable()
    {
        return $this->remisable;
    }

    public function getTotalAfterMarkup()
    {
        return $this->totalTTC * (100 - $this->markup) / 100;
    }

    public function getTotalForMe()
    {
        return $this->getTotalAfterMarkup() * (100 - $this->ownedBy->getMarkup()) / 100;
    }

}
