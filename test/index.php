<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Gsnw\VitaminDCalculator\VitaminDCalculator;

// Sample values
$currentLevel = 44.9; // ng/ml
$targetLevel = 55.0;  // ng/ml
$weight = 69.0;       // kg
$days = 30;           // Duration of the start-up phase in days

// Language German
echo "=== Deutsch ===\n";
echo VitaminDCalculator::getRecommendation($currentLevel, $targetLevel, $weight, 'de', $days);

// Language English
echo "\n=== English ===\n";
echo VitaminDCalculator::getRecommendation($currentLevel, $targetLevel, $weight, 'en', $days);

// Example of a direct calculation using the time period
$loadingDose = VitaminDCalculator::calculateDailyDose($currentLevel, $targetLevel, $weight, $days);
$maintenanceDose = VitaminDCalculator::calculateMaintenanceDose($weight);

echo "\n=== Direct calculation ===\n";
echo "Ladedosis (" . $days . " Tage): " . $loadingDose . " IE/Tag\n";
echo "Erhaltungsdosis: " . $maintenanceDose . " IE/Tag\n";