<?php namespace Models\Brokers;

use stdClass;
use Zephyrus\Security\Cryptography;

class UserBroker extends Broker
{
    public function authenticate(string $username, string $password): ?stdClass
    {
        $sql = 'SELECT * 
                  FROM "user" 
                  JOIN authentication ON "user".user_id = authentication.user_id 
                 WHERE username = ?';
        $user = $this->selectSingle($sql, [$username]);
        if (is_null($user)) {
            return null;
        }
        if (!Cryptography::verifyHashedPassword($password, $user->password)) {
            return null;
        }
        return $user;
    }
}
