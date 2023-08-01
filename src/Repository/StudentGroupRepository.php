<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class StudentGroupRepository extends EntityRepository
{
    public function findByCreatedBy(int $page, int $perPage, $createdById): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->where('t.created_by = :creator')
            ->setParameter('creator', $createdById)
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }
}