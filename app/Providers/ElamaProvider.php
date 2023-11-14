<?php

namespace Laravel\Socialite\Two;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;

class ElamaProvider extends AbstractProvider
{
    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'openid',
        'profile',
        'email',
    ];

    public function getElamaUrl()
    {
        return config('services.elama');
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        
        //grant_type=authorization_code
        return 'https://new.elama.ru/api/oauth-provider/init?client_id=61f2b657-a8b1-4693-923d-3056aec44d6a&redirect_uri=https://dailysender.ru/auth/elama/callback&scope=login&response_type=code&state=YKu7PrbP2SZ90jqRo4gUP7PtkZcplEfNaaWvrN7c';
        //return $this->buildAuthUrlFromBase('https://new.elama.ru/api/oauth-provider/init', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        
        return 'https://new.elama.ru/api/oauth-provider/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {

        
        /**$response = $this->getHttpClient()->post('https://new.elama.ru/api/oauth-provider/token', [
            'headers' => [
                'cache-control' => 'no-cache',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);*/
        
        //print_r($token);
        /*
        return Http::dd()->post('https://new.elama.ru/api/oauth-provider/token', [
            'grant_type' => 'refresh_token',
            'client_id' => '61f2b657-a8b1-4693-923d-3056aec44d6a',
            'client_secret' => '1535029e607db8765885f1d22ced62bf3868c6a3bda2c30ce9',
            'refresh_token' => $token,
        ]);
        
        $response = Http::asForm()->post('https://new.elama.ru/api/oauth-provider/token', [
          
            'grant_type' => 'refresh_token',
            'client_id' => '61f2b657-a8b1-4693-923d-3056aec44d6a',
            'client_secret' => '1535029e607db8765885f1d22ced62bf3868c6a3bda2c30ce9',
            'refresh_token' => $token,
        
        ]);
*/
        
        $array = array(
            'grant_type' => 'refresh_token',
            'client_id' => '61f2b657-a8b1-4693-923d-3056aec44d6a',
            'client_secret' => '1535029e607db8765885f1d22ced62bf3868c6a3bda2c30ce9',
            'refresh_token' => $token,
        );	

        $ch = curl_init('https://new.elama.ru/api/oauth-provider/token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded')); 	
        $response = curl_exec($ch);
        curl_close($ch);	

        $decode = json_decode($response, true);
        
        
        $array_1 = array(
            'Authorization' => $decode['access_token']
        );	
        
        $ch = curl_init('https://new.elama.ru/api/oauth-provider/user-info');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:' . $decode['access_token']));
        $response_1 = curl_exec($ch);
        curl_close($ch);	

        return json_decode($response_1, true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['sub'],
            'email' => $user['email'],
            
        ]);
    }
}
