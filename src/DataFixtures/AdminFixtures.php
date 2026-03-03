<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. LE SUPER ADMIN (Le Directeur)
        $superAdmin = new Admin();
        $superAdmin->setIdentifiant('CGH'); 
        $superAdmin->setNom('GHEZALI'); // Ajout obligatoire
        $superAdmin->setPrenom('CAMELIA'); // Ajout obligatoire
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']); 
        
        // On hache le mot de passe 
        $superAdmin->setPassword($this->passwordHasher->hashPassword($superAdmin, 'boss123'));
        $manager->persist($superAdmin);

        // 2. LES ADMINS NORMAUX (Les gestionnaires)
        for ($i = 1; $i <= 2; $i++) {
            $admin = new Admin();
            $admin->setIdentifiant("gestionnaire_$i");
            $admin->setNom("Nom_$i"); // Ajout obligatoire
            $admin->setPrenom("Prenom_$i"); // Ajout obligatoire
            $admin->setRoles(['ROLE_ADMIN']); 
            
            //A modifier pour mdp en général
            // On hache le mot de passe "admin123"
            $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
            $manager->persist($admin);
        }

        $manager->flush();
    }
}