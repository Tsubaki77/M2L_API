<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Ligue;
use App\Entity\Reservations;
use App\Repository\AdherentRepository;
use App\Repository\LigueRepository;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/ligues', name: 'api_ligues_')]
class LigueController extends AbstractController
{
    public function __construct(
        private LigueRepository $ligueRepository,
        private AdherentRepository $adherentRepository,
        private ReservationsRepository $reservationsRepository,
    ) {
    }

    // Liste publique (id, nom), utilisée pour le formulaire d'inscription mobileapp.
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $ligues = $this->ligueRepository->findAll();

        return $this->json(array_map(fn (Ligue $l) => $l->toArray(), $ligues));
    }

    
    #[Route('/stats', name: 'stats', methods: ['GET'])]
    #[IsGranted('ROLE_GESTIONNAIRE')]
    public function stats(): JsonResponse
    {
        $ligues = $this->ligueRepository->findAll();

        $result = array_map(function (Ligue $ligue) {
            $adherents = $this->adherentRepository->findByLigue($ligue);

            $nbReservations = 0;
            foreach ($adherents as $adherent) {
                $nbReservations += $this->countReservations($adherent);
            }

            return [
                'id'             => $ligue->getId(),
                'nom'            => $ligue->getNom(),
                'nbAdherents'    => count($adherents),
                'nbReservations' => $nbReservations,
            ];
        }, $ligues);

        return $this->json($result);
    }

    #[Route('/{id}/membres', name: 'membres', methods: ['GET'])]
    #[IsGranted('ROLE_GESTIONNAIRE')]
    public function membres(int $id): JsonResponse
    {
        $ligue = $this->ligueRepository->find($id);
        if (!$ligue) {
            return $this->json(['message' => 'Ligue introuvable'], Response::HTTP_NOT_FOUND);
        }

        $adherents = $this->adherentRepository->findByLigue($ligue);

        $result = array_map(fn (Adherent $adherent) => [
            'id'             => $adherent->getId(),
            'nom'            => $adherent->getNom(),
            'prenom'         => $adherent->getPrenom(),
            'ligue'          => $ligue->getNom(),
            'poste'          => $adherent->getPoste(),
            'nbReservations' => $this->countReservations($adherent),
        ], $adherents);

        return $this->json($result);
    }

    #[Route('/membres/{id}/reservations', name: 'membre_reservations', methods: ['GET'])]
    #[IsGranted('ROLE_GESTIONNAIRE')]
    public function reservationsMembre(int $id): JsonResponse
    {
        $adherent = $this->adherentRepository->find($id);
        if (!$adherent) {
            return $this->json(['message' => 'Adhérent introuvable'], Response::HTTP_NOT_FOUND);
        }

        $reservations = $this->reservationsQueryBuilder($adherent)->getQuery()->getResult();

        return $this->json([
            'adherent'     => $adherent->toArray(),
            'reservations' => array_map(fn (Reservations $r) => $r->toArray(), $reservations),
        ]);
    }

    private function countReservations(Adherent $adherent): int
    {
        return (int) $this->reservationsQueryBuilder($adherent)
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    private function reservationsQueryBuilder(Adherent $adherent): QueryBuilder
    {
        $qb = $this->reservationsRepository->createQueryBuilder('r')
            ->andWhere('r.adherent = :adherent')
            ->setParameter('adherent', $adherent);

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $qb->join('r.salle', 's')
               ->andWhere('s.gestionnaire = :gestionnaire')
               ->setParameter('gestionnaire', $this->getUser());
        }

        return $qb;
    }
}
