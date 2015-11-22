<?php

namespace Barra\AdminBundle\DataFixtures\ORM;

use Barra\AdminBundle\Entity\Manufacturer;
use Barra\AdminBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use InvalidArgumentException;

/**
 * Class LoadProductData
 * @author Paul Kujawa <p.kujawa@gmx.net>
 * @package Barra\AdminBundle\DataFixtures\ORM
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    static public $members = [];


    public function load(ObjectManager $em)
    {
        $nutritions = [
            'gr'        => 1,
            'kcal'      => 1,
            'carbs'     => 1.0,
            'sugar'     => 1.0,
            'protein'   => 1.0,
            'fat'       => 1.0,
            'gfat'      => 1.0,
        ];
        
        self::$members[] = $this->instantiate('Product1', false, $nutritions, 'refManufacturer1');
        self::$members[] = $this->instantiate('Product2', true, $nutritions, 'refManufacturer1');
        self::$members[] = $this->instantiate('Product3', true, $nutritions, 'refManufacturer1');
        self::$members[] = $this->instantiate('Product4', true, $nutritions, 'refManufacturer1');

        foreach (self::$members as $i => $e) {
            $this->addReference('refProduct'.($i+1), $e);
            $em->persist($e);
        }
        $em->flush();
    }


    /**
     * @param string    $name
     * @param bool      $isVegan
     * @param array     $nutritions
     * @param string    $refManufacturer
     * @return Product
     * @throws InvalidArgumentException
     */
    protected function instantiate($name, $isVegan, array $nutritions, $refManufacturer)
    {
        $manufacturer = $this->getReference($refManufacturer);

        if (!$manufacturer instanceof Manufacturer) {
            throw new InvalidArgumentException();
        }

        $entity = new Product();
        $entity
            ->setName($name)
            ->setVegan($isVegan)
            ->setGr($nutritions['gr'])
            ->setKcal($nutritions['kcal'])
            ->setCarbs($nutritions['carbs'])
            ->setSugar($nutritions['sugar'])
            ->setProtein($nutritions['protein'])
            ->setFat($nutritions['fat'])
            ->setGfat($nutritions['gfat'])
            ->setManufacturer($manufacturer)
        ;

        return $entity;
    }


    public function getOrder()
    {
        return 5;
    }
}
