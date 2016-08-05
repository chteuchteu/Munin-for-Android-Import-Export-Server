<?php

namespace AppBundle\Repository;
use AppBundle\Entity\ExportBag;
use AppBundle\Helper\Util;
use Doctrine\ORM\EntityRepository;

/**
 * ExportBagRepository
 */
class ExportBagRepository extends EntityRepository
{
    /**
     * Removes items older than 1 day
     */
    public function cleanDb()
    {
        $date = (new \DateTime())->modify('-1 day');

        $qb = $this->createQueryBuilder('e');
        $qb
            ->delete('AppBundle:ExportBag', 'e')
            ->where('e.exportDate < :date')
            ->setParameter('date', $date);
        $qb->getQuery()->execute();
    }

    /**
     * @param $version
     * @param $dataString
     * @param $dataType
     * @return ExportBag
     */
    public function insertExportBag($version, $dataString, $dataType)
    {
        // Generate unique password
        $pswd = Util::generateRandomPassword();

        while ($this->findOneBy(['password' => $pswd]) != null)
            $pswd = Util::generateRandomPassword();

        $bag = new ExportBag();
        $bag
            ->setVersion($version)
            ->setDataString($dataString)
            ->setDataType($dataType)
            ->setPassword($pswd);

        $em = $this->getEntityManager();
        $em->persist($bag);
        $em->flush();

        return $bag;
    }

    /**
     * @param $pswd
     * @param $dataType
     * @return null|ExportBag
     */
    public function findExportBag($pswd, $dataType)
    {
        /** @var ExportBag $bag */
        $bag = $this->findOneBy([
            'password' => $pswd,
            'dataType' => $dataType
        ]);

        return $bag;
    }

    public function destroy(ExportBag $dataBag)
    {
        $em = $this->getEntityManager();
        $em->remove($dataBag);
        $em->flush($em);
    }
}
