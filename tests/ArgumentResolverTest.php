<?php declare(strict_types=1);

namespace Sofyco\Bundle\JsonRequestBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ArgumentResolverTest extends WebTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        restore_exception_handler();
    }

    public function testExample(): void
    {
        $body = \json_encode(['name' => 'John', 'age' => 21, 'isAgree' => true, 'child' => ['id' => 56789]]) ?: '';

        $client = self::createClient();
        $client->request(Request::METHOD_POST, '/12345', [], [], ['CONTENT_TYPE' => 'application/json'], $body);
        $response = $client->getResponse();

        $expected = '{"id":12345,"age":21,"name":"John","isAgree":true,"child":{"id":56789}}';

        self::assertSame($expected, $response->getContent());
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testBadRequest(): void
    {
        $this->expectException(BadRequestHttpException::class);

        $body = \json_encode(['name' => []]) ?: '';

        $client = self::createClient();
        $client->catchExceptions(false);
        $client->request(Request::METHOD_POST, '/12345', [], [], ['CONTENT_TYPE' => 'application/json'], $body);
    }

    public function testRequestBodyToParameters(): void
    {
        $data = ['name' => 'John'];
        $body = \json_encode($data) ?: '';

        $client = self::createClient();
        $client->request(Request::METHOD_POST, '/12345', [], [], ['CONTENT_TYPE' => 'application/json'], $body);
        $response = $client->getResponse();

        $expected = '{"id":12345,"name":"John"}';

        self::assertSame($expected, $response->getContent());
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertSame($data, $client->getRequest()->request->all());
    }
}
