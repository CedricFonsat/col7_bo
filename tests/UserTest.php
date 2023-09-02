<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetNickname() {
        $user = new User();
        $user->setNickname('lepetitprince'); static::assertEquals('lepetitprince', $user->getNickname());
    }
    public function testGetEmail() {
        $user = new User();
        $user->setPassword('antoine@gmail.com'); static::assertEquals('antoine@gmail.com', $user->getPassword());
    }
    public function testGetPassword() {
        $user = new User();
        $user->setPassword('Antoine123@'); static::assertEquals('Antoine123@', $user->getPassword());
    }
}

