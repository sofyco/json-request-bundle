<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\Tests\EventListener;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class JsonRequestListenerTest extends WebTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        restore_exception_handler();
    }

    public function testExample(): void
    {
        $body = json_encode(['name' => 'John', 'age' => 21, 'isAgree' => true, 'child' => ['id' => 56789]]) ?: null;

        $client = self::createClient();
        $client->request(
            method: Request::METHOD_POST,
            uri: '/',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: $body,
        );
        $response = $client->getResponse();

        $expected = '{"name":"John","age":21,"isAgree":true,"child":{"id":56789}}';

        self::assertResponseIsSuccessful();
        self::assertSame($expected, $response->getContent());
    }
}
