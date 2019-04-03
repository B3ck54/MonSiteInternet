<?php

namespace App\Form;

use App\Entity\Edition;
use App\Entity\Livres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'label' => 'Titre',
            ])
            ->add('auteur',TextType::class)
            ->add('resume', TextareaType::class)
            ->add('prix',NumberType::class)

            ->add('editions', EntityType::class, [
                'label' => 'Editions :',
                'class'=> Edition::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,

            ])

            ->add('categorie', ChoiceType::class, [
                'label' => false,
                'choices' =>
                    array_combine( SearchLivreType::CATEGORIE, SearchLivreType::CATEGORIE)
            ])
            ->add('etat', ChoiceType::class, [
                'label' => false,
                'choices' =>
                            array_combine( SearchLivreType::ETAT, SearchLivreType::ETAT)
            ])
            //pour le formulaire ajout de keywords
            ->add('keywords', CollectionType::class,[
                'entry_type' => KeywordType::class, //type de formulaire
                'allow_add' => true, //cela veut dire qu'on a le droit d'en ajouter
                'by_reference' => false, // forcer la soumission du formulaire a appeler cette méthode addKeyword
                'label' => null
            ])
            ->add('image', ImageType::class, ['label' => false])
        ;


        // Si y a pas de fichier tu va mettre ma value image à null dans mon objet livre
        $builder -> addEventListener(FormEvents::POST_SUBMIT,
           function (FormEvent $event ) use ($options) { // => fonction anonyme

            $livre=$event->getData();

            if (null === $livre->getImage()->getFile()){
                $livre->setImage(null);
                return;
            }
                $image = $livre->getImage();
                $image->setPath($options['path']);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livres::class,
            'path' => null,
        ]);
    }
}
