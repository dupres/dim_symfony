<?php

namespace AppBundle\Security\Authentication;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiUserPasswordAuthenticator extends AbstractGuardAuthenticator{

    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function getCredentials(Request $request)
    {
        if (!$request->headers->has('X_USERNAME') or !$request->headers->has('X_PASSWORD')){
            return null;
        }

        $credentials = [];

        $credentials['username'] = $request->headers->get('X_USERNAME');
        $credentials['password'] = $request->headers->get('X_PASSWORD');

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $userProvider->loadUserByUsername($credentials['username']);
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        if ($encoder->isPasswordValid($user->getPassword(),$credentials['password'],null)){
            return true;
        }

        throw new AuthenticationException();

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse('Authentication failed.',Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse('The authentication is required.',Response::HTTP_UNAUTHORIZED);
    }




}