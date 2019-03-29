<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

//callback = fonction de retour, fonctions qui seront appelés

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    private $path;

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }
    //*********************************************COURS ONE TO ONE*********************************************//

    private $file; // fichier soumis par l'utilisateur - A aucun moment on le stocke dans la bdd

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Image
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        return $this;
    }
    //*********************************************************************************************************//


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    //*********************************************************************************************************//



    //avant de persister tu m'appelle la méthode handle()
    /**
     * @ORM\PreFlush()
     */
    public function handle()
    {

        if($this->file === null) // dans le cas d'une édition si je n'ai pas soumis de formulaire
        {
            return; // je retourne je n'ai rien a faire ici
        }
        // si j'ai pas d'id je suis en création dans ce cas là je rentre pas dans la condition
        //si j'édite, je soumet un fichier il va falloir que je supprime l'ancien fichier
        if($this->id) // si j'ai un id c'est que forcément mon image est en édition
        {
            // on va récupérer le fichier de l'image que je modifie et on va le supprimer
            unlink($this->path.'/'.$this->name);// cette méthode supprime un fichier. on lui passe le path et le nom du fichier.

        }

        //Ici processus normal pour aller créer une image et la déplacer dans le fichier publi/images
        //Il appelle la méthode CreateName => Création d'un nom unique
        $name = $this->createName();

        //donne le nom à l'image
        $this->setName($name);

        //déplace le fichier
        $this->file->move ($this->path, $name); //méthode de l'objet uploadedFile
    }

    //Création d'un nom unique
    private function createName(): string
    {
        // on récupère le nom du fichier soumis (getClientOriginalName()) . + extension
        //elle met un nom unique à l'image
        return md5(uniqid()).$this->file->getClientOriginalName().'.'.$this->file -> guessExtension();
    }

}
