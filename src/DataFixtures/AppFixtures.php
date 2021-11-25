<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\Status;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un nouvel objet Faker
        $faker = Factory::create('fr_FR');

        // Création de 5 utilisateurs
        for ($u = 0; $u < 5; $u++) {

            // Création d'un nouvel objet User
            $user = new User;

            // Hashage de notre mot de passe avec les paramètres de sécurité de notre $user
            // dans /config/packages/security.yaml
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setPassword($hash);

            // Si premier utilisateur créé on lui donne le rôle d'admin
            if ($u === 0) {
                $user->setRoles(["ROLE_ADMIN"])
                    ->setEmail("admin@admin.fr");
            } else {
                $user->setEmail($faker->safeEmail());
            }

            // On fait persister les données
            $manager->persist($user);
        }

        // Création de nos 5 catégories
        for ($c = 0; $c < 5; $c++) {
            // Création d'un nouvel objet Tag
            $tag = new Tag;

            // On ajoute un nom à notre catégorie
            $tag->setName($faker->colorName());

            // On fait persister les données
            $manager->persist($tag);
        }



        //Création de nos statuts
        for ($s = 0; $s < 3; $s++) {
            $status = new Status;
            // 1=todo, 2=WIP, 3=done
            $status->setLabel($s + 1);
            $manager->persist($status);
        }

        // On push les catégories en BDD
        $manager->flush();

        // Récupération des catégories créées
        $tags = $manager->getRepository(Tag::class)->findAll();
        $listeUsers = $manager->getRepository(User::class)->findAll();
        $listStatus = $manager->getRepository(Status::class)->findAll();

        // Création entre 15 et 30 tâches aléatoirement
        for ($t = 0; $t < mt_rand(15, 30); $t++) {

            // Création d'un nouvel objet Task
            $task = new Task;

            // On nourrit l'objet Task
            $task->setName($faker->sentence(6))
                ->setDescription($faker->paragraph(3))
                ->setCreatedAt(new \DateTime()) // Attention les dates sont fonctions du paramétrage du serveur
                ->setDueAt($faker->dateTimeBetween('now', '6 months'))
                ->setTag($faker->randomElement($tags))
                ->setUser($faker->randomElement($listeUsers))
                ->setStatus($faker->randomElement($listStatus))
                ->setIsArchived(0);

            // On fait persister les données
            $manager->persist($task);
        }


        $manager->flush();
    }
}
