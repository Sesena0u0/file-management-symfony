<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Folder;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create();

        //pour l'utilisateur
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($faker->userName);
            $user->setEmail($faker->email);
            $user->setRoles(['USER-ROLES']);

            $user->setPlainPassword("sesena");
            $users[] = $user;

            $manager->persist($user);
        }

        //pour le folder
        for ($i = 0; $i < 10; $i++) {
            $folder = new Folder();
            $folder->setName($faker->word);
            $folder->setSize($faker->numberBetween);
            $folder->setUser($users[mt_rand(0, count($users)-1)]);

            $manager->persist($folder);
        }

        $manager->flush();
    }
}
