<?php

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterAuth {

    protected $client;
    protected $clientCallback = "http://exercise.org";
    protected $app_id = "odGJtUtFaP6urmMDq6SzlsH6Q";
    protected $app_secret = "zNliHCE2b3tFjgWGP2jAbc6SW4KgUjmE2W6xHvuXR7MnusBGyl";

    public function __construct(TwitterOAuth $client)
    {
        $this->client = $client;
    }
    protected function generateAccessToken()
    { 
        if (!isset($_SESSION['twitter_auth'])) {
            return $this->client->oauth('oauth/request_token', ['oauth_callback' => $this->clientCallback]);
        }

        return false;
    }
    protected function storeToken()
    {

        if (!isset($_SESSION['twitter_auth'])) {
            //storing the token into the session.
              
            $accessToken = $this->generateAccessToken();

            $_SESSION['twitter_auth'] = "started";
            $_SESSION['oauth_token'] = $accessToken['oauth_token'];
            $_SESSION['oauth_token_secret'] = $accessToken['oauth_token'];

            return $accessToken;

        }

        return false;

    }
    public function getUrl()
    {
        $token = $this->storeToken();

        return $this->client->url('oauth/authorize', ['oauth_token' => $_SESSION['oauth_token'] ]);
    }
    protected function verifyToken()
    {
        $requestToken = [];
        $requestToken['oauth_token'] = $_SESSION['oauth_token'];
        $requestToken['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
        unset($_SESSION['twitter_auth']);

        if (isset($_REQUEST['oauth_token']) && $requestToken['oauth_token'] !== $_REQUEST['oauth_token']) {
            return false; // if token mismatch
        }
        return $requestToken; 
    }
    public function getPayload()
    {
        $requestToken = $this->verifyToken();
        if (!$this->verifyToken()) {
            return false;
        }

        $connection = new TwitterOAuth($this->app_id,$this->app_secret, $requestToken['oauth_token'], $requestToken['oauth_token_secret']);
     

        $accessToken = $connection->oauth('oauth/access_token', ['oauth_verifier' => $_REQUEST['oauth_verifier']]);
      
        $connection = new TwitterOAuth($this->app_id, $this->app_secret, $accessToken['oauth_token'], $accessToken['oauth_token_secret']);

        $payload = $connection->get('account/verify_credentials', ['include_email' => 'true']); 
        // don't forgot the qoutes over the true.
        
        return $payload;
    }
    public function setPayload($payload)
    {
        $_SESSION['TwitterPayload'] = $payload;
        return ;
    }
}