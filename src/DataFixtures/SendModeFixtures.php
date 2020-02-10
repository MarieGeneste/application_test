<?php

namespace App\DataFixtures;

use App\Entity\SendMode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SendModeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $sendMode1 = new SendMode();
        $sendMode1->setName("Courrier")
                ->setPrice("0.6");

        $sendMode2 = new SendMode();
        $sendMode2->setName("Email")
                ->setPrice("0.4");

        $manager->persist($sendMode1);
        $manager->persist($sendMode2);

        $manager->flush();
    }
}
