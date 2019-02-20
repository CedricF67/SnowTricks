<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Liste des figures à ajouter
        $trick = new Trick();
        $trick->setName('Mute');
        $trick->setDescription('Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.');
        $trick->setTrickGroup($this->getReference('Group-Grabs'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('Sad');
        $trick->setDescription('Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.');
        $trick->setTrickGroup($this->getReference('Group-Grabs'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('Indy');
        $trick->setDescription('Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.');
        $trick->setTrickGroup($this->getReference('Group-Grabs'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('180 Backside');
        $trick->setDescription('Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués :
            un 180 désigne un demi-tour, soit 180 degrés d\'angle.');
        $trick->setTrickGroup($this->getReference('Group-Rotations'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('360 Frontside');
        $trick->setDescription('Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués :
            360, trois six pour un tour complet.');
        $trick->setTrickGroup($this->getReference('Group-Rotations'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('Japan Air');
        $trick->setTrickGroup($this->getReference('Group-Old school'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('Haakon Flip');
        $trick->setDescription('Une manœuvre aérienne effectuée dans un halfpipe en décollant en arrière et en effectuant une rotation inversée de 720 °.');
        $trick->setTrickGroup($this->getReference('Group-Flips'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('Crippler');
        $trick->setDescription('Une rotation inversée de 540 degrés effectuée sur la paroi frontale du halfpipe.');
        $trick->setTrickGroup($this->getReference('Group-Rotations'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('Chicane');
        $trick->setDescription('Une chicane est une figure rarement fait qui consiste à faire un 180 frontside avec un front flip sur l\'axe X.');
        $trick->setTrickGroup($this->getReference('Group-Rotations'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

        $trick = new Trick();
        $trick->setName('50-50');
        $trick->setDescription('Glissade dans laquelle un snowboarder chevauche un rail ou un autre obstacle.');
        $trick->setTrickGroup($this->getReference('Group-Slides'));
        $manager->persist($trick);
        $this->addReference('Trick-' . $trick->getName(), $trick);

         // On déclenche l'enregistrement de toutes les figures
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TrickGroupFixtures::class,
        );
    }
}
