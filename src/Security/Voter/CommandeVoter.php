<?php

namespace App\Security\Voter;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CommandeVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const EDIT = 'EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT], true)
            && $subject instanceof Commande;
    }

    /**
 * @param string $attribute
 * @param Commande $subject
 * @param TokenInterface $token
 */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool {
        $user = $token->getUser();

        if (!$user instanceof Utilisateur) {
            return false;
        }

        /** @var Commande $commande */
        $commande = $subject;

        // ✅ ADMIN → accès total
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // ✅ USER → uniquement ses commandes
        return match ($attribute) {
            self::VIEW, self::EDIT => $commande->getUtilisateur()?->getId() === $user->getId(),
            default => false,
        };
    }
}