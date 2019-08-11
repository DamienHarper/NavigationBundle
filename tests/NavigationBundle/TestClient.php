<?php

namespace DH\NavigationBundle\Tests;

use DH\NavigationBundle\Provider\ProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Serve responses from local file cache.
 */
class TestClient implements ClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ProviderInterface
     */
    private $provider;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param ProviderInterface $provider
     *
     * @return TestClient
     */
    public function setProvider(ProviderInterface $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array            $options request options to apply to the given
     *                                  request and to the transfer
     *
     * @throws GuzzleException
     * @throws \Exception
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, array $options = [])
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * Asynchronously send an HTTP request.
     *
     * @param RequestInterface $request Request to send
     * @param array            $options request options to apply to the given
     *                                  request and to the transfer
     *
     * @throws \Exception
     *
     * @return PromiseInterface
     */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * Create and send an HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well.
     *
     * @param string              $method  HTTP method
     * @param string|UriInterface $uri     URI object or string
     * @param array               $options request options to apply
     *
     * @throws GuzzleException
     * @throws \Exception
     *
     * @return ResponseInterface
     */
    public function request($method, $uri, array $options = [])
    {
        $cacheKey = $uri;
        $host = parse_url($uri, PHP_URL_HOST);

        foreach ($this->provider->getCredentials() as $key => $value) {
            $cacheKey = str_replace($value, '['.$key.']', $cacheKey);
        }

        $file = sprintf('%s/%s_%s', __DIR__.'/Fixtures/cached_responses', $host, sha1($cacheKey));
        if (is_file($file) && is_readable($file)) {
            return new Response(200, [], unserialize(file_get_contents($file)));
        }

        $response = $this->client->request($method, $uri, $options);
        file_put_contents($file, serialize($response->getBody()->getContents()));
        $response->getBody()->rewind();

        return $response;
    }

    /**
     * Create and send an asynchronous HTTP request.
     *
     * Use an absolute path to override the base path of the client, or a
     * relative path to append to the base path of the client. The URL can
     * contain the query string as well. Use an array to provide a URL
     * template and additional variables to use in the URL template expansion.
     *
     * @param string              $method  HTTP method
     * @param string|UriInterface $uri     URI object or string
     * @param array               $options request options to apply
     *
     * @throws \Exception
     *
     * @return PromiseInterface
     */
    public function requestAsync($method, $uri, array $options = [])
    {
        throw new \Exception('Not implemented.');
    }

    /**
     * Get a client configuration option.
     *
     * These options include default request options of the client, a "handler"
     * (if utilized by the concrete client), and a "base_uri" if utilized by
     * the concrete client.
     *
     * @param null|string $option the config option to retrieve
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function getConfig($option = null)
    {
        throw new \Exception('Not implemented.');
    }
}
