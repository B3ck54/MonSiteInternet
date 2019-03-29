<?php

namespace App\Form;

use App\Entity\Edition;
use App\Entity\Livres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchLivreType extends AbstractType
{
    const CATEGORIE = [
        'Toutes les catégories',
        'Roman',
        'Poésie',
        'Nouvelle',
        'Biographie',
        'Science-Fiction',
        'Fantastique',
    ];

    const ETAT = [
        'Tous les états',
        'Neuf',
        'Trés bon état',
        'Bon état',
        'Mauvais état'
    ];

    const EDITIONS = [
        'Toutes les éditions',
        'J\'aime lire' ,
        'Folio',
        'J\'ai lu',
        'Librio',
        'Les Editions de minuit',
        'Pour les nuls',
        'Livre de poche',
        'Pocket',
        'Eyrolles',
        'Hachette',
        'Larousse',
    ];

    const PRICE =
        [1,5,10,15,20,25,30,35,40,45,50,];





    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add ('titre', TextType::class,[
                'required' => false,
               ])

            ->add('categorie', ChoiceType::class, [
                'choices' =>
                    array_combine( self::CATEGORIE, self::CATEGORIE)
            ])
            ->add('etat', ChoiceType::class, [
                'choices' =>
                    array_combine( self::ETAT, self::ETAT)
            ])
//            ->add('editions', EntityType::class, [
//                'class'=> Edition::class,
//                'choice_label' => 'name'
//            ])

            ->add('editions', ChoiceType::class, [
                'choices' =>
                    array_combine( self::EDITIONS, self::EDITIONS),

            ])


            ->add('minimumPrice',ChoiceType::class, [
                'label' => 'Prix minimum',
                'choices' => array_combine(self::PRICE,self::PRICE)
            ])
            ->add('maximumPrice',ChoiceType::class, [
                'label' => 'Prix maximum',
                'choices' => array_combine(self::PRICE,self::PRICE)
            ])
            ->add('recherche', SubmitType::class)

        ;
    }

}
