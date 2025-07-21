@extends('user.layouts.app')

@section('title', 'Budget Analysis')

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <h2 class="content-header-title">Budget Analysis</h2>
                </div>
            </div>
            <div class="content-body">

                <section class="app-user-view">
                    <div class="col-md-12">
                        <div class="card user-card">
                            <div class="card-body">

                                <!-- REVENUS Table -->
                                <h3>REVENUS</h3>
                                <div class="table-responsive mb-25">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Description</th>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <th>{{ $year }}</th>
                                                <th>% of Total</th>
                                            @endfor
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($revenusData as $line)
                                            <tr>
                                                <td>{{ $line['code'] }}</td>
                                                <td>{{ $line['description'] }}</td>
                                                @for ($year = $startYear; $year <= $endYear; $year++)
                                                    <td class="table-info">{{ $line['amounts'][$year] ?? '-' }}</td>
                                                    <td>
                                                        @php
                                                            $total = $revenusTotals[$year] ?? 1;
                                                            $percent = isset($line['amounts'][$year]) ? round(($line['amounts'][$year] / $total) * 100, 2) : 0;
                                                        @endphp
                                                        {{ $percent }}%
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endforeach
                                        <tr class="table-success font-weight-bold">
                                            <td colspan="2">Total REVENUS</td>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <td>{{ $revenusTotals[$year] ?? 0 }}</td>
                                                <td>100%</td>
                                            @endfor
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- COÛTS DIRECTS Table -->
                                <h3>COÛTS DIRECTS</h3>
                                <div class="table-responsive mb-25">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Description</th>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <th>{{ $year }}</th>
                                                <th>% of REVENUS</th>
                                            @endfor
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($coutsDirectsData['subtypes'] as $subtypeName => $subtypeData)
                                            <!-- Subtype Row -->
                                            <tr class="table-secondary font-weight-bold">
                                                <td colspan="{{ 2 + 2 * ($endYear - $startYear + 1) }}">{{ $subtypeName }}</td>
                                            </tr>

                                            @foreach ($subtypeData['lines'] as $line)
                                                <tr>
                                                    <td>{{ $line['code'] }}</td>
                                                    <td>{{ $line['description'] }}</td>
                                                    @for ($year = $startYear; $year <= $endYear; $year++)
                                                        <td class="table-info">{{ $line['amounts'][$year] ?? '-' }}</td>
                                                        <td>
                                                            @php
                                                                $totalRevenus = $revenusTotals[$year] ?? 1;
                                                                $percent = isset($line['amounts'][$year]) ? round(($line['amounts'][$year] / $totalRevenus) * 100, 2) : 0;
                                                            @endphp
                                                            {{ $percent }}%
                                                        </td>
                                                    @endfor
                                                </tr>
                                            @endforeach

                                            <!-- Subtype Totals -->
                                            <tr class="table-primary font-weight-bold">
                                                <td colspan="2">Total {{ $subtypeName }}</td>
                                                @for ($year = $startYear; $year <= $endYear; $year++)
                                                    <td>{{ $subtypeData['totals'][$year] ?? 0 }}</td>
                                                    <td>
                                                        @php
                                                            $totalRevenus = $revenusTotals[$year] ?? 1;
                                                            $percent = isset($subtypeData['totals'][$year]) ? round(($subtypeData['totals'][$year] / $totalRevenus) * 100, 2) : 0;
                                                        @endphp
                                                        {{ $percent }}%
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endforeach
                                        <!-- COÛTS DIRECTS Totals -->
                                        <tr class="table-warning font-weight-bold">
                                            <td colspan="2">Total COÛTS DIRECTS</td>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <td>{{ $coutsDirectsData['totals'][$year] ?? 0 }}</td>
                                                <td>
                                                    @php
                                                        $totalRevenus = $revenusTotals[$year] ?? 1;
                                                        $percent = isset($coutsDirectsData['totals'][$year]) ? round(($coutsDirectsData['totals'][$year] / $totalRevenus) * 100, 2) : 0;
                                                    @endphp
                                                    {{ $percent }}%
                                                </td>
                                            @endfor
                                        </tr>

                                        <!-- Profit Brut Row -->
                                        <tr class="table-success font-weight-bold mt-25">
                                            <td colspan="2">Profit Brut</td>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                @php
                                                    $profitBrut = ($revenusTotals[$year] ?? 0) - ($coutsDirectsData['totals'][$year] ?? 0);
                                                    $percent = $revenusTotals[$year] > 0 ? round(($profitBrut / $revenusTotals[$year]) * 100, 2) : 0;
                                                @endphp
                                                <td>{{ $profitBrut }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endfor
                                        </tr>


                                        </tbody>
                                    </table>
                                </div>

                                <!-- FRAIS D'EXPLOITATION Table -->
                                <h3>FRAIS D'EXPLOITATION</h3>
                                <div class="table-responsive mb-25">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Description</th>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <th>{{ $year }}</th>
                                                <th>% of REVENUS</th>
                                            @endfor
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($fraisExploitationData['subtypes'] as $subtypeName => $subtypeData)
                                            <!-- Subtype Row -->
                                            <tr class="table-secondary font-weight-bold">
                                                <td colspan="{{ 2 + 2 * ($endYear - $startYear + 1) }}">{{ $subtypeName }}</td>
                                            </tr>

                                            @foreach ($subtypeData['lines'] as $line)
                                                <tr>
                                                    <td>{{ $line['code'] }}</td>
                                                    <td>{{ $line['description'] }}</td>
                                                    @for ($year = $startYear; $year <= $endYear; $year++)
                                                        <td class="table-info">{{ $line['amounts'][$year] ?? '-' }}</td>
                                                        <td>
                                                            @php
                                                                $totalRevenus = $revenusTotals[$year] ?? 1; // Avoid division by zero
                                                                $percent = isset($line['amounts'][$year]) ? round(($line['amounts'][$year] / $totalRevenus) * 100, 2) : 0;
                                                            @endphp
                                                            {{ $percent }}%
                                                        </td>
                                                    @endfor
                                                </tr>
                                            @endforeach

                                            <!-- Subtype Totals -->
                                            <tr class="table-primary font-weight-bold">
                                                <td colspan="2">Total {{ $subtypeName }}</td>
                                                @for ($year = $startYear; $year <= $endYear; $year++)
                                                    <td>{{ $subtypeData['totals'][$year] ?? 0 }}</td>
                                                    <td>
                                                        @php
                                                            $totalRevenus = $revenusTotals[$year] ?? 1;
                                                            $percent = isset($subtypeData['totals'][$year]) ? round(($subtypeData['totals'][$year] / $totalRevenus) * 100, 2) : 0;
                                                        @endphp
                                                        {{ $percent }}%
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endforeach

                                        <!-- FRAIS D'EXPLOITATION Totals -->
                                        <tr class="table-warning font-weight-bold">
                                            <td colspan="2">Total FRAIS D'EXPLOITATION</td>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <td>{{ $fraisExploitationData['totals'][$year] ?? 0 }}</td>
                                                <td>
                                                    @php
                                                        $totalRevenus = $revenusTotals[$year] ?? 1;
                                                        $percent = isset($fraisExploitationData['totals'][$year]) ? round(($fraisExploitationData['totals'][$year] / $totalRevenus) * 100, 2) : 0;
                                                    @endphp
                                                    {{ $percent }}%
                                                </td>
                                            @endfor
                                        </tr>

                                        <!-- Bénéfice Avant Impôt Row -->
                                        <tr class="table-success font-weight-bold">
                                            <td colspan="2">Bénéfice Avant Impôt</td>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                @php
                                                    $profitBrut = ($revenusTotals[$year] ?? 0) - ($coutsDirectsData['totals'][$year] ?? 0);
                                                    $fraisExploitationTotal = $fraisExploitationData['totals'][$year] ?? 0;
                                                    $beneficeAvantImpot = $profitBrut - $fraisExploitationTotal;
                                                    $percent = $revenusTotals[$year] > 0 ? round(($beneficeAvantImpot / $revenusTotals[$year]) * 100, 2) : 0;
                                                @endphp
                                                <td>{{ $beneficeAvantImpot }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endfor
                                        </tr>
                                        <!-- Facteur de Frais d'Exploitation Row -->
                                        <tr class="table-danger font-weight-bold">
                                            <td colspan="2">Facteur de Frais d'Exploitation (%)</td>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                @php
                                                    $fraisExploitationTotal = $fraisExploitationData['totals'][$year] ?? 0;
                                                    $coutsDirectsTotal = $coutsDirectsData['totals'][$year] ?? 1; // Avoid division by zero
                                                    $facteurFraisExploitation = round(($fraisExploitationTotal / $coutsDirectsTotal) * 100, 2);
                                                @endphp
                                                <td colspan="2">{{ $facteurFraisExploitation }}%</td>
                                            @endfor
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- AUTRES REVENUS ET CHARGES Table -->
                                <h3>Autres Revenus et Charges</h3>
                                <div class="table-responsive mb-25">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Description</th>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <th>{{ $year }}</th>
                                                <th>% of Total REVENUS</th>
                                            @endfor
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($autresRevenusChargesData['lines'] as $line)
                                            <tr>
                                                <td>{{ $line['code'] }}</td>
                                                <td>{{ $line['description'] }}</td>
                                                @for ($year = $startYear; $year <= $endYear; $year++)
                                                    <td class="table-info">{{ $line['amounts'][$year] ?? '-' }}</td>
                                                    <td>
                                                        @php
                                                            $totalRevenus = $revenusTotals[$year] ?? 1; // Avoid division by zero
                                                            $percent = isset($line['amounts'][$year]) ? round(($line['amounts'][$year] / $totalRevenus) * 100, 2) : 0;
                                                        @endphp
                                                        {{ $percent }}%
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endforeach

                                        <!-- AUTRES REVENUS ET CHARGES Totals -->
                                        <tr class="table-warning font-weight-bold">
                                            <td colspan="2">Total Autres Revenus et Charges</td>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <td>{{ $autresRevenusChargesData['totals'][$year] ?? 0 }}</td>
                                                <td>
                                                    @php
                                                        $totalRevenus = $revenusTotals[$year] ?? 1;
                                                        $percent = isset($autresRevenusChargesData['totals'][$year]) ? round(($autresRevenusChargesData['totals'][$year] / $totalRevenus) * 100, 2) : 0;
                                                    @endphp
                                                    {{ $percent }}%
                                                </td>
                                            @endfor
                                        </tr>
                                        <!-- Total Profit Net -->
                                        <tr class="table-success font-weight-bold">
                                            <td colspan="2">Total Profit Net</td>
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <td>{{ $totalProfitNet[$year] ?? 0 }}</td>
                                                <td>
                                                    @php
                                                        $totalRevenus = $revenusTotals[$year] ?? 1; // Avoid division by zero
                                                        $percent = isset($totalProfitNet[$year]) ? round(($totalProfitNet[$year] / $totalRevenus) * 100, 2) : 0;
                                                    @endphp
                                                    {{ $percent }}%
                                                </td>
                                            @endfor
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection



