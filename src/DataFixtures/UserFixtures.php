<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    
    }

    public function load(ObjectManager $manager)
    {
        // Liste des utilisateurs à ajouter
        $names = [
            'Cedric',
            'Magalie',
            'Pierre',
        ];

        foreach ($names as $name) {
            //On crée l'utilisateur
            $user = new User();
            $user->setUsername($name);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setEmail($name . '@test.com');
            $user->setCreatedDate(\DateTime::createFromFormat('d/m/Y', '06/12/2018'));
            $user->setAvatar('default.png');
            $user->setRoles(['ROLE_USER']);
            // On le persiste
            $manager->persist($user);
        }
        // On déclenche l'enregistrement de tous les utilisateurs
        $manager->flush();
    }
}
