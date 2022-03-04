<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="nom is required")

     */
    private $nom;


    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="prix is required")

     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="adresse is required")

     */
    private $adresse;

    /**
     
     
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="date is required")
     * @var string A "Y-m-d H:i:s" formatted value
     * @Assert\GreaterThanOrEqual("today")

     */
    private $date;

    /**
 
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="datefin is required")
     * @var string A "Y-m-d H:i:s" formatted value
     * @Assert\GreaterThanOrEqual(propertyPath="date", message="La date du fin doit être supérieure à la date début")

     */
    private $datefin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="description is required")

     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $affiche;

    /**
     * @ORM\OneToMany(targetEntity=Commentaires::class, mappedBy="annonces", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\Column(type="text")
     */
    private $longdesc;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

   

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAffiche(): ?string
    {
        return $this->affiche;
    }

    public function setAffiche(string $affiche): self
    {
        $this->affiche = $affiche;

        return $this;
    }

    /**
     * @return Collection|Commentaires[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setAnnonces($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getAnnonces() === $this) {
                $commentaire->setAnnonces(null);
            }
        }

        return $this;
    }

    public function getLongdesc(): ?string
    {
        return $this->longdesc;
    }

    public function setLongdesc(string $longdesc): self
    {
        $this->longdesc = $longdesc;

        return $this;
    }
}
