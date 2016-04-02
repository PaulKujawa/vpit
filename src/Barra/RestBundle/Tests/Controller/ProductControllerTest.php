<?php

namespace Barra\RestBundle\Tests\Controller;

use Barra\RecipeBundle\DataFixtures\ORM\LoadIngredientData;
use Barra\RecipeBundle\DataFixtures\ORM\LoadManufacturerData;
use Barra\RecipeBundle\DataFixtures\ORM\LoadMeasurementData;
use Barra\RecipeBundle\DataFixtures\ORM\LoadProductData;
use Barra\RecipeBundle\DataFixtures\ORM\LoadRecipeData;
use Barra\RecipeBundle\DataFixtures\ORM\LoadUserData;
use FOS\RestBundle\Util\Codes;
use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class ProductControllerTest extends WebTestCase
{
    private $appType = ['CONTENT_TYPE' => 'application/json'];

    /** @var  Client */
    protected $client;

    /**
     * Login with credentials to receive JWT and attach it as future request http_auth header
     */
    public function setUp()
    {
        $this->loadFixtures([LoadUserData::class, LoadManufacturerData::class, LoadProductData::class]);

        $this->client = static::createClient();
        $this->client->request('POST', '/en/admino/login_check', ['_username' => 'demoSA', '_password' => 'testo']);

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->client->setServerParameter('HTTP_Authorization', 'Bearer ' . $response['token']);
    }

    public function testNew()
    {
        $this->client->request('GET', '/en/api/products/new');
        $this->validateResponse(
            Codes::HTTP_OK,
            '{"data":{"children":{' .
                '"name":[],"vegan":[],"gr":[],"kcal":[],"protein":[],"carbs":[],' .
                '"sugar":[],"fat":[],"gfat":[],"manufacturer":[]' .
            '}}}'
        );
    }

    public function testGet()
    {
        $this->client->request('GET', '/en/api/products/1');
        $this->validateResponse(
            Codes::HTTP_OK,
            '{"data":{"vegan":false,"gr":1,"kcal":1,"protein":1,"carbs":1,' .
            '"sugar":1,"fat":1,"gfat":1,"id":1,"name":"Product1"}}'
        );

        $this->client->request('GET', '/en/api/products/0');
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }

    public function testCget()
    {
        $this->client->request('GET', '/en/api/products?limit=2');
        $this->validateResponse(
            Codes::HTTP_OK,
            '{"data":[' .
                '{' .
                    '"vegan":false,"gr":1,"kcal":1,"protein":1,"carbs":1,' .
                    '"sugar":1,"fat":1,"gfat":1,"id":1,"name":"Product1"' .
                '},{' .
                    '"vegan":true,"gr":1,"kcal":1,"protein":1,"carbs":1,' .
                    '"sugar":1,"fat":1,"gfat":1,"id":2,"name":"Product2"' .
                '}' .
            ']}'
        );

        $this->client->request('GET', '/en/api/products');
        $this->validateResponse(Codes::HTTP_BAD_REQUEST);
    }

    public function testGetRecipe()
    {
        $this->client->request('GET', '/en/api/products/1/manufacturer');
        $this->validateResponse(Codes::HTTP_OK, '{"data":{"id":1,"name":"Manufacturer1"}}');

        $this->client->request('GET', '/en/api/products/0/manufacturer');
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }

    public function testGetIngredients()
    {
        $this->loadFixtures([
            LoadUserData::class,
            LoadManufacturerData::class,
            LoadMeasurementData::class,
            LoadRecipeData::class,
            LoadProductData::class,
            LoadIngredientData::class,
        ]);

        $this->client->request('GET', '/en/api/products/1/ingredients');
        $this->validateResponse(
            Codes::HTTP_OK,
            '{"data":[' .
                '{"amount":1,"id":1,"position":1},' .
                '{"amount":3,"id":3,"position":3}' .
            ']}'
        );

        $this->client->request('GET', '/en/api/products/0/ingredients');
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }

    public function testPost()
    {
        $requestBody = '{"product":{' .
            '"name":"new product","vegan":1,"gr":1,"kcal":1,"protein":1.1,"carbs":1.2,' .
            '"sugar":1.3,"fat":1.4,"gfat":1.5,"manufacturer":1' .
        '}}';

        $this->client->request('POST', '/en/api/products', [], [], $this->appType, $requestBody);

        $this->validateResponse(Codes::HTTP_CREATED);
        $this->assertStringEndsWith('/en/api/products/5', $this->client->getResponse()->headers->get('Location'));
    }

    public function testPostInvalid()
    {
        $this->client->request('POST', '/en/api/products', [], [], $this->appType, '{}');
        $this->validateResponse(
            Codes::HTTP_BAD_REQUEST,
            '{"data":{"children":{' .
                '"name":[],"vegan":[],"gr":[],"kcal":[],"protein":[],"carbs":[],' .
                '"sugar":[],"fat":[],"gfat":[],"manufacturer":[]' .
            '}}}'
        );
    }

    public function testPut()
    {
        $requestBody = '{"product":{' .
            '"name":"updated product","vegan":1,"gr":1,"kcal":1,"protein":1.1,"carbs":1.2,' .
            '"sugar":1.3,"fat":1.4,"gfat":1.5,"manufacturer":1' .
        '}}';

        $this->client->request('PUT', '/en/api/products/1', [], [], $this->appType, $requestBody);
        $this->validateResponse(Codes::HTTP_NO_CONTENT);
        $this->assertStringEndsWith('/en/api/products/1', $this->client->getResponse()->headers->get('Location'));

        $this->client->request('PUT', '/en/api/products/0', [], [], $this->appType, '{}');
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }

    public function testDelete()
    {
        $this->client->request('DELETE', '/en/api/products/1');
        $this->validateResponse(Codes::HTTP_NO_CONTENT);

        $this->client->request('DELETE', '/en/api/products/0');
        $this->validateResponse(Codes::HTTP_NOT_FOUND);
    }

    public function testDeleteInvalid()
    {
        $this->loadFixtures([
            LoadUserData::class,
            LoadManufacturerData::class,
            LoadMeasurementData::class,
            LoadRecipeData::class,
            LoadProductData::class,
            LoadIngredientData::class,
        ]);

        $this->client->request('DELETE', '/en/api/products/1');
        $this->validateResponse(Codes::HTTP_CONFLICT);
    }

    /**
     * @param int $expectedStatusCode
     * @param null|string $expectedJSON
     */
    protected function validateResponse($expectedStatusCode, $expectedJSON = null)
    {
        $this->assertEquals($expectedStatusCode, $this->client->getResponse()->getStatusCode());

        if (null !== $expectedJSON) {
            $this->assertEquals($expectedJSON, $this->client->getResponse()->getContent());
        }
    }
}
