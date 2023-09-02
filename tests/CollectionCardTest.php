<?php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CollectionCardTest extends WebTestCase
{
    public function testCreateCollectionCard()
    {
        $client = static::createClient();

        $client->request('POST', '/api/collection_cards', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'Test Collection', // Utilisez 'name' au lieu de 'name' => 'TestCollection'
            'author' => 'Test Author',   // Utilisez 'author' au lieu de 'author' => 'TestAuthor'
            'description' => 'Test Description',
            'isComingSoon' => false,
            // Autres champs de votre entité
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
    }


/*    public function testGetCollectionCard()
    {
        // Créer un client HTTP Symfony
        $client = static::createClient();

        // Remplacez {id} par l'ID de la CollectionCard que vous souhaitez récupérer
        $client->request('GET', '/api/collection_cards/{id}');

        // Vérifier si la requête a été effectuée avec succès (statut 200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Récupérer la réponse JSON
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Vérifier que la réponse contient les données attendues de la CollectionCard
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('author', $responseData);
        $this->assertArrayHasKey('description', $responseData);
        $this->assertArrayHasKey('isComingSoon', $responseData);
        // Autres champs de votre entité
    }*/
}
