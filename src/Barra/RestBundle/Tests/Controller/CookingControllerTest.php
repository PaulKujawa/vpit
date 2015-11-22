<?php

namespace Barra\RestBundle\Tests\Controller;

use FOS\RestBundle\Util\Codes;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class CookingControllerTest
 * @package Barra\RestBundle\Tests\Controller
 */
class CookingControllerTest extends WebTestCase
{
    /** @var  Client */
    protected $client;


    /**
     * Login with credentials to receive JWT and attach it as future request http_auth header
     */
    public function setUp()
    {
        $this->loadFixtures([
            'Barra\AdminBundle\DataFixtures\ORM\LoadUserData',
            'Barra\AdminBundle\DataFixtures\ORM\LoadRecipeData',
            'Barra\AdminBundle\DataFixtures\ORM\LoadCookingData',
        ]);

        $this->client = static::createClient();
        $csrfToken    = $this->client
            ->getContainer()
            ->get('form.csrf_provider')
            ->generateCsrfToken('authenticate')
        ;

        $this->client->request(
            'POST',
            '/de/admino/login_check',
            [
                '_username'     => 'demoSA',
                '_password'     => 'testo',
                '_csrf_token'   => $csrfToken,
            ]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $response);

        $this->client = static::createClient(); // without (recent/any) session
        $this->client->setServerParameter('HTTP_Authorization', 'Bearer '.$response['token']);
    }


    public function testNew()
    {
        $this->client->request('GET', '/api/cookings/new');
        $this->validateResponse(Codes::HTTP_OK, '{"data":{"children":{"description":[],"recipe":[]}}}');
    }


    public function testGet()
    {
        $this->client->request('GET', '/api/cookings/11');
        $this->validateResponse(Codes::HTTP_OK, '{"data":{"id":11,"description":"1th step","position":1}}');

        $this->client->request('GET', '/api/cookings/0');
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }


    public function testCget()
    {
        $this->client->request('GET', '/api/cookings?limit=2');
        $this->validateResponse(
            Codes::HTTP_OK,
            '{"data":['.
                '{"id":11,"description":"1th step","position":1},'.
                '{"id":12,"description":"2th step","position":2}'.
            ']}'
        );

        $this->client->request('GET', '/api/cookings');
        $this->validateResponse(Codes::HTTP_BAD_REQUEST);
    }


    public function testGetRecipe()
    {
        $this->client->request('GET', '/api/cookings/11/recipe');
        $this->validateResponse(Codes::HTTP_OK, '{"data":{"id":1,"name":"Recipe1"}}');

        $this->client->request('GET', '/api/cookings/0/recipe');
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }


    public function testPost()
    {
        $this->client->request(
            'POST',
            '/api/cookings',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"formCooking":{"description":"new step","recipe":1}}'
        );

        $this->validateResponse(Codes::HTTP_CREATED);
        $this->assertStringEndsWith('/api/cookings/14', $this->client->getResponse()->headers->get('Location'));
    }


    public function testPostInvalid()
    {
        $this->client->request(
            'POST',
            '/api/cookings',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{}'
        );
        $this->validateResponse(Codes::HTTP_BAD_REQUEST, '{"data":{"children":{"description":[],"recipe":[]}}}');


        $this->client->request(
            'POST',
            '/api/cookings',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"formCooking":{"recipe":0}}'
        );
        $this->validateResponse(Codes::HTTP_BAD_REQUEST, '{"data":{"children":{"description":[],"recipe":[]}}}');
    }


    public function testPut()
    {
        $this->client->request(
            'PUT',
            '/api/cookings/11',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"formCooking":{"description":"updated step","recipe":1}}'
        );
        $this->validateResponse(Codes::HTTP_NO_CONTENT);
        $this->assertStringEndsWith('/api/cookings/11', $this->client->getResponse()->headers->get('Location'));

        $this->client->request(
            'PUT',
            '/api/cookings/0',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{}'
        );
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }


    public function testDelete()
    {
        $this->client->request('DELETE', '/api/cookings/11');
        $this->validateResponse(Codes::HTTP_NO_CONTENT);

        $this->client->request('DELETE', '/api/cookings/0');
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }


    /**
     * @param int           $expectedStatusCode
     * @param null|string   $expectedJSON
     */
    protected function validateResponse($expectedStatusCode, $expectedJSON = null)
    {
        $this->assertEquals(
            $expectedStatusCode,
            $this->client->getResponse()->getStatusCode()
        );

        if (null !== $expectedJSON) {
            $this->assertEquals(
                $expectedJSON,
                $this->client->getResponse()->getContent()
            );
        }
    }
}