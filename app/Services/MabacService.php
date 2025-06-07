<?php

namespace App\Services;

class MabacService
{
    /**
     * Calculate repair priorities using MABAC method with GDSS
     * 
     * @param array $reports Collection of reports with criteria
     * @param string $gdssMethod The GDSS method to use ('copeland' or 'borda')
     * @return array Reports with calculated priorities and calculation steps
     */
    public function calculatePriorities($reports, $gdssMethod = 'copeland')
    {
        // Calculate priorities for pelapor criteria
        $pelaporResult = $this->calculatePrioritiesByPerspective($reports, 'pelapor');

        // Calculate priorities for sarpras criteria
        $sarprasResult = $this->calculatePrioritiesByPerspective($reports, 'sarpras');

        // Combine the two perspectives using the selected GDSS method
        if ($gdssMethod == 'copeland') {
            $finalResult = $this->applyGDSSCopeland($reports, $pelaporResult['reports'], $sarprasResult['reports']);
        } else {
            $finalResult = $this->applyGDSSBorda($reports, $pelaporResult['reports'], $sarprasResult['reports']);
        }

        return [
            'reports' => $finalResult['reports'],
            'steps' => [
                'pelapor' => $pelaporResult['steps'],
                'sarpras' => $sarprasResult['steps'],
                'gdss' => $finalResult['steps'],
                'method' => $gdssMethod
            ]
        ];
    }

    /**
     * Calculate priorities for a specific perspective (pelapor or sarpras)
     */
    private function calculatePrioritiesByPerspective($reports, $perspective = 'sarpras')
    {
        // Set weights based on perspective
        $weights = [
            'tingkat_kerusakan_' . $perspective => 0.35,
            'dampak_akademik_' . $perspective => 0.35,
            'kebutuhan_' . $perspective => 0.30,
        ];

        // Step 1: Create decision matrix based on perspective
        $matrix = $this->createDecisionMatrixByPerspective($reports, $perspective);

        // Step 2: Normalize the decision matrix
        $normalizedMatrix = $this->normalizeMatrix($matrix);

        // Step 3: Calculate the weighted matrix
        $weightedMatrix = $this->calculateWeightedMatrix($normalizedMatrix, $weights);

        // Step 4: Determine the border approximation area (BAA)
        $borderApproximationArea = $this->calculateBAA($weightedMatrix);

        // Step 5: Calculate distance from BAA for each alternative
        $distanceMatrix = $this->calculateDistanceFromBAA($weightedMatrix, $borderApproximationArea);

        // Step 6: Calculate final scores and rank reports
        $scores = $this->calculateScores($distanceMatrix);

        // Step 7: Sort reports by score and assign rankings
        $rankedReports = $this->assignRankingsByPerspective($reports, $scores, $perspective);

        $calculationSteps = [
            'weights' => $weights,
            'initialMatrix' => $matrix,
            'normalizedMatrix' => $normalizedMatrix,
            'weightedMatrix' => $weightedMatrix,
            'borderApproximationArea' => $borderApproximationArea,
            'distanceMatrix' => $distanceMatrix,
            'scores' => $scores
        ];

        return [
            'reports' => $rankedReports,
            'steps' => $calculationSteps
        ];
    }

    /**
     * Create decision matrix from reports based on perspective
     */
    private function createDecisionMatrixByPerspective($reports, $perspective = 'sarpras')
    {
        $matrix = [];

        foreach ($reports as $index => $report) {
            if (!$report->kriteria) {
                // If report has no criteria, assign default medium values (3)
                $matrix[$index] = [
                    'tingkat_kerusakan_' . $perspective => 3,
                    'dampak_akademik_' . $perspective => 3,
                    'kebutuhan_' . $perspective => 3,
                ];
                continue;
            }

            $matrix[$index] = [
                'tingkat_kerusakan_' . $perspective => $report->kriteria->{'tingkat_kerusakan_' . $perspective} ?? 3,
                'dampak_akademik_' . $perspective => $report->kriteria->{'dampak_akademik_' . $perspective} ?? 3,
                'kebutuhan_' . $perspective => $report->kriteria->{'kebutuhan_' . $perspective} ?? 3,
            ];
        }

        return $matrix;
    }

