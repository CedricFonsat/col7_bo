<?php

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PasswordResetControllerTest extends WebTestCase
{
    public function testPasswordResetSuccess()
    {
        $client = static::createClient();

        // Envoyer une demande POST de réinitialisation de mot de passe avec un e-mail valide
        $client->request('POST', '/request-password-reset', ['email' => 'ced97x@gmail.com']);

        // Vérifier si la réponse est un succès
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Vérifier si la réponse contient un message de succès
        $this->assertTrue($responseData['success']);
    }

    public function testPasswordResetInvalidEmail()
    {
        $client = static::createClient();

        // Envoyer une demande POST de réinitialisation de mot de passe avec un e-mail invalide
        $client->request('POST', '/request-password-reset', ['email' => 'invalidemail']);

        // Vérifier si la réponse est une erreur
        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Vérifier si la réponse contient un message d'erreur approprié
        $this->assertFalse($responseData['success']);
        $this->assertSame('Invalid email address', $responseData['message']);
    }
}
