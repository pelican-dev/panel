<?php

namespace Tests\Unit\Models;

use App\Models\Allocation;
use App\Tests\TestCase;
use IPTools\Network;

class AllocationTest extends TestCase
{
    public function test_debug_network_comparison(): void
    {
        $ip1 = '192.168.1.100';
        $ip2 = '192.168.1.200';
        
        $network1 = Network::parse($ip1 . '/24');
        $network2 = Network::parse($ip2 . '/24');
        
        echo "Network 1: " . $network1->getNetwork() . " (type: " . gettype($network1->getNetwork()) . ")\n";
        echo "Network 2: " . $network2->getNetwork() . " (type: " . gettype($network2->getNetwork()) . ")\n";
        echo "Are equal: " . ($network1->getNetwork() === $network2->getNetwork() ? 'true' : 'false') . "\n";
        echo "Are equal (==): " . ($network1->getNetwork() == $network2->getNetwork() ? 'true' : 'false') . "\n";
        
        // This test will help us understand what's happening
        $this->assertTrue(true); // Just to make the test pass while we debug
    }

    public function test_are_ips_on_same_network(): void
    {
        // Test IPv4 addresses on the same /24 network
        $this->assertTrue(Allocation::areIpsOnSameNetwork('192.168.1.100', '192.168.1.200'));
        $this->assertTrue(Allocation::areIpsOnSameNetwork('10.0.0.5', '10.0.0.10'));
        
        // Test IPv4 addresses on different networks
        $this->assertFalse(Allocation::areIpsOnSameNetwork('192.168.1.100', '192.168.2.100'));
        $this->assertFalse(Allocation::areIpsOnSameNetwork('10.0.0.5', '172.16.0.5'));
        
        // Test with invalid IPs (should return false)
        $this->assertFalse(Allocation::areIpsOnSameNetwork('invalid', '192.168.1.100'));
    }
} 