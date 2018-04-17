<?php

namespace Tests\AppBundle\Security\Authentication;

use AppBundle\Security\Authentication\ApiUserPasswordAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\User;

class ApiUserPasswordAuthenticatorTest extends TestCase{

    // Deprecated because of testGetCredentialsWithoutAnyHeaders
    public function testGetCredentialsWithoutUsernameInRequest(){
        $encoderFactory = new EncoderFactory([]);

        $request = new Request();
        $request->headers->add(['X-PASSWORD' => 'mypwd']);

        $authenticator = new ApiUserPasswordAuthenticator($encoderFactory);
        $result = $authenticator->getCredentials($request);

        $this->assertSame(null,$result);

    }

    // Deprecated beceause of testGetCredentialsWithoutAnyHeaders
    public function testGetCredentialsWithoutPasswordInRequest()
    {
        $encoderFactory = new EncoderFactory([]);

        $request = new Request();
        $request->headers->add(['X-USERNAME' => 'ME']);

        $authenticator = new ApiUserPasswordAuthenticator($encoderFactory);
        $result = $authenticator->getCredentials($request);

        $this->assertNull($result);
    }

    /**
     * @dataProvider getHeaders
     */
    public function testGetCredentialsWithoutAnyHeaders($headers){
        $encoderFactory = new EncoderFactory([]);

        $request = new Request();
        $request->headers->add($headers);

        $authenticator = new ApiUserPasswordAuthenticator($encoderFactory);
        $result = $authenticator->getCredentials($request);

        $this->assertSame(null,$result);
    }

    public function testGetCredentialsFromHeaders(){
        $encoderFactory = new EncoderFactory([]);

        $request = new Request();
        $request->headers->add(['X-PASSWORD' => 'mypwd']);
        $request->headers->add(['X-USERNAME' => 'ME']);

        $authenticator = new ApiUserPasswordAuthenticator($encoderFactory);
        $result = $authenticator->getCredentials($request);

        $expected = ['username' => 'ME', 'password' => 'mypwd'];

        $this->assertSame($expected,$result);
    }

    public function getHeaders(){
        return [
            [['X-USERNAME' => 'ME']],
            [['X-PASSWORD' => 'mypwd']],
        ];
    }







}