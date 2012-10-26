<?php

namespace Github\HttpClient;

/**
 * Performs requests on GitHub API. API documentation should be self-explanatory.
 *
 * @author    David King <imkingdavid@gmail.com>
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 */
abstract class HttpClient implements HttpClientInterface
{
    /**
     * The http client options
     * @var array
     */
    protected $options = [];

    protected static $history = [];

    /**
     * Instanciate a new http client
     *
     * @param  array   $options  http client options
     */
    public function __construct(array $options = array())
    {
        // The values below are defaults that get overridden by the values set
        // by the $options parameter
        $this->options = array_merge([
            'protocol'   => 'https',
            'url'        => ':protocol://github.com/api/v2/:format/:path',
            'format'     => 'json',
            'user_agent' => 'php-github-api (http://github.com/ornicar/php-github-api)',
            'http_port'  => 443,
            'timeout'    => 10,
            'login'      => null,
            'token'      => null,
        ], $options);
    }

    /**
     * @inheritdoc
     */
    abstract protected function doRequest($url, array $parameters = array(), $httpMethod = 'GET', array $options = array());

    /**
     * @inheritdoc
     */
    public function get($path, array $parameters = array(), array $options = array())
    {
        return $this->request($path, $parameters, 'GET', $options);
    }

    /**
     * @inheritdoc
     */
    public function post($path, array $parameters = array(), array $options = array())
    {
        return $this->request($path, $parameters, 'POST', $options);
    }

    /**
     * @inheritdoc
     */
    public function update($path, array $parameters = array(), array $options = array())
    {
        return $this->request($path, $parameters, 'PATCH', $options);
    }

    /**
     * @inheritdoc
     */
    public function delete($path, array $parameters = array(), array $options = array())
    {
        return $this->request($path, $parameters, 'DELETE', $options);
    }

    /**
     * @inheritdoc
     */
    public function request($path, array $parameters = array(), $httpMethod = 'GET', array $options = array())
    {
        $this->updateHistory();

        $options = array_merge($this->options, $options);

        // create full url
        $url = strtr($options['url'], [
            ':protocol' => $options['protocol'],
            ':format'   => $options['format'],
            ':path'     => trim($path, '/')
        ]);

        // get encoded response
        $response = $this->doRequest($url, $parameters, $httpMethod, $options);

        // decode response
        $response = $this->decodeResponse($response, $options);

        return $response;
    }

    /**
     * Get a JSON response and transform it to a PHP array
     *
     * @return  array   the response
     */
    protected function decodeResponse($response, array $options)
    {
        if ($options['format'] === 'text') {
            return $response;
        } elseif ($options['format'] === 'json') {
            return json_decode($response, true);
        }

        throw new \Exception(__CLASS__ . ' only supports json & text format, ' . $options['format'] . ' given.');
    }

    /**
     * @inheritdoc
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Records the requests times
     * When 30 request have been sent in less than a minute,
     * sleeps for two second to prevent reaching GitHub API limitation.
     *
     * @access protected
     * @return void
     */
    protected function updateHistory()
    {
        self::$history[] = time();

        if (count(self::$history) === 30) {
            if (reset(self::$history) >= (time() - 35)) {
                sleep(2);
            }

            array_shift(self::$history);
        }
    }
}
