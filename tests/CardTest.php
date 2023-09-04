<?php

namespace App\Tests;
use App\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class CardTest extends KernelTestCase
{
/*    public function testCardValidation()
    {
        $card = new Card();
        $card->setName('Test Card');
        $card->setPrice(10);
        $card->setIfAvailable(true);

        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $errors = $validator->validate($card);

        $this->assertCount(0, $errors);
    }*/

    public function testCardImageUrl()
    {
        $card = new Card();
        $card->setImageName('test-image.jpg');
        $imageUrl = $card->getImageUrl();

        $this->assertEquals('http://192.168.1.123:8000/uploads/cards/test-image.jpg', $imageUrl);
    }
}


