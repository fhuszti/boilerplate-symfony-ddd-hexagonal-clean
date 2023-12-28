<?php

namespace App\Tests\Unit\Infrastructure\Security;

use App\Infrastructure\Security\CustomAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomAuthenticatorTest extends TestCase
{
    private JWTTokenManagerInterface $jwtManager;
    private EventDispatcherInterface $eventDispatcher;
    private TokenExtractorInterface $tokenExtractor;
    private MockObject&UserProviderInterface $userProvider;
    private TranslatorInterface $translator;

    protected function setUp(): void
    {
        $this->jwtManager = $this->createMock(JWTTokenManagerInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->tokenExtractor = $this->createMock(TokenExtractorInterface::class);
        $this->userProvider = $this->createMock(UserProviderInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
    }

    public function testLoadUserWithPayloadAwareUserProvider(): void
    {
        $identity = 'testUser';
        $payload = ['data' => 'testData'];

        $user = $this->createMock(UserInterface::class);
        /* Roundabout way of mocking PayloadAwareUserProviderInterface because as of now,
            loadUserByIdentifierAndPayload is a docblock declared method and PHPUnit doesn't allow mocking those */
        $payloadAwareProvider = $this->getMockBuilder(PayloadAwareUserProviderInterface::class)
            ->onlyMethods(['refreshUser', 'supportsClass', 'loadUserByUsernameAndPayload', 'loadUserByIdentifier'])
            ->addMethods(['loadUserByIdentifierAndPayload'])
            ->getMock();
        $payloadAwareProvider->expects($this->once())
            ->method('loadUserByIdentifierAndPayload')
            ->with($identity, $payload)
            ->willReturn($user);

        $authenticator = new CustomAuthenticator(
            $this->jwtManager,
            $this->eventDispatcher,
            $this->tokenExtractor,
            $payloadAwareProvider,
            $this->translator
        );

        $this->assertSame($user, $authenticator->loadUser($payload, $identity));
    }

    public function testLoadUserFallback(): void
    {
        $identity = 'testUser';
        $user = $this->createMock(UserInterface::class);

        $this->userProvider->expects($this->once())
            ->method('loadUserByIdentifier')
            ->with($identity)
            ->willReturn($user);

        $authenticator = new CustomAuthenticator(
            $this->jwtManager,
            $this->eventDispatcher,
            $this->tokenExtractor,
            $this->userProvider,
            $this->translator
        );

        $this->assertSame($user, $authenticator->loadUser([], $identity));
    }
}
