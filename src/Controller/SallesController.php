<?php

namespace App\Controller;

use App\Repository\SallesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class SallesController extends AbstractController
{
    #[Route('/api/mes_salles', name: 'api_my_salles', methods: ['GET'])]
    public function mySalles(SallesRepository $repo): JsonResponse
    {
        // 1. On récupère l'admin grâce au Token JWT envoyé par React
        $user = $this->getUser(); 

        // 2. Sécurité : on vérifie que l'utilisateur est bien connecté
        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé ou non connecté'], 401);
        }

        //3. On cherche les salles. 
        //Attention : Dans l'entité Symfony, le champ s'appelle souvent 'admin' 
        // (l'objet complet) et non 'id_admin' (la colonne SQL). 
        $salles = $repo->findBy(['id_admin' => $user]);

        return $this->json($salles);
    }
}
