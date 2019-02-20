<?php

namespace App\DataFixtures;

use App\Entity\TrickVideo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickVideoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Liste des vidéos à ajouter
        $video= new TrickVideo();
        $video->setUrl('<iframe width="560" height="315" src="https://www.youtube.com/embed/M5NTCfdObfs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $video->setTrick($this->getReference('Trick-Mute'));
        $manager->persist($video);

        $video= new TrickVideo();
        $video->setUrl('<iframe width="560" height="315" src="https://www.youtube.com/embed/KEdFwJ4SWq4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $video->setTrick($this->getReference('Trick-Sad'));
        $manager->persist($video);

        $video= new TrickVideo();
        $video->setUrl('<iframe width="560" height="315" src="https://www.youtube.com/embed/zqJLx7vZ7Uk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $video->setTrick($this->getReference('Trick-180 Backside'));
        $manager->persist($video);

        $video= new TrickVideo();
        $video->setUrl('<iframe width="560" height="315" src="https://www.youtube.com/embed/q1ENzzd7RsY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $video->setTrick($this->getReference('Trick-360 Frontside'));
        $manager->persist($video);

        $video= new TrickVideo();
        $video->setUrl('<iframe width="560" height="315" src="https://www.youtube.com/embed/CzDjM7h_Fwo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $video->setTrick($this->getReference('Trick-Japan Air'));
        $manager->persist($video);

        $video= new TrickVideo();
        $video->setUrl('<iframe width="560" height="315" src="https://www.youtube.com/embed/QF2rtZBsjIo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $video->setTrick($this->getReference('Trick-Haakon Flip'));
        $manager->persist($video);

        $video= new TrickVideo();
        $video->setUrl('<iframe width="560" height="315" src="https://www.youtube.com/embed/tjMo7FfW2WE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $video->setTrick($this->getReference('Trick-Crippler'));
        $manager->persist($video);

        $video= new TrickVideo();
        $video->setUrl('<iframe width="560" height="315" src="https://www.youtube.com/embed/kxZbQGjSg4w" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $video->setTrick($this->getReference('Trick-50-50'));
        $manager->persist($video);

        // On déclenche l'enregistrement de toutes les vidéos
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TrickFixtures::class,
        );
    }
}
