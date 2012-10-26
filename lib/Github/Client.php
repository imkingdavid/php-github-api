<?php

namespace Github;

use Github\Api\Api;
use Github\Api\ApiInterface;
use Github\Api\Commit;
use Github\Api\Issue;
use Github\Api\Object;
use Github\Api\Organization;
use Github\Api\PullRequest;
use Github\Api\Repo;
use Github\Api\User;
use Github\HttpClient\HttpClientInterface;
use Github\HttpClient\Curl;

/**
 * Simple yet very cool PHP Github client
 *
 * @tutorial  http://github.com/imkingdavid/php-github-api/blob/master/README.markdown
 * @version   4.0
 * @author    David King <imkingdavid@gmail.com>
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 *
 * Website: http://github.com/imkingdavid/php-github-api
 * Tickets: http://github.com/imkingdavid/php-github-api/issues
 */
class Client
{
    /**
     * Constant for authentication method. Indicates the default, but deprecated
     * login with username and token in URL.
     */
    const AUTH_URL_TOKEN = 'url_token';

    /**
     * Constant for authentication method. Indicates the new favored login method
     * with username and password via HTTP Authentication.
     */
    const AUTH_HTTP_PASSWORD = 'http_password';

    /**
     * Constant for authentication method. Indicates the new login method with
     * with username and token via HTTP Authentication.
     */
    const AUTH_HTTP_TOKEN = 'http_token';

    /**
     * The httpClient instance used to communicate with GitHub
     *
     * @var HttpClientInterface
     */
    protected $httpClient = null;

    /**
     * The list of loaded API instances
     *
     * @var array
     */
    protected $apis = array();

    /**
     * Instanciate a new GitHub client
     *
     * @param  HttpClientInterface $httpClient custom http client
     */
    public function __construct(HttpClientInterface $httpClient = null)
    {
        if (null === $httpClient) {
            $this->httpClient = new Curl();
        } else {
            $this->httpClient = $httpClient;
        }
    }

    /**
     * Authenticate a user for all next requests
     *
     * @param  string         $login      GitHub username
     * @param  string         $secret     GitHub private token or Github password if $method == AUTH_HTTP_PASSWORD
     * @param  string         $method     One of the AUTH_* class constants
     *
     * @return null
     */
    public function authenticate($login, $secret, $method = null)
    {
        if (!$method) {
            $method = self::AUTH_URL_TOKEN;
        }

        $this->getHttpClient()
            ->setOption('auth_method', $method)
            ->setOption('login', $login)
            ->setOption('secret', $secret);
    }

    /**
     * Deauthenticate a user for all next requests
     *
     * @return null
     */
    public function deAuthenticate()
    {
        $this->authenticate(null, null, null);
    }

    /**
     * Call any path, GET method
     * Ex: $api->get('repos/show/my-username/my-repo')
     *
     * @param   string  $path            the GitHub path
     * @param   array   $parameters       GET parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    public function get($path, array $parameters = array(), $requestOptions = array())
    {
        return $this->getHttpClient()->get($path, $parameters, $requestOptions);
    }

    /**
     * Call any path, POST method
     * Ex: $api->post('repos/show/my-username', ['email' => 'my-new-email@provider.org'])
     *
     * @param   string  $path            the GitHub path
     * @param   array   $parameters       POST parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    public function post($path, array $parameters = array(), $requestOptions = array())
    {
        return $this->getHttpClient()->post($path, $parameters, $requestOptions);
    }

    /**
     * Call any path, PATCH method
     * Ex: $api->get('repos/show/my-username/my-repo')
     *
     * @param   string  $path            the GitHub path
     * @param   array   $parameters       PATCH parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    public function update($path, array $parameters = array(), $requestOptions = array())
    {
        return $this->getHttpClient()->update($path, $parameters, $requestOptions);
    }

    /**
     * Call any path, DELETE method
     * Ex: $api->post('repos/show/my-username', ['email' => 'my-new-email@provider.org'])
     *
     * @param   string  $path            the GitHub path
     * @param   array   $parameters       DELETE parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    public function delete($path, array $parameters = array(), $requestOptions = array())
    {
        return $this->getHttpClient()->delete($path, $parameters, $requestOptions);
    }

    /**
     * Get the http client.
     *
     * @return  HttpClientInterface   a request instance
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Inject another http client
     *
     * @param   Github_HttpClient_Interface   a httpClient instance
     *
     * @return  null
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get the user API
     *
     * @param   bool    $newInstance  Whether or not to create a new instance
     * @return  User    the user API
     */
    public function getUserApi($newInstance = false)
    {
        if (!isset($this->apis['user']) || $newInstance !== false) {
            $this->apis['user'] = new User($this);
        }

        return $this->apis['user'];
    }

    /**
     * Get the issue API
     *
     * @param   bool    $newInstance  Whether or not to create a new instance
     * @return  Issue   the issue API
     */
    public function getIssueApi($newInstance = false)
    {
        if (!isset($this->apis['issue']) || $newInstance !== false) {
            $this->apis['issue'] = new Issue($this);
        }

        return $this->apis['issue'];
    }

    /**
     * Get the commit API
     *
     * @param   bool    $newInstance   Whether or not to create a new instance
     * @return  Commit  the commit API
     */
    public function getCommitApi($newInstance = false)
    {
        if (!isset($this->apis['commit']) || $newInstance !== false) {
            $this->apis['commit'] = new Commit($this);
        }

        return $this->apis['commit'];
    }

    /**
     * Get the repo API
     *
     * @param   bool  $newInstance    Whether or not to create a new instance
     * @return  Repo  the repo API
     */
    public function getRepoApi($newInstance = false)
    {
        if (!isset($this->apis['repo']) || $newInstance !== false) {
            $this->apis['repo'] = new Repo($this);
        }

        return $this->apis['repo'];
    }

    /**
     * Get the organization API
     *
     * @param   bool    $newInstance   Whether or not to create a new instance
     * @return  Organization  the object API
     */
    public function getOrganizationApi($newInstance = false)
    {
        if (!isset($this->apis['organization']) || $newInstance !== false) {
            $this->apis['organization'] = new Organization($this);
        }

        return $this->apis['organization'];
    }

    /**
     * Get the object API
     *
     * @param   bool    $newInstance   Whether or not to create a new instance
     * @return  Object  the object API
     */
    public function getObjectApi($newInstance = false)
    {
        if (!isset($this->apis['object']) || $newInstance !== false) {
            $this->apis['object'] = new Object($this);
        }

        return $this->apis['object'];
    }

    /**
     * Get the pull request API
     *
     * @param   bool   $newInstance   Whether or not to create a new instance
     * @return  PullRequest  the pull request API
     */
    public function getPullRequestApi($newInstance = false)
    {
        if (!isset($this->apis['pullrequest']) || $newInstance !== false) {
            $this->apis['pullrequest'] = new PullRequest($this);
        }

        return $this->apis['pullrequest'];
    }

    /**
     * Inject an API instance
     *
     * @param   string            $name the API name
     * @param   ApiInterface      $api  the API instance
     *
     * @return  null
     */
    public function setApi($name, ApiInterface $instance)
    {
        $this->apis[$name] = $instance;

        return $this;
    }

    /**
     * Get any API
     *
     * @param   string           $name the API name
     * @return  ApiInterface     the API instance
     */
    public function getApi($name)
    {
        return $this->apis[$name];
    }
}
