<?php

namespace Github\Api;

/**
 * Interface for Api classes
 *
 * @author    David King <imkingdavid@gmail.com>
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 */
interface ApiTypeInterface
{
    /**
     * Call any path, GET method
     * Ex: $api->get('repos/show/my-username/my-repo')
     *
     * @param   string  $path             the GitHub path
     * @param   array   $parameters       GET parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    protected function get($path, array $parameters = array(), $requestOptions = array());

    /**
     * Call any path, POST method
     * Ex: $api->post('repos/show/my-username', array('email' => 'my-new-email@provider.org'))
     *
     * @param   string  $path             the GitHub path
     * @param   array   $parameters       POST parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    protected function post($path, array $parameters = array(), $requestOptions = array());

    /**
     * Call any path, PATCH method
     * Ex: $api->post('repos/show/my-username', array('email' => 'my-new-email@provider.org'))
     *
     * @param   string  $path             the GitHub path
     * @param   array   $parameters       PATCH parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    protected function update($path, array $parameters = array(), $requestOptions = array());

    /**
     * Call any path, DELETE method
     * Ex: $api->post('repos/show/my-username', array('email' => 'my-new-email@provider.org'))
     *
     * @param   string  $path             the GitHub path
     * @param   array   $parameters       DELETE parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    protected function delete($path, array $parameters = array(), $requestOptions = array());
}
