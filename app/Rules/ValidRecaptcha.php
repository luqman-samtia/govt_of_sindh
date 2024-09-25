<?php

namespace App\Rules;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Validation\Rule;

class ValidRecaptcha implements Rule
{
    /**
     * @param string
     * @param  mixed  $value
     *
     * @throws GuzzleException
     */
    public function passes($attribute, $value): bool
    {
        // Validate ReCaptcha
        $client = new Client([
            'base_uri' => 'https://google.com/recaptcha/api/',
        ]);

        $response = $client->post('siteverify', [
            'query' => [
                'secret' => config('app.recaptcha.secret'),
                'response' => $value,
            ],
        ]);

        return json_decode($response->getBody())->success;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'ReCaptcha verification failed.';
    }
}
