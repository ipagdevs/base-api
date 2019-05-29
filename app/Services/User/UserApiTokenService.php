<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserApiToken;
use Ramsey\Uuid\Uuid;

class UserApiTokenService
{
    /**
     * Generate the Api_token For an User
     * @param User $user
     *
     * @return string
     */
    public function generate(User $user)
    {
        $salt = $this->salt();
        $token = Uuid::uuid4();

        $apiToken = $userSignature = $user->apiTokens()->create([
            'api_id'    => md5(hash('sha256', $user->password, true)),
            'api_token' => $token,
            'salt'      => $salt,
            'signature' => $this->signature($token, $salt),
        ]);

        return $apiToken;
    }

    /**
     * Generate a Random Salt
     *
     * @return string
     */
    private function salt()
    {
        return base64_encode(openssl_random_pseudo_bytes(16));
    }

    /**
     * Generate the Signature
     * @param string $text s
     * @param string $salt
     *
     * @return string
     */
    private function signature($text, $salt)
    {
        return base64_encode(hash('sha256', $text.$salt, true));
    }

    /**
     * Validate if the password has not beed modified
     * @param UserApiToken $apiToken
     * @param string $password
     *
     * @return bool
     */
    public function validate(UserApiToken $apiToken, $password)
    {
        return (bool) ($apiToken->signature === $this->signature($password, $apiToken->salt));
    }
}