    /**
     * Normalize the decision matrix
     */
    private function normalizeMatrix($matrix)
    {
        $normalizedMatrix = [];
        $criteriaMin = [];
        $criteriaMax = [];

        // Get min and max values for each criteria
        foreach ($matrix as $row) {
            foreach ($row as $criterion => $value) {
                if (!isset($criteriaMin[$criterion]) || $criteriaMin[$criterion] > $value) {
                    $criteriaMin[$criterion] = $value;
                }
                if (!isset($criteriaMax[$criterion]) || $criteriaMax[$criterion] < $value) {
                    $criteriaMax[$criterion] = $value;
                }
            }
        }

        // Normalize values using formula: (x - x_min) / (x_max - x_min)
        foreach ($matrix as $index => $row) {
            foreach ($row as $criterion => $value) {
                $diff = $criteriaMax[$criterion] - $criteriaMin[$criterion];
                $normalizedMatrix[$index][$criterion] = $diff == 0 ? 0.5 : ($value - $criteriaMin[$criterion]) / $diff;
            }
        }

        return $normalizedMatrix;
    }

    /**
     * Calculate weighted matrix
     */
    private function calculateWeightedMatrix($normalizedMatrix, $weights)
    {
        $weightedMatrix = [];

        foreach ($normalizedMatrix as $index => $row) {
            $weightedMatrix[$index] = [];
            foreach ($row as $criterion => $value) {
                $weightedMatrix[$index][$criterion] = $weights[$criterion] * $value + $weights[$criterion];
            }
        }

        return $weightedMatrix;
    }

    /**
     * Calculate border approximation area
     */
    private function calculateBAA($weightedMatrix)
    {
        $baa = [];
        $criteriaCount = count($weightedMatrix[0] ?? []);
        $reportCount = count($weightedMatrix);

        if ($reportCount === 0 || $criteriaCount === 0) {
            return $baa;
        }

        // Initialize criteria arrays to 1 for multiplication
        foreach (array_keys($weightedMatrix[0]) as $criterion) {
            $baa[$criterion] = 1;
        }

        // Calculate product of values for each criterion
        foreach ($weightedMatrix as $row) {
            foreach ($row as $criterion => $value) {
                $baa[$criterion] *= $value;
            }
        }

        // Calculate nth root (geometric mean) for each criterion
        foreach ($baa as $criterion => $product) {
            $baa[$criterion] = pow($product, 1 / $reportCount);
        }

        return $baa;
    }

    /**
     * Calculate distance from border approximation area
     */
    private function calculateDistanceFromBAA($weightedMatrix, $borderApproximationArea)
    {
        $distanceMatrix = [];

        foreach ($weightedMatrix as $index => $row) {
            $distanceMatrix[$index] = [];
            foreach ($row as $criterion => $value) {
                $distanceMatrix[$index][$criterion] = $value - $borderApproximationArea[$criterion];
            }
        }

        return $distanceMatrix;
    }

    /**
     * Calculate final scores
     */
    private function calculateScores($distanceMatrix)
    {
        $scores = [];

        foreach ($distanceMatrix as $index => $row) {
            $scores[$index] = array_sum($row);
        }

        return $scores;
    }

