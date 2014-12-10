<?php

namespace Barra\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MeasurementRepository extends EntityRepository
{
    public function getSome($first, $amount)
    {
        $query = $this->createQueryBuilder('m')
            ->orderBy('m.type', 'ASC')
            ->setFirstResult($first)
            ->setMaxResults($amount)
            ->getQuery();

        return $query->getResult();
    }

    public function count()
    {
        $query = $this->createQueryBuilder('m')
            ->select('COUNT(m)')
            ->getQuery();
        return $query->getSingleResult()[1];
    }
}