<?php

namespace App\DataFixtures;

use App\Entity\FrontUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FrontUserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $frontUser = new FrontUser();
        $frontUser->setFirstname("Marie")
                    ->setLastname("Geneste")
                    ->setIsActive(true)
                    ->setEmail("marie.geneste.formation@gmail.com")
                    ->setPassword("testtest");
        $manager->persist($frontUser);

        $manager->flush();
    }
}
