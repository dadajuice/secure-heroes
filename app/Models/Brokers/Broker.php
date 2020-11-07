<?php namespace Models\Brokers;

use Zephyrus\Database\Core\Database;
use Zephyrus\Database\DatabaseBroker;
use Zephyrus\Security\Cryptography;

/**
 * Zephyrus enforces that the way to communicate with your database should use broker instances. This class acts as a
 * middleware, all the other "brokers" should extends this class and thus, you can add project specific processing to
 * this class that every other brokers shall inherit.
 *
 * Class Broker
 * @package Models\Brokers
 */
abstract class Broker extends DatabaseBroker
{
    public function __construct(?Database $database = null)
    {
        parent::__construct($database);
        $this->addSessionVariable('heroes.encryption_key', 'hubert');

        //Cryptography::deriveEncryptionKey('Omega123', 'validator');

        //$this->addSessionVariable('heroes.authentication_key', 'autrechose');
    }
}
