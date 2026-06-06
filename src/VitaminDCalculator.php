<?php
/*
 * Copyright (C) 2026 German-Service-Network <support@gsnw.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Gsnw\VitaminDCalculator;

class VitaminDCalculator
{
  const NG_ML_TO_NMOL_L = 2.5; // 1 ng/ml = 2.5 nmol/l
  const IE_PER_NG_ML = 1000; // 1,000 IU increases the level by ~1 ng/ml
  const MAX_SAFE_DOSE = 4000; // Tolerable Upper Intake Level (IU/day) according to EFSA

  private static $translations = [
    'de' => [
      'current_level' => "Aktueller Spiegel: %.1f ng/ml\n",
      'target_level' => "Zielspiegel: %.1f ng/ml\n\n",
      'loading_dose' => "Empfohlene Ladedosis: %d IE/Tag für %d Tage, um den Zielwert zu erreichen.\n",
      'maintenance_dose' => "Empfohlene Erhaltungsdosis: %d IE/Tag, um den Spiegel zu halten.\n",
      'already_in_range' => "Dein aktueller Spiegel ist bereits im Zielbereich oder darüber.\n",
      'warning' => "\nAchtung: Die berechnete Dosis liegt über der tolerierbaren Obergrenze von %d IE/Tag. Bitte konsultiere einen Arzt!\n",
      'invalid_input' => "Alle Werte müssen größer als 0 sein.",
      'target_too_high' => "Zielspiegel sollte nicht über 100 ng/ml liegen."
    ],
    'en' => [
      'current_level' => "Current level: %.1f ng/ml\n",
      'target_level' => "Target level: %.1f ng/ml\n\n",
      'loading_dose' => "Recommended loading dose: %d IU/day for %d days to reach the target level.\n",
      'maintenance_dose' => "Recommended maintenance dose: %d IU/day to maintain the level.\n",
      'already_in_range' => "Your current level is already within or above the target range.\n",
      'warning' => "\nWarning: The calculated dose exceeds the safe upper limit of %d IU/day. Please consult a doctor!\n",
      'invalid_input' => "All values must be greater than 0.",
      'target_too_high' => "Target level should not exceed 100 ng/ml."
    ]
  ];

  /**
   * Calculates the daily dose (IU) needed to reach the target level.
   *
   * @param float $currentLevel Current vitamin D level (ng/ml)
   * @param float $targetLevel Target vitamin D level (ng/ml)
   * @param float $weight Body weight in kg
   * @param int $days Duration of the loading phase in days
   * @return int Recommended daily dose in IU
   * @throws \InvalidArgumentException
   */
  public static function calculateDailyDose(float $currentLevel, float $targetLevel, float $weight = 70.0, int $days = 30): int
  {
    if ($currentLevel <= 0 || $targetLevel <= 0 || $weight <= 0 || $days <= 0) {
      throw new \InvalidArgumentException(self::$translations['de']['invalid_input']);
    }

    if ($targetLevel > 100) {
      throw new \InvalidArgumentException(self::$translations['de']['target_too_high']);
    }

    $difference = $targetLevel - $currentLevel;
    if ($difference <= 0) return self::calculateMaintenanceDose($weight);

    // Formula: Adjusted factor to align with standard dosage recommendations
    $weightRatio = $weight / 70.0;
    $totalDose = $difference * 9650 * $weightRatio;
    $dailyDose = $totalDose / $days;

    return (int) round($dailyDose);
  }

  /**
   * Calculates the maintenance dose based on body weight.
   *
   * @param float $weight Body weight in kg
   * @return int Maintenance dose in IU/day
   * @throws \InvalidArgumentException
   */
  public static function calculateMaintenanceDose(float $weight): int
  {
    if ($weight <= 0) {
      throw new \InvalidArgumentException(self::$translations['de']['invalid_input']);
    }

    $dosePerKg = 30; // Average: 20–40 IU per kg
    $maintenanceDose = $weight * $dosePerKg;

    return min((int) round($maintenanceDose), self::MAX_SAFE_DOSE);
  }

  /**
   * Returns a recommendation as text in the selected language.
   *
   * @param float $currentLevel
   * @param float $targetLevel
   * @param float $weight
   * @param string $language Language (‘de’ or ‘en’)
   * @param int $days Duration of loading phase
   * @return string
   */
  public static function getRecommendation(float $currentLevel, float $targetLevel, float $weight, string $language = 'de', int $days = 30): string
  {
    if (!isset(self::$translations[$language])) {
      $language = 'en';
    }

    $translations = self::$translations[$language];
    $loadingDose = self::calculateDailyDose($currentLevel, $targetLevel, $weight, $days);
    $maintenanceDose = self::calculateMaintenanceDose($weight);

    $recommendation = sprintf(
      $translations['current_level'] . $translations['target_level'],
      $currentLevel,
      $targetLevel
    );

    if ($currentLevel < $targetLevel) {
      $recommendation .= sprintf($translations['loading_dose'], $loadingDose, $days);
    } else {
      $recommendation .= $translations['already_in_range'];
    }

    $recommendation .= sprintf($translations['maintenance_dose'], $maintenanceDose);

    if ($loadingDose > self::MAX_SAFE_DOSE) {
      $recommendation .= sprintf($translations['warning'], self::MAX_SAFE_DOSE);
    }

    return $recommendation;
  }
}
?>