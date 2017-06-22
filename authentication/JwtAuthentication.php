<?php
namespace base\authentication;

use base\App;
use base\contracts\AuthenticationInterface;
use Interop\Container\ContainerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Adapter for JWT Auth
 * @package base\authentication
 * @author  Irvan Setiawan <peang.cookie@gmail.com>
 */
class JwtAuthentication implements AuthenticationInterface
{
    /** @var string $secret */
    private static $secret;

    /**
     * JwtAuthentication constructor.
     */
    public function __construct($secret)
    {
        /** @var ContainerInterface $ci */
        $ci = App::$app->getContainer();

        if (empty($secret)) {
            self::$secret = $ci['params_local']['jwt_secret'];
        } else {
            self::$secret = $secret;
        }
    }

    /**
     * @return string
     */
    public static function generateToken()
    {
        $signer  = new Sha256();
        $builder = new Builder();

        $token = $builder->setIssuer('slim-dev')            // Configures the issuer (iss claim)
            ->setAudience('')                               // Configures the audience (aud claim)
            ->setId('4f1g23a12aa', true)                    // Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time())                           // Configures the time that the token was issue (iat claim)
            ->setNotBefore(time() + 60)                     // Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 3600)                  // Configures the expiration time of the token (exp claim)
            ->set('user_id', 1)                             // Configures a new claim, called "uid"
            ->set('scope', ['read','write','delete'])       // add scopes
            ->sign($signer, self::$secret)                  // creates a signature key
            ->getToken();                                   // Retrieves the generated token

        return (string) $token;
    }

    public function validate()
    {
        // TODO: Implement validateToken() method.
    }
}