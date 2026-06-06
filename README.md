# Vitamin D Calculator

This is a PHP Composer class for calculating vitamin D requirements.
**Please note**: This calculation does not constitute medical advice and is intended solely as a non-binding guideline. For a proper diagnosis and treatment plan, please consult a healthcare professional.

## Usage

Example `index.php`

```
<?php

require_once __DIR__ . '/vendor/autoload.php';

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
```

Execute: `php index.php` or `php -S localhost:8000`

## Example output

```
=== Deutsch ===
Aktueller Spiegel: 20.0 ng/ml
Zielspiegel: 40.0 ng/ml

Empfohlene Ladedosis: 25000 IE/Tag für 2–3 Monate, um den Zielwert zu erreichen.
Empfohlene Erhaltungsdosis: 2550 IE/Tag, um den Spiegel zu halten.

Achtung: Die berechnete Dosis liegt über der tolerierbaren Obergrenze von 4000 IE/Tag. Bitte konsultiere einen Arzt!

=== English ===
Current level: 20.0 ng/ml
Target level: 40.0 ng/ml

Recommended loading dose: 25000 IU/day for 2–3 months to reach the target level.
Recommended maintenance dose: 2550 IU/day to maintain the level.

Warning: The calculated dose exceeds the safe upper limit of 4000 IU/day. Please consult a doctor!

=== Direct calculation ===
Ladedosis: 25000 IE/Tag
Erhaltungsdosis: 2550 IE/Tag
```