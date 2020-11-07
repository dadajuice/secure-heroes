<?php namespace Models\Brokers;

class HeroBroker extends Broker
{
    public function findAll(): array
    {
        return $this->select("SELECT decrypt(real_name) as real_name, alias FROM hero ORDER BY alias");
    }
}
