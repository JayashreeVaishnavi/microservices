<?php


namespace Tests;


use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class BasicTestCase extends TestCase
{
    protected $user, $token;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function setUpHeaders($token = null)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ];
        if ($token) {
            $headers = array_merge($headers, ['Authorization' => 'Bearer ' . $token]);
        }
        return $headers;
    }
}