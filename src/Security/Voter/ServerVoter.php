<?php

namespace App\Security\Voter;

use App\Entity\Server;
use App\Entity\ServerMember;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ServerVoter extends Voter
{
    private const ACCESS_LEVELS = [
        'GUEST' => ServerMember::ACCESS_LEVEL_GUEST,
        'MEMBER' => ServerMember::ACCESS_LEVEL_MEMBER,
        'OWNER' => ServerMember::ACCESS_LEVEL_OWNER
    ];

    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    protected function supports($attribute, $subject): bool
    {
        return (array_key_exists($attribute, self::ACCESS_LEVELS) || in_array($attribute, ['DELETE', 'EDIT', 'VIEW']))
            && $subject instanceof Server;
    }

    /**
     * @param string         $attribute
     * @param Server         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if (!$serverMember = $subject->getMemberByUser($user)) {
            return false;
        }

        if ($accessLevel = self::ACCESS_LEVELS[$attribute] ?? null) {
            return $serverMember->getAccessLevel() >= $accessLevel;
        }

        switch ($attribute) {
            case 'DELETE':
            case 'EDIT':
                return $serverMember->getAccessLevel() >= ServerMember::ACCESS_LEVEL_OWNER;
            case 'VIEW':
                return $serverMember->getAccessLevel() >= ServerMember::ACCESS_LEVEL_MEMBER;
        }

        return false;
    }
}
