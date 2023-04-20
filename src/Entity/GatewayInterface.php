<?php
declare(strict_types=1);

namespace App\Entity;

interface GatewayInterface
{

    public function processPayment(Payment $payment): bool;
    public function getTrafficLoad(): int;

}
