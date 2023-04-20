<?php
declare(strict_types=1);

namespace App\Entity;

class Gateway implements GatewayInterface
{
    private string $id;
    private int $weight;
    private int $trafficLoad;
    
    public function __construct($id, $weight) {
        $this->id = $id;
        $this->weight = $weight;
        $this->trafficLoad = 0;
    }
    
    public function processPayment(Payment $payment): bool
    {
        $this->trafficLoad++;

        return true;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getTrafficLoad(): int
    {
        return $this->trafficLoad;
    }

}
