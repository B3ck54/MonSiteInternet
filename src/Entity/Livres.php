<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LivresRepository")
 */
class Livres
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le titre ne peut pas être vide")
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $auteur;

    /**
     * @ORM\Column(type="text")
     */
    private $resume;

    /**
     * @Assert\GreaterThan(value = 1, message="Minimum 1 €")
     * @ORM\Column(type="integer")
     */
    private $prix;




    //*********************************************COURS ONE TO ONE*********************************************//
    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     */
    //"remove" -> je veux pas d'entités orphelines => si je remove le car tu remove les images et inversement
    private $image;

    /**
     * @return mixed
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Livres
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
    //**********************************************************************************************************//




    //*****************************************COURS ONE TO MANY*********************************************//
    /**
     * 1 objet de type Livre à plusieurs mot-clés
     * @ORM\OneToMany(targetEntity="Keyword", mappedBy="livre", cascade={"persist", "remove"})
     */
    private $keywords;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Edition", inversedBy="livres")
     */
    private $editions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;



    //initialisation du tableau $keywords
    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->editions = new ArrayCollection(); //permet de gérer un tableau d'objet
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $keywords
     * @return Livres
     */
    public function setKeywords($keyword)
    {
        $this->keywords = $keyword;
        return $this;
    }

    // METHODES ADD ET REMOVE SONT OBLIGATOIRES
    // c'est pas des setters mais des adders -> pouvoir ajouter des objets keyword dans mon tableau d'objet keyword
    public function addKeyword (Keyword $keyword)
    {
        $this->keywords->add($keyword);
        $keyword->setLivre($this); //ajoute mon livre courant à l'objet keyword
    }

    //être capable de remove un keyword dans le tableau
    public function removeKeyword (Keyword $keyword)
    {
        $this->keywords->removeElement($keyword);
    }
    //**********************************************************************************************************//



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection|Edition[]
     */
    public function getEditions(): Collection
    {
        return $this->editions;
    }

    public function addEdition(Edition $edition): self
    {
        if (!$this->editions->contains($edition)) {
            $this->editions[] = $edition;
        }

        return $this;
    }

    public function removeEdition(Edition $edition): self
    {
        if ($this->editions->contains($edition)) {
            $this->editions->removeElement($edition);
        }

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }


}
