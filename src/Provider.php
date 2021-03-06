<?php
/**
 * Official OAuth2 Client Provider for Preview Technologies
 *
 * @author Shaharia Azam <shaharia@previewtechs.com>
 * @url https://www.previewtechs.com
 */

namespace Previewtechs\Oauth2\Client;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Provider
 * @package Previewtechs\Oauth2\Client
 */
class Provider extends AbstractProvider
{

    use BearerAuthorizationTrait;

    /**
     * @var bool
     */
    public $testMode = false;
    /**
     * @var array
     */
    public $scopes = ['basic', 'email'];

    /**
     * @var
     */
    public $authorizeEndpoint;
    /**
     * @var
     */
    public $accessTokenEndpoint;
    /**
     * @var
     */
    public $resourceOwnerEndpoint;

    /**
     * @var array
     */
    public $defaultScopes = [];

    /**
     * @var string
     */
    public $oauthHost = "https://oauth.previewtechs.com";

    /**
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->authorizeEndpoint = $this->oauthHost . "/ac/v1/authorize";
    }

    /**
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->accessTokenEndpoint = $this->oauthHost . "/ac/v1/access_token";
    }

    /**
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->resourceOwnerEndpoint = $this->oauthHost . "/api/v1/userinfo";
    }

    /**
     * @return array
     */
    protected function getDefaultScopes()
    {
        return $this->defaultScopes;
    }

    /**
     * @param ResponseInterface $response
     * @param array|string $data
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode > 400) {
            throw new IdentityProviderException(
                $data['message'] ?: $response->getReasonPhrase(),
                $statusCode,
                $response
            );
        }
    }

    /**
     * @param array $response
     * @param AccessToken $token
     * @return ResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ResourceOwner($response);
    }
}
