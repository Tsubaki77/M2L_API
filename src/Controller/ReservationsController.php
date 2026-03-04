<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/reservations', name: 'api_reservations_')]
class ReservationsController extends AbstractController
{
    public function __construct(
        private ReservationsRepository $reservationsRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function list(): JsonResponse
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $reservations = $this->reservationsRepository->findAll();
        } else {
            $reservations = $this->reservationsRepository->findBy([
                'adherent' => $user
            ]);
        }

        return $this->json(array_map(
            fn (Reservations $r) => [
                'id' => $r->getId(),
                'dateDebut' => $r->getDateDebut()->format('Y-m-d'),
                'dateFin' => $r->getDateFin()->format('Y-m-d'),
                'heureDebut' => $r->getHeureDebut()->format('H:i'),
                'heureFin' => $r->getHeureFin()->format('H:i'),
                'motif' => $r->getMotif(),
                'statut' => $r->getStatut(),
            ],
            $reservations
        ));
    }

    /**
     * 🔐 Créer une réservation
     */
    #[Route('', name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (
            empty($data['dateDebut']) ||
            empty($data['dateFin']) ||
            empty($data['heureDebut']) ||
            empty($data['heureFin'])
        ) {
            return $this->json([
                'message' => 'Champs obligatoires manquants'
            ], Response::HTTP_BAD_REQUEST);
        }

        $reservation = new Reservations();
        $reservation->setDateDebut(new \DateTime($data['dateDebut']));
        $reservation->setDateFin(new \DateTime($data['dateFin']));
        $reservation->setHeureDebut(new \DateTime($data['heureDebut']));
        $reservation->setHeureFin(new \DateTime($data['heureFin']));
        $reservation->setMotif($data['motif'] ?? null);
        $reservation->setStatut('EN_ATTENTE');
        $reservation->setAdherent($this->getUser());

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Réservation créée'
        ], Response::HTTP_CREATED);
    }

    /**
     * 🔐 Supprimer une réservation (ADMIN ou propriétaire)
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function delete(int $id): JsonResponse
    {
        $reservation = $this->reservationsRepository->find($id);

        if (!$reservation) {
            return $this->json([
                'message' => 'Réservation introuvable'
            ], Response::HTTP_NOT_FOUND);
        }

        if (
            !$this->isGranted('ROLE_ADMIN') &&
            $reservation->getAdherent() !== $this->getUser()
        ) {
            return $this->json([
                'message' => 'Accès refusé'
            ], Response::HTTP_FORBIDDEN);
        }

        $this->entityManager->remove($reservation);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Réservation supprimée'
        ]);
    }
}