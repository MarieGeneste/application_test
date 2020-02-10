<?php

namespace App\DataFixtures;

use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DocumentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $document = new Document();
        $document->setTitle("Document Test")
                ->setIsActive(true)
                ->setPages("4");
        $manager->persist($document);

        $manager->flush();
    }
}
