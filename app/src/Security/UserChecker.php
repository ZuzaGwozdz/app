<?php
/**
 * User Checker.
 */

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserChecker.
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * Checking if user is blocked.
     * 
     * @param UserInterface $user UserInterface
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user is blocked, show a generic Disabled message.
        if ($user->getUserData()->getBlocked()) {
            throw new DisabledException();
        }
    }

    /**
     * Checking Post Autentification.
     * 
     * @param UserInterface $user UserInterface
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }
}
