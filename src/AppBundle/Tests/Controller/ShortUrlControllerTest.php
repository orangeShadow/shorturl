<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShortUrlControllerTest extends WebTestCase
{
    public function testGetshorturllist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/shorturl');
    }

}
