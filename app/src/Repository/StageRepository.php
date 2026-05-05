<?php

namespace App\Repository;

use App\Entity\Stage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Override;

/**
 * @extends ServiceEntityRepository<Stage>
 */
class StageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stage::class);
    }

    // #[Override]
    // public function findAll(): array
    // {
    //     return $this->createQueryBuilder('s')
    //             ->addSelect('')
    // }

    /**
     * euh a voir pour le findAll mais FDF
     */
    // select * from stage s 

    // right join etudiant e on e.id = s.etudiant_id

    // right join periode p on p.id = s.periode_id

    // right join organisation o on o.id = s.organisation_id 

    // right join specialite spe on spe.id = e.specialite_id

    // right join categorie cat on cat.id = o.categorie_id

    // -- PK une erreur qui fait que j'ai que 2 result ?

    // left join stage_competence sc on s.id = sc.stage_id

    // left join competence c on c.id = sc.competence_id

    // left join bloc b on b.id = c.bloc_id

    // left join specialite s1 on s1.id = b.specialite_id

    // group by s.id
    // ;

    public function findByGetParam(string $opt, string $ville, string $cp)
    {
        $query = $this->createQueryBuilder('stage')
            ->join('stage.etudiant', 'e')
            ->join('stage.organisation', 'o')
            ->join('e.specialite', 'spe')
            ->orderBy('stage.id', 'ASC');
        if (!empty($opt)) {
            $query->andWhere('spe.sigle = :option')
                ->setParameter('option', $opt);
        }
        if (!empty($ville)) {
            $query->andWhere('o.ville = :ville')
                ->setParameter('ville', $ville);
        }
        if (!empty($cp)) {
            $query->andWhere('o.codePostal = :cp')
                ->setParameter('cp', $cp);
        }
        return $query ->getQuery()
                      ->getResult();
    }

    //    /**
    //     * @return Stage[] Returns an array of Stage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Stage
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
