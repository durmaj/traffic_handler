<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Payment;

class TrafficSplit
{
    private array $gateways;

    public function __construct(array $gateways) {
        $this->gateways = $gateways;
    }

    public function handlePayment(Payment $payment): bool
    {
        if (false === $this->checkIfGatewaysWeightsAreCorrect()) {
            throw new \RuntimeException('Gateways weights are not summing up to 100%');
        }

        $gateways = $this->sortGatewaysByWeight($this->gateways);

        foreach ($gateways as $gateway) {
            $gatewayTrafficLoad = $gateway->getTrafficLoad();

            if ($gatewayTrafficLoad < $gateway->getWeight()) {
                $isSuccessful = $gateway->processPayment($payment);

                if ($isSuccessful) {
                    return true;
                }
            }
        }

        return false;
    }

    private function checkIfGatewaysWeightsAreCorrect(): bool
    {
        $totalWeight = 0;
        foreach ($this->gateways as $gateway) {
            $totalWeight += $gateway->getWeight();
        }
        
        if ($totalWeight != 100) {
            return false;
        }

        return true;
    }


    private function sortGatewaysByWeight(array $gateways): array
    {
         usort($gateways, function($a, $b) {
            return $a->getWeight() <=> $b->getWeight();
         });

         return $gateways;
    }
}
