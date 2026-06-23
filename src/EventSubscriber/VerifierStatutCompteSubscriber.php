<?php

namespace App\EventSubscriber;

use App\Entity\Gestionnaires;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

// Empêche la connexion d'un gestionnaire dont le compte a été désactivé
// par un super-admin (statut = 'inactif'), juste après la vérification
// de son mot de passe.
class VerifierStatutCompteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => 'verifierStatut',
        ];
    }

    public function verifierStatut(CheckPassportEvent $event): void
    {
        $user = $event->getPassport()->getUser();

        if ($user instanceof Gestionnaires && $user->getStatut() === 'inactif') {
            throw new CustomUserMessageAccountStatusException('Ce compte a été désactivé. Contactez un administrateur.');
        }
    }
}
