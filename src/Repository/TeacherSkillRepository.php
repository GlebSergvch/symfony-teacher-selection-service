<?php

namespace App\Repository;

use App\Entity\TeacherSkill;
use Doctrine\ORM\EntityRepository;

class TeacherSkillRepository extends EntityRepository
{
    /**
     * @param int $page
     * @param int $perPage
     * @return TeacherSkill[]
     */
    public function getTeacherSkill(int $page, int $perPage): array
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