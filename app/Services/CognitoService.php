<?php

namespace App\Services;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

class CognitoService
{
    protected $client;

    public function __construct()
    {
        $this->client = new CognitoIdentityProviderClient([
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function authenticate($username, $password)
    {
        $client = new \Aws\CognitoIdentityProvider\CognitoIdentityProviderClient([
            'region' => 'ap-northeast-1',
            'version' => 'latest',
        ]);
    
        $response = $client->adminInitiateAuth([
            'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
            'ClientId' => env('AWS_COGNITO_CLIENT_ID'),
            'UserPoolId' => env('AWS_COGNITO_USER_POOL_ID'),
            'AuthParameters' => [
                'USERNAME' => $username,
                'PASSWORD' => $password,
            ],
        ]);
    
        // Check if 'AuthenticationResult' is present in the response
        if (isset($response['AuthenticationResult']['AccessToken'])) {
            return $response['AuthenticationResult']['AccessToken'];
        }
    
        return null; // Return null if no token is found
    }
    
}
