<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param int $page
     * @param int $perPage
     * @return User[]
     */
    public function getUsers(int $page, int $perPage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param string $login
     * @return array
     */
    public function findByLogin(int $page, int $perPage, string $login): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't')
            ->orderBy('t.id', 'DESC')
            ->where('t.login LIKE :login')
            ->setParameter('login', '%' . $login . '%')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param array $userProfileData
     * @return array
     */
    public function findByUserProfile(int $page, int $perPage, array $userProfileData): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getClassName(), 't');

        // TODO findByUserProfile: правильно ли передаётся аргумент $userProfileData. Может его нужно передавать как DTO ?
        // на сколько корректен такой подход в query builder: foreach вместе с $qb->andWhere ?
        // как получить готовый sql ? у меня получалось доставать только dql. это не удобно.

        if (count(array_values($userProfileData))) {
            $qb->leftJoin('t.userProfile', 'userProfile');

            foreach ($userProfileData as $field => $value) {
                $qb->andWhere("userProfile.$field LIKE :$field")
                    ->setParameter($field, '%' . $value . '%');
            }
        }

        $qb
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($perPage * $page)
            ->setMaxResults($perPage);

        return $qb->getQuery()->getResult();
    }
}