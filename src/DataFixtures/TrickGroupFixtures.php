<?php

namespace App\DataFixtures;

use App\Entity\TrickGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TrickGroupFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Liste des noms de groupes à ajouter
        $names = [
            'Grabs',
            'Rotations',
            'Flips',
            'Rotations désaxées',
            'Slides',
            'One foot tricks',
            'Old school'
        ];

        foreach ($names as $name) {
            // On crée le groupe
            $group = new TrickGroup();
            $group->setName($name);
            // On le persiste
            $manager->persist($group);
        }
        // On déclenche l'enregistrement de tous les groupes
        $manager->flush();
    }
}
