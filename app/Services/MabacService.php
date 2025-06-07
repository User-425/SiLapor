<?php

namespace App\Services;

class MabacService
{
    /**
     * Calculate repair priorities using MABAC method
     * 
     * @param array $reports Collection of reports with criteria
     * @param array $weights Criteria weights
     * @return array Reports with calculated priorities and calculation steps
     */
    public function calculatePriorities($reports, $weights = null)
    {
        // Default weights if not provided
        if (!$weights) {
            $weights = [
                'tingkat_kerusakan_sarpras' => 0.35,
                'dampak_akademik_sarpras' => 0.35,
                'kebutuhan_sarpras' => 0.30,
            ];
        }
        
        // Step 1: Create decision matrix
        $matrix = $this->createDecisionMatrix($reports);
        
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
        $rankedReports = $this->assignRankings($reports, $scores);
        
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
     * Create initial decision matrix from reports
     */
    private function createDecisionMatrix($reports)
    {
        $matrix = [];
        
        foreach ($reports as $index => $report) {
            if (!$report->kriteria) {
                // If report has no criteria, assign default medium values (3)
                $matrix[$index] = [
                    'tingkat_kerusakan_sarpras' => 3,
                    'dampak_akademik_sarpras' => 3,
                    'kebutuhan_sarpras' => 3,
                ];
                continue;
            }
            
            $matrix[$index] = [
                'tingkat_kerusakan_sarpras' => $report->kriteria->tingkat_kerusakan_sarpras,
                'dampak_akademik_sarpras' => $report->kriteria->dampak_akademik_sarpras,
                'kebutuhan_sarpras' => $report->kriteria->kebutuhan_sarpras,
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
                $normalizedMatrix[$index][$criterion] = $diff == 0 ? 0.5 : 
                    ($value - $criteriaMin[$criterion]) / $diff;
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
     * Assign rankings to reports based on scores
     */
    private function assignRankings($reports, $scores)
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
        usort($reportScores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Assign rankings
        $rank = 1;
        $rankedReports = [];
        foreach ($reportScores as $item) {
            $report = $item['report'];
            $report->mabac_score = $item['score'];
            $report->recommended_rank = $rank;
            $rankedReports[] = $report;
            $rank++;
        }
        
        return $rankedReports;
    }
}