<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\ORM\EntityRepository;

class SkillRepository extends EntityRepository
{
    /**
     * @param int $page
     * @param int $perPage
     * @return Skill[]
     */
    public function getSkills(int $page, int $perPage): array
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