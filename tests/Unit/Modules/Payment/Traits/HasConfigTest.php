<?php

declare(strict_types=1);

namespace Modules\Payment\Traits;

use App\Modules\Payment\Traits\HasConfig;
use App\Packages\Exceptions\Sber\ConfigException;
use Exception;
use Illuminate\Support\Facades\Config;
use Tests\SberTestCase;

class HasConfigTest extends SberTestCase
{
    /**
     * Test case to verify that the correct table name is returned for a given table name key.
     *
     * @throws Exception
     */
    public function testShouldReturnTableNameForCorrectTableNameKey(): void
    {
        // Set the expected table name
        $expectedTableName = 'some_payments_table';
        // Set the table name in the config
        Config::set('sberbank-acquiring.tables.payments', $expectedTableName);
        // Create a mock object that has the HasConfigTrait
        $mock = $this->getMockForHasConfigTrait();
        // Assert that the expected table name is returned
        $this->assertEquals($expectedTableName, $mock->getTableName('payments'));
    }

    /**
     * @test
     * Test case to verify that an exception is thrown for a bad table name key.
     */
    public function testShouldThrowExceptionForBadTableNameKey(): void
    {
        // Create a mock object of the class that uses the HasConfigTrait.
        $mock = $this->getMockForHasConfigTrait();
        // Expect an exception of type ConfigException to be thrown.
        $this->expectException(ConfigException::class);
        // Call the getConfigParam method with a bad table name key.
        $mock->getConfigParam('sberbank-acquiring.bad_key');
    }

    /**
     * Test case to verify that the getConfigAuthParams method returns the correct authentication parameters.
     *
     * @throws Exception
     */
    public function testGetConfigAuthParams(): void
    {
        // Set up the authentication parameters
        $authParams = [
            'userName' => 'some_test_userName',
            'password' => 'some_test_password',
            'token'    => 'some_test_token',
        ];
        Config::set('sberbank-acquiring.auth', $authParams);
        // Create a mock object with the HasConfigTrait
        $mock = $this->getMockForHasConfigTrait();
        // Assert that the getConfigAuthParams method returns the expected authentication parameters
        $this->assertEquals($authParams, $mock->getConfigAuthParams());
    }

    /**
     * @test
     * @throws Exception
     */
    public function shouldReturnMerchantLoginParam(): void
    {
        // Set the merchant login value
        $merchantLogin = 'some_test_merchant_login';
        Config::set('sberbank-acquiring.merchant_login', $merchantLogin);
        // Instantiate the mock object
        $mock = $this->getMockForHasConfigTrait();
        // Assert that the getConfigMerchantLoginParam() method returns the expected merchant login value
        $this->assertEquals($merchantLogin, $mock->getConfigMerchantLoginParam());
    }

    /**
     * Test case to verify that the getConfigBaseURIParam method returns the correct base URI parameter.
     *
     * @test
     * @throws Exception
     */
    public function testGetConfigBaseURIParam(): void
    {
        // Set the base URI in the Config class
        $baseUri = 'http://pay-server.test';
        Config::set('sberbank-acquiring.base_uri', $baseUri);
        // Create a mock object that uses the HasConfigTrait
        $mock = $this->getMockForHasConfigTrait();
        // Assert that the getConfigBaseURIParam method returns the expected base URI
        $this->assertEquals($baseUri, $mock->getConfigBaseURIParam());
    }

    /**
     */
    private function getMockForHasConfigTrait()
    {
        return $this->getMockForTrait(HasConfig::class);
    }
}
