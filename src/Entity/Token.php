<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenRepository")
 */
class Token
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Token constructor.
     * @param User $user
     * @throws \Exception
     */
    public function __construct(User $user) // Utilisateur dans mon constructeur comme ça je suis forcée d'ajouter un user à mon token
    {
        $this->createdAt = new \DateTime(); //dès que je crée un Token je met la date de création
        $this->user = $user;
        $this->value = md5(uniqid());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isValid()
    {
        $interval = new \DateInterval('PT6H'); // je crée une interval de 6h et je l'ajoute à la date de création
        return $this->createdAt->add($interval)>= new \DateTime(); // que la date de création + 6H est + petit ou égale qu'à la date actuelle
        // \DateTime par défaut c'est la date actuelle

    }



}
