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
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('auteur',TextType::class)
            ->add('resume', TextareaType::class,['label' => 'Résumé'])
            ->add('prix',NumberType::class)

            ->add('editions', EntityType::class, [
                'class'=> Edition::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,

            ])

            ->add('categorie', ChoiceType::class, [
                'choices' =>
                    array_combine( SearchLivreType::CATEGORIE, SearchLivreType::CATEGORIE)
            ])
            ->add('etat', ChoiceType::class, [
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


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livres::class,
        ]);
    }
}
