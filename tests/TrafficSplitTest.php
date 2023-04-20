<?php
declare(strict_types=1);

namespace App\Tests;

use App\Entity\Gateway;
use App\Entity\Payment;
use App\Service\TrafficSplit;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class TrafficSplitTest extends TestCase
{
    public function testTrafficSplitFailWithWrongWeights()
    {
        $this->expectException(RuntimeException::class);

        $gateways = [
            new Gateway('Gateway1', 50),
            new Gateway('Gateway2', 50),
            new Gateway('Gateway3', 15),
            new Gateway('Gateway4', 10)
        ];

        $trafficSplit = new TrafficSplit($gateways);

        $trafficSplit->handlePayment(new Payment());
    }
    public function testTrafficSplitWithEqualLoad()
    {
        $gateways = [
            new Gateway('Gateway1', 25),
            new Gateway('Gateway2', 25),
            new Gateway('Gateway3', 25),
            new Gateway('Gateway4', 25)
        ];

        $trafficSplit = new TrafficSplit($gateways);

        $payments = array_fill(0, 100, new Payment());
        foreach ($payments as $payment) {
            $trafficSplit->handlePayment($payment);
        }

        foreach ($gateways as $gateway) {
            $trafficLoadPercentage = ($gateway->getTrafficLoad() / 100) * 100;
            $this->assertEquals(25, $trafficLoadPercentage);
        }
    }

    public function testTrafficSplitWithVariousLoad()
    {
        $gateways = [
            new Gateway('Gateway1', 75),
            new Gateway('Gateway2', 10),
            new Gateway('Gateway3', 15)
        ];

        $trafficSplit = new TrafficSplit($gateways);

        $payments = array_fill(0, 100, new Payment());
        foreach ($payments as $payment) {
            $trafficSplit->handlePayment($payment);
        }

        $this->assertEquals(75, ($gateways[0]->getTrafficLoad() / 100) * 100);
        $this->assertEquals(10, ($gateways[1]->getTrafficLoad() / 100) * 100);
        $this->assertEquals(15, ($gateways[2]->getTrafficLoad() / 100) * 100);
    }
}
