<?php

namespace App\Repository;

use App\Entity\StudentGroup;
use Doctrine\ORM\EntityRepository;

class StudentGroupRepository extends EntityRepository
{
    /**
     * @param int $page
     * @param int $perPage
     * @param $createdById
     * @return StudentGroup[]
     */
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

    /**
     * @param int $page
     * @param int $perPage
     * @return StudentGroup[]
     */
    public function getStudentGroups(int $page, int $perPage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }
}