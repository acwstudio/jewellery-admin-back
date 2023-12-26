<?php

declare(strict_types=1);

namespace Modules\Payment\Clients;

use App\Packages\ApiClients\Payment\Responses\SberbankResponse;
use Tests\SberTestCase;

class SberbankResponseTest extends SberTestCase
{
    /**
     * @throws \JsonException
     */
    public function testGetResponseMethodReturnsUnmodifiedStringResponse(): void
    {
        $responseJson = '{"orderId": "v71n2vc",'
            . '"formUrl":"http://some-url.com",'
            . '"errorCode": 20, '
            . '"errorMessage": "Some error"}';

        $response = new SberbankResponse($responseJson);

        $this->assertEquals($responseJson, $response->getJsonResponse());
    }


    public function testGetFormattedResponseArrayReturnsFormattedResponseArray(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "9nfdv","formUrl":"http://some-url.com","error": {"code": 10, "message": "Error"}}'
        );

        $expectedResult = [
            'orderId' => '9nfdv',
            'formUrl' => 'http://some-url.com',
        ];
        $this->assertEquals($expectedResult, $response->getFormattedData());
    }

    /**
     */
    public function testGetFormattedResponseArrayMethod(): void
    {
        $expectedResponseArray = [
            'orderId' => '9nfdv',
            'formUrl' => 'http://some-url.com',
        ];

        $response = new SberbankResponse(
            '{"orderId": "9nfdv","formUrl":"http://some-url.com","error": {"code": 10, "message": "Error"}}'
        );
        $actualResponseArray = $response->getFormattedData();
        $this->assertEquals($expectedResponseArray, $actualResponseArray);
    }

    /**
     * @test
     */
    public function testGetFormattedResponseArrayMethodReturnsFormattedResponseArray3(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "vc91mx","formUrl":"http://test-url.com","errorCode": 0}'
        );
        $expectedResponse = [
            'orderId' => 'vc91mx',
            'formUrl' => 'http://test-url.com',
        ];
        $this->assertEquals($expectedResponse, $response->getFormattedData());
    }

    /**
     * @test
     */
    public function testGetErrorCodeReturnsCode(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "vc81cx","formUrl":"http://some-url.com","errorCode":10,"success":false}'
        );
        $this->assertEquals(10, $response->getErrorCode());
    }

    /**
     * @test
     */
    public function testGetErrorCodeReturnsCode2(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "fgd55m,421","formUrl":"http://some-url.com","error":{"code":20},"success":true}'
        );
        $this->assertEquals(20, $response->getErrorCode());
    }

    /**
     * @test
     */
    public function testGetErrorCodeReturnsSuccessCodeWhenCodeIsMissingInResponse(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "fgd55m,421","formUrl":"http://some-url.com","success":true}'
        );
        $this->assertEquals(SberbankResponse::CODE_SUCCESS, $response->getErrorCode());
    }

    /**
     * @throws \JsonException
     */
    public function testGetErrorMessageReturnsMessage(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "1325vb",'
            . '"formUrl":"http://some-url.com",'
            . '"errorCode":10,'
            . '"errorMessage":"error occurred"}'
        );
        $this->assertEquals('error occurred', $response->getErrorMessage());
    }


    public function testGetErrorMessageReturnsMessage2(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "f91ca", '
            . '"formUrl":"http://some-url.com", '
            . '"error":{"code":2, "message":"error!"}, '
            . '"success":true}'
        );
        $this->assertEquals('error!', $response->getErrorMessage());
    }


    public function testGetErrorMessageReturnsUnknownErrorMessageWhenMessageIsMissingInResponse(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "ow55d","formUrl":"http://some-url.com","error":{"code":20},"success":true}'
        );
        $this->assertSame(SberbankResponse::UNKNOWN_ERROR_MESSAGE, $response->getErrorMessage());
    }

    /**
     * @test
     */
    public function testIsOkMethodReturnsOperationStatus(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "vqx151","formUrl":"http://some-url.com","errorCode":30}'
        );
        $this->assertFalse($response->isOk());
    }

    /**
     * @test
     */
    public function testIsOkMethodReturnsOperationStatus2(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "14cbqx","formUrl":"http://some-url.com","error":{"code":20}}'
        );
        $this->assertFalse($response->isOk());
    }

    /**
     * @test
     */
    public function testIsOkMethodReturnsOperationStatus3(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "bnv74","formUrl":"http://some-url.com","error":{"code":0}}'
        );
        $this->assertTrue($response->isOk());
    }

    /**
     * @test
     */
    public function testIsOkMethodReturnsOperationStatus4(): void
    {
        $response = new SberbankResponse(
            '{"orderId": "bbcx75","formUrl":"http://some-url.com","errorCode":0}'
        );
        $this->assertTrue($response->isOk());
    }

    /**
     * @test
     */
    public function testIsOkMethodReturnsOperationStatus5(): void
    {
        $response = new SberbankResponse('{"orderId": "bnv74","formUrl":"http://some-url.com"}');
        $this->assertTrue($response->isOk());
    }
}
