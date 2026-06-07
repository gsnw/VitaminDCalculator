<?php
namespace Gsnw\VitaminDCalculator\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Gsnw\VitaminDCalculator\VitaminDCalculator;

class VitaminDCalculatorTest extends TestCase
{
    public function testMaintenanceDoseCalculation()
    {
      // 70kg * 30 IU = 2100 IU
      $this->assertEquals(2100, VitaminDCalculator::calculateMaintenanceDose(70.0));
    }

    public function testLoadingDoseCalculation()
    {
      $dose = VitaminDCalculator::calculateDailyDose(20.0, 30.0, 70.0, 30);
      $this->assertGreaterThan(0, $dose);
    }

    public function testInvalidInputThrowsException()
    {
      $this->expectException(\InvalidArgumentException::class);
      VitaminDCalculator::calculateDailyDose(0, 50, 70, 30);
    }

    public function testTargetTooHighThrowsException()
    {
      $this->expectException(\InvalidArgumentException::class);
      VitaminDCalculator::calculateDailyDose(20, 101, 70, 30);
    }
}