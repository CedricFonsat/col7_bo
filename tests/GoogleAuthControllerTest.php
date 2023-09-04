<?php

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class GoogleAuthControllerTest extends WebTestCase
{
    public function testGoogleAuthenticationSuccess()
    {
        $client = static::createClient();

        // Envoyer une demande POST pour simuler une authentification réussie
        $client->request('POST', '/google-auth', ['google_id_token' => 'valid_google_id_token']);

        // Vérifier si la réponse est un succès
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Vérifier si la réponse contient un message de succès
        $this->assertTrue($responseData['success']);
    }

    public function testGoogleAuthenticationFailure()
    {
        $client = static::createClient();

        // Envoyer une demande POST pour simuler une authentification échouée
        $client->request('POST', '/google-auth', ['google_id_token' => 'invalid_google_id_token']);

        // Vérifier si la réponse est une erreur
        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Vérifier si la réponse contient un message d'erreur approprié
        $this->assertFalse($responseData['success']);
        $this->assertSame('Google authentication failed', $responseData['message']);
    }
}
