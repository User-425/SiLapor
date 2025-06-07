@extends('layouts.app')

@section('title', 'Detail Perhitungan GDSS')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Detail Perhitungan GDSS {{ ucfirst($gdssMethod) }} - {{ $batch->nama_batch }}</h1>
            <p class="text-gray-600">Group Decision Support System dengan metode {{ ucfirst($gdssMethod) }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('batches.gdss', ['batch' => $batch->id_batch, 'method' => $gdssMethod]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Kembali ke Prioritas
            </a>
            <a href="{{ route('batches.show', $batch->id_batch) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                Kembali ke Batch
            </a>
        </div>
    </div>
    
    <!-- Method Explanation -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>   
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Tentang GDSS dengan Metode {{ ucfirst($gdssMethod) }}</h4>
                <p class="text-sm text-blue-700 mt-1">
                    @if($gdssMethod == 'copeland')
                    GDSS (Group Decision Support System) dengan metode Copeland menggunakan perbandingan berpasangan antar alternatif berdasarkan preferensi dari beberapa pengambil keputusan. Setiap alternatif dibandingkan dengan yang lain, dan skor dihitung berdasarkan jumlah kemenangan dikurangi jumlah kekalahan.
                    @else
                    GDSS (Group Decision Support System) dengan metode Borda menggabungkan preferensi dari beberapa pengambil keputusan dengan memberikan poin berdasarkan peringkat. Peringkat tertinggi mendapatkan poin terbanyak, dan jumlah total poin digunakan untuk menentukan peringkat akhir.
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    <!-- Calculation Process -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Proses Perhitungan GDSS</h2>
        </div>
        <div class="p-6">
            <!-- Part 1: Pelapor MABAC -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-indigo-800 mb-4">I. Perhitungan MABAC Perspektif Pelapor</h3>
                
                <!-- Pelapor Initial Decision Matrix -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">1. Matriks Keputusan Awal (Pelapor)</h4>
                    <p class="text-sm text-gray-600 mb-3">Nilai awal kriteria dari perspektif pelapor:</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    @foreach(array_keys($calculationSteps['pelapor']['weights']) as $criterion)
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ str_replace(['tingkat_kerusakan_pelapor', 'dampak_akademik_pelapor', 'kebutuhan_pelapor'], 
                                                     ['Tingkat Kerusakan', 'Dampak Akademik', 'Kebutuhan'], 
                                                     $criterion) }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($calculationSteps['pelapor']['initialMatrix'] as $reportIndex => $values)
                                @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reportId }}
                                    </td>
                                    @foreach($values as $criterion => $value)
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $value }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pelapor Normalized Matrix -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">2. Matriks Ternormalisasi (Pelapor)</h4>
                    <p class="text-sm text-gray-600 mb-3">Normalisasi nilai dengan rumus: (nilai - nilai_minimum) / (nilai_maksimum - nilai_minimum)</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    @foreach(array_keys($calculationSteps['pelapor']['weights']) as $criterion)
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ str_replace(['tingkat_kerusakan_pelapor', 'dampak_akademik_pelapor', 'kebutuhan_pelapor'], 
                                                     ['Tingkat Kerusakan', 'Dampak Akademik', 'Kebutuhan'], 
                                                     $criterion) }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($calculationSteps['pelapor']['normalizedMatrix'] as $reportIndex => $values)
                                @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reportId }}
                                    </td>
                                    @foreach($values as $criterion => $value)
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($value, 4) }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pelapor Weighted Matrix -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">3. Matriks Terbobot (Pelapor)</h4>
                    <p class="text-sm text-gray-600 mb-3">Menghitung nilai terbobot dengan rumus: v<sub>ij</sub> = w<sub>j</sub> × n<sub>ij</sub> + w<sub>j</sub></p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    @foreach(array_keys($calculationSteps['pelapor']['weights']) as $criterion)
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ str_replace(['tingkat_kerusakan_pelapor', 'dampak_akademik_pelapor', 'kebutuhan_pelapor'], 
                                                     ['Tingkat Kerusakan', 'Dampak Akademik', 'Kebutuhan'], 
                                                     $criterion) }}
                                        ({{ number_format($calculationSteps['pelapor']['weights'][$criterion] * 100) }}%)
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($calculationSteps['pelapor']['weightedMatrix'] as $reportIndex => $values)
                                @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reportId }}
                                    </td>
                                    @foreach($values as $criterion => $value)
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($value, 4) }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pelapor BAA -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">4. Border Approximation Area (Pelapor)</h4>
                    <p class="text-sm text-gray-600 mb-3">Menghitung nilai BAA dengan rumus: g<sub>j</sub> = (∏<sub>i=1</sub><sup>m</sup> v<sub>ij</sub>)<sup>1/m</sup></p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai BAA</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($calculationSteps['pelapor']['borderApproximationArea'] as $criterion => $value)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ str_replace(['tingkat_kerusakan_pelapor', 'dampak_akademik_pelapor', 'kebutuhan_pelapor'], 
                                                     ['Tingkat Kerusakan', 'Dampak Akademik', 'Kebutuhan'], 
                                                     $criterion) }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($value, 4) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pelapor Distance Matrix -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">5. Matriks Jarak dari BAA (Pelapor)</h4>
                    <p class="text-sm text-gray-600 mb-3">Menghitung jarak dari BAA dengan rumus: q<sub>ij</sub> = v<sub>ij</sub> - g<sub>j</sub></p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    @foreach(array_keys($calculationSteps['pelapor']['weights']) as $criterion)
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ str_replace(['tingkat_kerusakan_pelapor', 'dampak_akademik_pelapor', 'kebutuhan_pelapor'], 
                                                     ['Tingkat Kerusakan', 'Dampak Akademik', 'Kebutuhan'], 
                                                     $criterion) }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($calculationSteps['pelapor']['distanceMatrix'] as $reportIndex => $values)
                                @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reportId }}
                                    </td>
                                    @foreach($values as $criterion => $value)
                                    <td class="px-3 py-2 whitespace-nowrap text-sm {{ $value >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($value, 4) }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pelapor Final Scores -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">6. Skor Final dan Peringkat (Pelapor)</h4>
                    <p class="text-sm text-gray-600 mb-3">Menghitung skor final (Si) dengan menjumlahkan semua nilai pada matriks jarak.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor MABAC</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php 
                                    $sortedPelaporReports = $rankedReports;
                                    usort($sortedPelaporReports, function($a, $b) {
                                        return $a->recommended_rank_pelapor <=> $b->recommended_rank_pelapor;
                                    });
                                @endphp
                                @foreach($sortedPelaporReports as $report)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">
                                        {{ $report->recommended_rank_pelapor }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $report->id_laporan }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900">
                                        <div class="truncate max-w-xs" title="{{ $report->deskripsi }}">
                                            {{ \Illuminate\Support\Str::limit($report->deskripsi, 50) }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap font-medium {{ $report->mabac_score_pelapor >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($report->mabac_score_pelapor, 4) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Part 2: Sarpras MABAC -->
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-green-800 mb-4">II. Perhitungan MABAC Perspektif Sarpras</h3>
                
                <!-- Sarpras Initial Decision Matrix -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">1. Matriks Keputusan Awal (Sarpras)</h4>
                    <p class="text-sm text-gray-600 mb-3">Nilai awal kriteria dari perspektif sarpras:</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    @foreach(array_keys($calculationSteps['sarpras']['weights']) as $criterion)
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ str_replace(['tingkat_kerusakan_sarpras', 'dampak_akademik_sarpras', 'kebutuhan_sarpras'], 
                                                     ['Tingkat Kerusakan', 'Dampak Akademik', 'Kebutuhan'], 
                                                     $criterion) }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($calculationSteps['sarpras']['initialMatrix'] as $reportIndex => $values)
                                @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reportId }}
                                    </td>
                                    @foreach($values as $criterion => $value)
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $value }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Sarpras Final Scores -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">6. Skor Final dan Peringkat (Sarpras)</h4>
                    <p class="text-sm text-gray-600 mb-3">Menghitung skor final (Si) dengan menjumlahkan semua nilai pada matriks jarak.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor MABAC</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php 
                                    $sortedSarprasReports = $rankedReports;
                                    usort($sortedSarprasReports, function($a, $b) {
                                        return $a->recommended_rank_sarpras <=> $b->recommended_rank_sarpras;
                                    });
                                @endphp
                                @foreach($sortedSarprasReports as $report)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-900">
                                        {{ $report->recommended_rank_sarpras }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $report->id_laporan }}
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900">
                                        <div class="truncate max-w-xs" title="{{ $report->deskripsi }}">
                                            {{ \Illuminate\Support\Str::limit($report->deskripsi, 50) }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap font-medium {{ $report->mabac_score_sarpras >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($report->mabac_score_sarpras, 4) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Part 3: GDSS Calculation -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-purple-800 mb-4">III. Penggabungan dengan GDSS ({{ ucfirst($gdssMethod) }})</h3>
                
                <!-- Copeland Method Specific Calculations -->
                @if($gdssMethod == 'copeland')
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">1. Perbandingan Berpasangan (Copeland)</h4>
                    <p class="text-sm text-gray-600 mb-3">Setiap laporan dibandingkan satu sama lain berdasarkan kedua peringkat. Nilai 1 berarti baris mengalahkan kolom, -1 berarti kalah, dan 0 berarti seri atau sama.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID / ID</th>
                                    @foreach(array_keys($calculationSteps['gdss']['pairwiseMatrix']) as $reportId)
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $reportId }}</th>
                                    @endforeach
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Skor</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($calculationSteps['gdss']['pairwiseMatrix'] as $rowId => $comparisons)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $rowId }}</td>
                                    @foreach($comparisons as $colId => $value)
                                    <td class="px-3 py-2 text-center text-sm 
                                        @if($rowId == $colId) text-gray-400
                                        @elseif($value > 0) text-green-600 font-medium
                                        @elseif($value < 0) text-red-600 font-medium
                                        @else text-yellow-600 font-medium @endif">
                                        {{ $value }}
                                    </td>
                                    @endforeach
                                    <td class="px-3 py-2 text-center text-sm font-medium {{ $calculationSteps['gdss']['copelandScores'][$rowId] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $calculationSteps['gdss']['copelandScores'][$rowId] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 bg-yellow-50 p-3 rounded-md">
                        <p class="text-sm text-yellow-800">
                            <strong>Interpretasi:</strong> Skor Copeland dihitung sebagai jumlah kemenangan (1) dikurangi jumlah kekalahan (-1). Semakin tinggi skor, semakin tinggi peringkat.
                        </p>
                    </div>
                </div>
                @endif
                
                <!-- Borda Method Specific Calculations -->
                @if($gdssMethod == 'borda')
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-2">1. Perhitungan Poin Borda</h4>
                    <p class="text-sm text-gray-600 mb-3">Metode Borda memberikan poin berdasarkan peringkat. Jumlah poin = (Total Laporan - Peringkat + 1). Semakin tinggi peringkat, semakin banyak poin.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat Pelapor</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Poin Pelapor</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat Sarpras</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Poin Sarpras</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Poin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($calculationSteps['gdss']['bordaMatrix'] as $reportId => $data)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $reportId }}</td>
                                    <td class="px-3 py-2 text-center text-sm text-gray-900">#{{ $data['pelapor_rank'] }}</td>
                                    <td class="px-3 py-2 text-center text-sm font-medium text-blue-600">{{ $data['pelapor_points'] }}</td>
                                    <td class="px-3 py-2 text-center text-sm text-gray-900">#{{ $data['sarpras_rank'] }}</td>
                                    <td class="px-3 py-2 text-center text-sm font-medium text-green-600">{{ $data['sarpras_points'] }}</td>
                                    <td class="px-3 py-2 text-center text-sm font-medium text-indigo-600">{{ $data['total_points'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 bg-yellow-50 p-3 rounded-md">
                        <p class="text-sm text-yellow-800">
                            <strong>Interpretasi:</strong> Untuk setiap laporan, poin Borda dihitung dari peringkat pelapor dan sarpras. Rumus: Poin = ({{ $calculationSteps['gdss']['totalReports'] ?? count($rankedReports) }} - Peringkat + 1). Total poin menentukan peringkat akhir.
                        </p>
                    </div>
                </div>
                @endif
                
                <!-- Final Rankings -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-2">2. Peringkat Akhir GDSS</h4>
                    <p class="text-sm text-gray-600 mb-3">Berikut adalah peringkat akhir berdasarkan metode {{ ucfirst($gdssMethod) }}.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank Pelapor</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank Sarpras</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor {{ ucfirst($gdssMethod) }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rankedReports as $index => $report)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $index + 1 }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ $report->id_laporan }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-900">
                                        <div class="truncate max-w-xs" title="{{ $report->deskripsi }}">
                                            {{ \Illuminate\Support\Str::limit($report->deskripsi, 40) }}
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-center">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            #{{ $report->recommended_rank_pelapor }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-center">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            #{{ $report->recommended_rank_sarpras }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium {{ $report->final_score >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($report->final_score, is_integer($report->final_score) ? 0 : 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex justify-end mt-6">
        <a href="{{ route('batches.gdss', ['batch' => $batch->id_batch, 'method' => $gdssMethod]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Kembali ke Halaman Prioritas
        </a>
    </div>
</div>
@endsection