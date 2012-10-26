<?php

namespace Github\Api;

/**
 * Abstract class for Api classes
 *
 * @author    David King <imkingdavid@gmail.com>
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 */
abstract class ApiType implements ApiTypeInterface
{
    /**
     * The client
     * @var Github_Client
     */
    private $client;

    /**
     * Constructor
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    protected function get($path, array $parameters = array(), $requestOptions = array())
    {
        return $this->client->get($path, $parameters, $requestOptions);
    }

    /**
     * @inheritdoc
     */
    protected function post($path, array $parameters = array(), $requestOptions = array())
    {
        return $this->client->post($path, $parameters, $requestOptions);
    }

    /**
     * @inheritdoc
     */
    protected function update($path, array $parameters = array(), $requestOptions = array())
    {
        return $this->client->update($path, $parameters, $requestOptions);
    }

    /**
     * @inheritdoc
     */
    protected function delete($path, array $parameters = array(), $requestOptions = array())
    {
        return $this->client->delete($path, $parameters, $requestOptions);
    }
}
