<?php

namespace AlexeyKuperhstokh\LocationBundle\Client;

use AlexeyKuperhstokh\LocationBundle\Exceptions\MalformedDataException;
use AlexeyKuperhstokh\LocationBundle\Exceptions\ServerErrorException;
use AlexeyKuperhstokh\LocationBundle\Location\Location;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class Client
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Client constructor.
     * @param ClientInterface $httpClient
     * @param RequestInterface $request
     */
    public function __construct(ClientInterface $httpClient, RequestInterface $request)
    {
        $this->httpClient = $httpClient;
        $this->request = $request;
    }

    /**
     * @return Location[]
     */
    public function getLocations()
    {
        $body = $this->sendRequest();
        $serializer = new Serializer([], [new JsonEncoder()]);
        $decoded = $serializer->decode($body, 'json');

        $this->validateResponse($decoded);

        $locations = array();
        foreach ($decoded['data']['locations'] as $jsonLocation) {
            $locations[] = new Location($jsonLocation);
        }

        return $locations;
    }

    /**
     * @param $decoded
     * @throws MalformedDataException
     * @throws ServerErrorException
     */
    public function validateResponse($decoded)
    {
        if (!isset($decoded['success'], $decoded['data']) || !is_array($decoded['data'])) {
            throw new MalformedDataException();
        }

        if (!$decoded['success']) {
            $message = isset($decoded['data']['message']) ? $decoded['data']['message'] : null;
            $code = isset($decoded['data']['code']) ? $decoded['data']['code'] : null;
            throw new ServerErrorException($message, $code);
        }

        if (!isset($decoded['data']['locations']) || !is_array($decoded['data']['locations'])) {
            throw new MalformedDataException();
        }
    }

    /**
     * @return string
     */
    public function sendRequest()
    {
        $response = $this->httpClient->send($this->request);
        return $response->getBody()->getContents();
    }
}
