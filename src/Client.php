<?php

namespace Arkade\Emarsys;

use GuzzleHttp;
use Psr\Http\Message\RequestInterface;

class Client
{
    use Modules\BuildsModules;

    /**
     * Emarsys endpoint uri.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Emarsys client id
     *
     * @var string
     */
    protected $clientId;

    /**
     * Emarsys client secret.
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * Guzzle client for HTTP transport.
     *
     * @var GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * Client constructor.
     *
     * @param string $endpoint
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct($endpoint, $clientId, $clientSecret)
    {
        $this->endpoint = $endpoint;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->setupClient();
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Pass unknown methods off to the underlying Guzzle client.
     *
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->client, $name], $arguments);
    }

    /**
     * Setup Guzzle client with optional provided handler stack.
     *
     * @param  GuzzleHttp\HandlerStack|null $stack
     * @param  array                        $options
     * @return Client
     */
    public function setupClient(GuzzleHttp\HandlerStack $stack = null, array $options = [])
    {
        $stack = $stack ?: GuzzleHttp\HandlerStack::create();

        $this->bindHeadersMiddleware($stack);

        $this->client = new GuzzleHttp\Client(array_merge([
            'handler'  => $stack,
            'base_uri' => (string) $this->getEndpoint()
        ], $options));

        return $this;
    }

    /**
     * Bind outgoing request middleware for headers.
     *
     * @param  GuzzleHttp\HandlerStack $stack
     * @return void
     */
    protected function bindHeadersMiddleware(GuzzleHttp\HandlerStack $stack)
    {
        $nonce = md5(rand());
        $date = gmdate('c');
        $digest = base64_encode(sha1($nonce . $date . $this->clientSecret));
        $token = 'UsernameToken Username="' . $this->clientId . '", PasswordDigest="' . $digest . '", Nonce="' . $nonce . '", Created="' . $date . '"';
        $stack->push(GuzzleHttp\Middleware::mapRequest(function (RequestInterface $request) use ($token) {
            return $request
                ->withHeader('X-WSSE', 'UsernameToken Username=' . $token);
        }));
    }
}
