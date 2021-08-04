<?php

namespace App\Entity;
use phpDocumentor\Reflection\Types\Integer;
use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UsersRepository;
use Symfony\Component\Security\Core\User\UserInterface;



/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 * @ORM\Table(name="offre", indexes={@ORM\Index(name="idclient", columns={"idclient"})})
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="date", nullable=true)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datefin", type="date", nullable=true)
     */
    private $datefin;




    /**
     * @var string
     *
     * @ORM\Column(name="libele", type="string", length=50, nullable=true)
     */
    private $libele;



    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=50, nullable=true)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=50, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cv", type="string", length=300, nullable=true)
     * @Assert\File(mimeTypes={"image/jpeg"},groups = {"create"})
     */
    private $cv;



    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idclient", referencedColumnName="id")
     * })
     */
    private $idclient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }


    public function getIdclient(): ?Users
    {
        return $this->idclient;
    }

    public function setIdclient(?Users $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }




    /**
     * @return string
     */
    public function getLibele(): ?string
    {
        return $this->libele;
    }

    /**
     * @param string $libele
     */
    public function setLibele(string $libele): void
    {
        $this->libele = $libele;
    }



    /**
     * @return string
     */
    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     */
    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }



}