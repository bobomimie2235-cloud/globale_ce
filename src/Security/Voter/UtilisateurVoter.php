<?php

namespace App\Security\Voter;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;

final class UtilisateurVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Utilisateur;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // Pas connecté → refus
        if (!$user instanceof Utilisateur) {
            return false;
        }

        /** @var Utilisateur $subject */
        $utilisateur = $subject;

        // ADMIN → accès total
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // USER → uniquement son propre profil
        return match ($attribute) {
            self::VIEW, self::EDIT => $utilisateur->getId() === $user->getId(),
            default => false,
        };
    }
}