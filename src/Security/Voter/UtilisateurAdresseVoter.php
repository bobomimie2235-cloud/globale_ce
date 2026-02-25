<?php

namespace App\Security\Voter;
use App\Entity\UtilisateurAdresse;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\User\UserInterface;

final class UtilisateurAdresseVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\UtilisateurAdresse;
    }

    /**
 * @param string $attribute
 * @param UtilisateurAdresse $subject
 * @param TokenInterface $token
 */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool {
        $user = $token->getUser();

        if (!$user instanceof Utilisateur) {
            return false;
        }

        /** @var UtilisateurAdresse $commande */
        $utilisateurAdresse = $subject;

        // ✅ ADMIN → accès total
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // ✅ USER → uniquement ses commandes
        return match ($attribute) {
            self::VIEW, self::EDIT => $utilisateurAdresse->getUtilisateur() === $user,
            default => false,
        };
    }
}
