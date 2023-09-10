<?php

namespace App\Tests;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PasswordResetControllerTest extends TestCase
{
    public function callResetPasswordAPI($email): ?ResponseInterface
    {
        $client = HttpClient::create();

        $baseUrl = 'http://192.168.1.123:8000';

        $response = null;

        try {
            $response = $client->request('POST', $baseUrl . '/request-password-reset', [
                'json' => ['email' => $email],
            ]);
        } catch (TransportExceptionInterface $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        return $response;
    }


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testResetPasswordRequest()
    {
        $response = $this->callResetPasswordAPI('ghermiston@yahoo.com');

        $this->assertNotNull($response);

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Password reset link sent successfully', $responseData['message']);
    }


}
