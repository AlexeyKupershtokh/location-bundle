<?php

namespace AlexeyKuperhstokh\LocationBundle\Tests\Client;

use AlexeyKuperhstokh\LocationBundle\Client\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $validResponse = <<<JSON
{
    "data": {
        "locations": [
            {
                "name": "Eiffel Tower",
                "coordinates": {
                    "lat": 21.12,
                    "long": 19.56
                }
            },
            {
                "name": "Novosibirsk",
                "coordinates": {
                    "lat": 54.9700492,
                    "long": 82.6692266
                }            
            }
        ]
    },
    "success": true
}
JSON;
        
        $mockRequests = [
            new Response(200, ['Content-type' => 'application/json'], $validResponse),
        ];
        $mockHandler = new MockHandler($mockRequests);
        $handler = HandlerStack::create($mockHandler);
        $httpClient = new GuzzleClient(['handler' => $handler]);

        $request = new Request('GET', 'http://localhost/', array('Accept' => 'application/json'));

        $client = new Client($httpClient, $request);

        $locations = $client->getLocations();
        $this->assertCount(2, $locations);
        $this->assertInstanceOf('AlexeyKuperhstokh\LocationBundle\Location\Location', $locations[0]);
        $this->assertInstanceOf('AlexeyKuperhstokh\LocationBundle\Location\Location', $locations[1]);
    }

    public function testCurlError()
    {
        $mockRequests = [
            new Response(404),
        ];
        $mockHandler = new MockHandler($mockRequests);
        $handler = HandlerStack::create($mockHandler);
        $httpClient = new GuzzleClient(['handler' => $handler]);

        $request = new Request('GET', 'http://localhost/', array('Accept' => 'application/json'));

        $client = new Client($httpClient, $request);

        $this->setExpectedException('GuzzleHttp\Exception\ClientException');

        $client->getLocations();
    }

    public function testMalformedJsonError()
    {
        $invalidResponse = <<<JSON
{{{{{{{
JSON;

        $mockRequests = [
            new Response(200, ['Content-type' => 'application/json'], $invalidResponse),
        ];
        $mockHandler = new MockHandler($mockRequests);
        $handler = HandlerStack::create($mockHandler);
        $httpClient = new GuzzleClient(['handler' => $handler]);

        $request = new Request('GET', 'http://localhost/', array('Accept' => 'application/json'));

        $client = new Client($httpClient, $request);

        $this->setExpectedException('Symfony\Component\Serializer\Exception\UnexpectedValueException');

        $client->getLocations();
    }

    public function testMalformedDataError1()
    {
        $validResponse = <<<JSON
{
    "data": {
        "locations": []
    }
}
JSON;

        $mockRequests = [
            new Response(200, ['Content-type' => 'application/json'], $validResponse),
        ];
        $mockHandler = new MockHandler($mockRequests);
        $handler = HandlerStack::create($mockHandler);
        $httpClient = new GuzzleClient(['handler' => $handler]);

        $request = new Request('GET', 'http://localhost/', array('Accept' => 'application/json'));

        $client = new Client($httpClient, $request);

        $this->setExpectedException('AlexeyKuperhstokh\LocationBundle\Exceptions\MalformedDataException');

        $client->getLocations();
    }

    public function testMalformedDataError2()
    {
        $validResponse = <<<JSON
{
    "data": {
        "locations": 1
    },
    "success": true
}
JSON;

        $mockRequests = [
            new Response(200, ['Content-type' => 'application/json'], $validResponse),
        ];
        $mockHandler = new MockHandler($mockRequests);
        $handler = HandlerStack::create($mockHandler);
        $httpClient = new GuzzleClient(['handler' => $handler]);

        $request = new Request('GET', 'http://localhost/', array('Accept' => 'application/json'));

        $client = new Client($httpClient, $request);

        $this->setExpectedException('AlexeyKuperhstokh\LocationBundle\Exceptions\MalformedDataException');

        $client->getLocations();
    }

    public function testServerErrorException()
    {
        $validResponse = <<<JSON
{
    "data": {
        "message": "testMessage",
        "code": 100
    },
    "success": false
}
JSON;

        $mockRequests = [
            new Response(200, ['Content-type' => 'application/json'], $validResponse),
        ];
        $mockHandler = new MockHandler($mockRequests);
        $handler = HandlerStack::create($mockHandler);
        $httpClient = new GuzzleClient(['handler' => $handler]);

        $request = new Request('GET', 'http://localhost/', array('Accept' => 'application/json'));

        $client = new Client($httpClient, $request);

        $this->setExpectedException(
            'AlexeyKuperhstokh\LocationBundle\Exceptions\ServerErrorException',
            'testMessage',
            100
        );

        $client->getLocations();
    }
}
