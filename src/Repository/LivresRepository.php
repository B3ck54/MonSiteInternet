<?php

namespace App\Repository;

use App\Entity\Edition;
use App\Entity\Livres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Livres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livres[]    findAll()
 * @method Livres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivresRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Livres::class);
    }

    public function searchLivre($critere)
    {
        $query = $this->createQueryBuilder('l')

            ->andWhere('l.prix > :minimumPrice')
            ->setParameter('minimumPrice', $critere['minimumPrice'])
            ->andWhere('l.prix < :maximumPrice')
            ->setParameter('maximumPrice', $critere['maximumPrice']);

        if ($critere['editions'] != 'Toutes les éditions')
        {
        $query->leftJoin('l.editions', 'editions')
        ->andWhere('editions.name =  :editionName')
        ->setParameter("editionName", $critere['editions']);
        }

        if ($critere['etat'] != 'Tous les états')
        {
            $query->andWhere('l.etat = :etat')
                ->setParameter('etat', $critere ['etat']);
        }

        if ($critere ['categorie'] != 'Toutes les catégories')
        {
            $query->andWhere('l.categorie = :categorie')
                ->setParameter('categorie', $critere['categorie']);
        }




       return $query ->getQuery()
            ->getResult()
        ;
    }




    // /**
    //  * @return Livres[] Returns an array of Livres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livres
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