    /**
     * Assign rankings to reports based on scores by perspective
     */
    private function assignRankingsByPerspective($reports, $scores, $perspective = 'sarpras')
    {
        // Combine reports with scores
        $reportScores = [];
        foreach ($scores as $index => $score) {
            $reportScores[] = [
                'report' => $reports[$index],
                'score' => $score
            ];
        }

        // Sort by scores (descending)
        usort($reportScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Assign rankings
        $rank = 1;
        $rankedReports = [];
        foreach ($reportScores as $item) {
            $report = $item['report'];
            $report->{'mabac_score_' . $perspective} = $item['score'];
            $report->{'recommended_rank_' . $perspective} = $rank;
            $rankedReports[] = $report;
            $rank++;
        }

        return $rankedReports;
    }

    /**
     * Apply Copeland method to combine two rankings
     */
    private function applyGDSSCopeland($reports, $pelaporReports, $sarprasReports)
    {
        // Create a map of report ID to their ranks
        $pelaporRanks = [];
        foreach ($pelaporReports as $index => $report) {
            $pelaporRanks[$report->id_laporan] = $report->recommended_rank_pelapor;
        }

        $sarprasRanks = [];
        foreach ($sarprasReports as $index => $report) {
            $sarprasRanks[$report->id_laporan] = $report->recommended_rank_sarpras;
        }

        // Build the Copeland matrix (for pairwise comparisons)
        $reportIds = array_keys($pelaporRanks);
        $copelandScores = array_fill_keys($reportIds, 0);
        $pairwiseMatrix = [];

        // Perform pairwise comparisons
        foreach ($reportIds as $i) {
            $pairwiseMatrix[$i] = [];
            foreach ($reportIds as $j) {
                if ($i == $j) {
                    $pairwiseMatrix[$i][$j] = 0; // Same report
                    continue;
                }

                $pelaporWins = $pelaporRanks[$i] < $pelaporRanks[$j] ? 1 : 0;
                $sarprasWins = $sarprasRanks[$i] < $sarprasRanks[$j] ? 1 : 0;

                // Sum the wins
                $result = $pelaporWins + $sarprasWins;

                // If total wins > 1, i wins against j
                if ($result > 1) {
                    $pairwiseMatrix[$i][$j] = 1;
                    $copelandScores[$i]++;
                }
                // If total wins = 1, it's a tie
                else if ($result == 1) {
                    $pairwiseMatrix[$i][$j] = 0;
                }
                // Otherwise, j wins against i
                else {
                    $pairwiseMatrix[$i][$j] = -1;
                    $copelandScores[$i]--;
                }
            }
        }

        // Create the final report rankings
        $reportScores = [];
        foreach ($reports as $report) {
            $reportId = $report->id_laporan;
            $reportScores[] = [
                'report' => $report,
                'score' => $copelandScores[$reportId] ?? 0
            ];
        }

        // Sort by Copeland scores (descending)
        usort($reportScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Assign final rankings
        $rank = 1;
        $rankedReports = [];
        foreach ($reportScores as $item) {
            $report = $item['report'];
            $report->final_score = $item['score'];
            $report->final_rank = $rank;
            $rankedReports[] = $report;
            $rank++;
        }

        return [
            'reports' => $rankedReports,
            'steps' => [
                'pairwiseMatrix' => $pairwiseMatrix,
                'copelandScores' => $copelandScores,
                'pelaporRanks' => $pelaporRanks,
                'sarprasRanks' => $sarprasRanks
            ]
        ];
    }

    /**
     * Apply Borda method to combine two rankings
     */
    private function applyGDSSBorda($reports, $pelaporReports, $sarprasReports)
    {
        // Create a map of report ID to their ranks
        $pelaporRanks = [];
        foreach ($pelaporReports as $index => $report) {
            $pelaporRanks[$report->id_laporan] = $report->recommended_rank_pelapor;
        }

        $sarprasRanks = [];
        foreach ($sarprasReports as $index => $report) {
            $sarprasRanks[$report->id_laporan] = $report->recommended_rank_sarpras;
        }

        // Calculate Borda points
        $totalReports = count($reports);
        $bordaScores = [];
        $bordaMatrix = [];

        foreach ($reports as $report) {
            $reportId = $report->id_laporan;

            // For Borda: points = (total reports - rank + 1)
            $pelaporPoints = $totalReports - $pelaporRanks[$reportId] + 1;
            $sarprasPoints = $totalReports - $sarprasRanks[$reportId] + 1;

            // Equal weighting (can be adjusted as needed)
            $totalPoints = $pelaporPoints + $sarprasPoints;

            $bordaScores[$reportId] = $totalPoints;
            $bordaMatrix[$reportId] = [
                'pelapor_rank' => $pelaporRanks[$reportId],
                'pelapor_points' => $pelaporPoints,
                'sarpras_rank' => $sarprasRanks[$reportId],
                'sarpras_points' => $sarprasPoints,
                'total_points' => $totalPoints
            ];
        }

        // Create the final report rankings
        $reportScores = [];
        foreach ($reports as $report) {
            $reportId = $report->id_laporan;
            $reportScores[] = [
                'report' => $report,
                'score' => $bordaScores[$reportId] ?? 0
            ];
        }

        // Sort by Borda scores (descending)
        usort($reportScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Assign final rankings
        $rank = 1;
        $rankedReports = [];
        foreach ($reportScores as $item) {
            $report = $item['report'];
            $report->final_score = $item['score'];
            $report->final_rank = $rank;
            $rankedReports[] = $report;
            $rank++;
        }

        return [
            'reports' => $rankedReports,
            'steps' => [
                'bordaMatrix' => $bordaMatrix,
                'bordaScores' => $bordaScores,
                'pelaporRanks' => $pelaporRanks,
                'sarprasRanks' => $sarprasRanks,
                'totalReports' => $totalReports
            ]
        ];
    }

    /**
     * Calculate priorities using MABAC method with GDSS (alias for consistency with controller)
     * 
     * @param array $reports Collection of reports with criteria
     * @param string $gdssMethod The GDSS method to use ('copeland' or 'borda')
     * @return array Reports with calculated priorities and calculation steps
     */
    public function calculatePrioritiesWithGDSS($reports, $gdssMethod = 'copeland')
    {
        return $this->calculatePriorities($reports, $gdssMethod);
    }
}
