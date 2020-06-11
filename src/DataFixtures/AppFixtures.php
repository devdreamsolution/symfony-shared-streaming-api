<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserpasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(
            'guide@guide.com',
            'name',
            'surename',
            ['ROLE_GUIDE'],
        );

        $user->setPassword($this->encoder->encodePassword($user, '123456'));

        $manager->persist($user);
        $manager->flush();
    }
}
