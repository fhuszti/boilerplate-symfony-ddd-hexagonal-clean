<?php

namespace App\Infrastructure\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomAuthenticator extends JWTAuthenticator
{
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $eventDispatcher,
        TokenExtractorInterface $tokenExtractor,
        private readonly UserProviderInterface $userProvider,
        TranslatorInterface $translator = null
    ) {
        parent::__construct($jwtManager, $eventDispatcher, $tokenExtractor, $userProvider, $translator);
    }

    /**
     * @param mixed[] $payload
     */
    public function loadUser(array $payload, string $identity): UserInterface
    {
        if ($this->userProvider instanceof PayloadAwareUserProviderInterface) {
            return $this->userProvider->loadUserByIdentifierAndPayload($identity, $payload);
        }

        return $this->userProvider->loadUserByIdentifier($identity);
    }
}
