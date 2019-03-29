<?php
/**
 * Created by PhpStorm.
 * User: Utisateur
 * Date: 19/03/2019
 * Time: 14:26
 */

namespace App\Services;


use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class imageHandler
{
    private $path;


    public function __construct($path)
    {
        $this->path = $path.'/public/images'; // renvoit le dossier courant public et Images
    }


    public function handle(Image $image)
    {
        //récupère le file soumis - il guette le fichier - c 'est ce qui a été soumis par l'utilisateur
        /** @var UploadedFile $file */
        $file = $image->getFile(); //recupération de l'image soumis par l'utilisateur

        //Il appelle la méthode CreateName => Création d'un nom unique
        $name = $this->createName($file);

        //donne le nom à l'image
        $image->setName($name);

        //déplace le fichier
        $file->move ($this->path, $name); //méthode de l'objet uploadedFile
    }

    //Création d'un nom unique
    private function createName(UploadedFile $file): string
    {
        // on récupère le nom du fichier soumis (getClientOriginalName()) . + extension
        //elle met un nom unique à l'image
        return md5(uniqid()).$file->getClientOriginalName().'.'.$file -> guessExtension();
    }
}