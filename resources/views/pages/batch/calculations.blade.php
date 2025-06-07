@extends('layouts.app')

@section('title', 'Detail Perhitungan MABAC')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Detail Perhitungan MABAC - {{ $batch->nama_batch }}</h1>
            <p class="text-gray-600">Multi-Attributive Border Approximation area Comparison</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('batches.ranking', $batch->id_batch) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Kembali ke Prioritas
            </a>
            <a href="{{ route('batches.show', $batch->id_batch) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                Kembali ke Batch
            </a>
        </div>
    </div>
    
    <!-- Step 0: Explanation -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Tentang Metode MABAC</h4>
                <p class="text-sm text-blue-700 mt-1">
                    MABAC (Multi-Attributive Border Approximation area Comparison) adalah metode pengambilan keputusan multi-kriteria yang menggunakan pendekatan matematis untuk menentukan prioritas berdasarkan beberapa kriteria. Metode ini menilai jarak setiap alternatif dari area perbatasan aproksimasi.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Step 1: Criteria Weights -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Langkah 1: Bobot Kriteria</h2>
        </div>
        <div class="p-6">
            <p class="mb-4">Bobot yang digunakan untuk menilai setiap kriteria dalam perhitungan prioritas:</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($calculationSteps['weights'] as $criterion => $weight)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ str_replace('_', ' ', ucwords($criterion)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $weight }} ({{ $weight * 100 }}%)
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Step 2: Initial Decision Matrix -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Langkah 2: Matriks Keputusan Awal</h2>
        </div>
        <div class="p-6">
            <p class="mb-4">Nilai awal untuk setiap kriteria dari setiap laporan:</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Laporan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            @foreach(array_keys($calculationSteps['weights']) as $criterion)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ str_replace('_', ' ', ucwords($criterion)) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($calculationSteps['initialMatrix'] as $reportIndex => $values)
                        @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reportId }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="truncate max-w-xs" title="{{ $reportsById[$reportId]->deskripsi }}">
                                    {{ \Illuminate\Support\Str::limit($reportsById[$reportId]->deskripsi, 30) }}
                                </div>
                            </td>
                            @foreach($values as $criterion => $value)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $value }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Step 3: Normalized Matrix -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Langkah 3: Matriks Ternormalisasi</h2>
        </div>
        <div class="p-6">
            <p class="mb-4">Normalisasi nilai setiap kriteria dengan rumus: (nilai - nilai_minimum) / (nilai_maksimum - nilai_minimum)</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Laporan</th>
                            @foreach(array_keys($calculationSteps['weights']) as $criterion)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ str_replace('_', ' ', ucwords($criterion)) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($calculationSteps['normalizedMatrix'] as $reportIndex => $values)
                        @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reportId }}
                            </td>
                            @foreach($values as $criterion => $value)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($value, 4) }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Step 4: Weighted Matrix -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Langkah 4: Matriks Terbobot</h2>
        </div>
        <div class="p-6">
            <p class="mb-4">Menghitung nilai terbobot dengan rumus: v<sub>ij</sub> = w<sub>j</sub> × n<sub>ij</sub> + w<sub>j</sub></p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Laporan</th>
                            @foreach(array_keys($calculationSteps['weights']) as $criterion)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ str_replace('_', ' ', ucwords($criterion)) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($calculationSteps['weightedMatrix'] as $reportIndex => $values)
                        @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reportId }}
                            </td>
                            @foreach($values as $criterion => $value)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($value, 4) }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Step 5: Border Approximation Area -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Langkah 5: Border Approximation Area (BAA)</h2>
        </div>
        <div class="p-6">
            <p class="mb-4">Menghitung nilai BAA dengan rumus: g<sub>j</sub> = (∏<sub>i=1</sub><sup>m</sup> v<sub>ij</sub>)<sup>1/m</sup></p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai BAA</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($calculationSteps['borderApproximationArea'] as $criterion => $value)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ str_replace('_', ' ', ucwords($criterion)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($value, 4) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Step 6: Distance Matrix -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Langkah 6: Matriks Jarak dari BAA</h2>
        </div>
        <div class="p-6">
            <p class="mb-4">Menghitung jarak dari BAA dengan rumus: q<sub>ij</sub> = v<sub>ij</sub> - g<sub>j</sub></p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Laporan</th>
                            @foreach(array_keys($calculationSteps['weights']) as $criterion)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ str_replace('_', ' ', ucwords($criterion)) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($calculationSteps['distanceMatrix'] as $reportIndex => $values)
                        @php $reportId = $rankedReports[$reportIndex]->id_laporan; @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $reportId }}
                            </td>
                            @foreach($values as $criterion => $value)
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $value >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($value, 4) }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Step 7: Final Scores -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-lg text-gray-800">Langkah 7: Skor Final dan Peringkat</h2>
        </div>
        <div class="p-6">
            <p class="mb-4">Menghitung skor final (Si) dengan menjumlahkan semua nilai pada matriks jarak. Semakin tinggi skor, semakin tinggi prioritasnya.</p>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Laporan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor MABAC</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rankedReports as $index => $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $report->id_laporan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="truncate max-w-xs" title="{{ $report->deskripsi }}">
                                    {{ \Illuminate\Support\Str::limit($report->deskripsi, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium {{ $report->mabac_score >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($report->mabac_score, 4) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                <div class="bg-gray-50 p-4 rounded-md">
                    <h4 class="font-medium text-gray-800 mb-2">Interpretasi:</h4>
                    <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
                        <li>Nilai positif menunjukkan bahwa alternatif berada di area superior (direkomendasikan)</li>
                        <li>Nilai negatif menunjukkan bahwa alternatif berada di area inferior (kurang direkomendasikan)</li>
                        <li>Semakin tinggi nilai skor, semakin tinggi prioritas diberikan pada laporan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex justify-end mt-6">
        <a href="{{ route('batches.ranking', $batch->id_batch) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Kembali ke Halaman Prioritas
        </a>
    </div>
</div>
@endsection