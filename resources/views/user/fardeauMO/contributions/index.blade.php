@extends('user.layouts.app')

@section('title', trans_choice('labels.models.contribution', 2))

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <h2 class="content-header-title">{{ __('messages.static.list', ['name' => trans_choice('labels.models.contribution', 2)]) }}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ __('messages.static.list', ['name' => trans_choice('labels.models.contribution', 2)]) }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('user.fardeauMO.contributions.create') }}" class="btn btn-outline-primary">
                                <i data-feather="plus"></i> Ajouter Contribution CSST
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="contributionsAccordion">

                                <!-- 🔹 RRQ -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#rrqCollapse">
                                            🔹 RRQ (Régime de rentes du Québec)
                                        </button>
                                    </div>
                                    <div id="rrqCollapse" class="collapse show">
                                        <div class="card-body">
                                            <table class="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Année</th>
                                                        <th>Max Salaire</th>
                                                        <th>Exemption Base</th>
                                                        <th>Max Gains Cotisables</th>
                                                        <th>Cotisation maximale RRQ</th>
                                                        <th>Exemption à l'heure</th>
                                                        <th>Cotisation RRQ à l'heure</th>
                                                        <th>Taux de cotisation RRQ (%)</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @forelse ($generalContributions as $contribution)
                                                @if($contribution->rrq_max_salary) 
                                                 <tr>
                                                  <td>{{ $contribution->year }}</td>
                                                  <td>{{ $contribution->rrq_max_salary ?? '-' }}$</td>
                                                  <td>{{ $contribution->rrq_exemption ?? '-' }}$</td>
                                                  <td>{{ $contribution->rrq_max_gains ?? '-' }}$</td>
                                                  <td>{{ $contribution->rrq_max_contribution ?? '-' }}$</td>
                                                  <td>{{ $contribution->rrq_hourly_exemption ?? '-' }}$</td>
                                                  <td>{{ $contribution->rrq_hourly_contribution ?? '-' }}$</td>
                                                  <td>{{ $contribution->taux_de_cotisation_rrq ?? '-' }}%</td>
                                                  
                                                  <td>
                                                  <a href="{{ route('user.fardeauMO.contributions.show', $contribution->id) }}">
                                                  <i class="mr-50 fas fa-eye"></i>
                                                  </a>
                                                   </td>
                                                  </tr>
                                                  @endif
                                                  @empty
                                                   <tr>
                                                  <td colspan="9" class="text-center">Aucune contribution RRQ disponible</td>
                                                  </tr>
                                                   @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔹 AE -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#aeCollapse">
                                            🔹 AE (Assurance Emploi)
                                        </button>
                                    </div>
                                    <div id="aeCollapse" class="collapse">
                                        <div class="card-body">
                                            <table class="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Année</th>
                                                        <th>Max Salaire</th>
                                                        <th>Taux Employé (%)</th>
                                                        <th>Part Employeur</th>
                                                        <th>Cotisation max Employé</th>
                                                        <th>Cotisation max Employeur</th>
                                                        <th>Cotisation à l'heure</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @forelse ($generalContributions as $contribution)
        @if($contribution->ae_max_salary)
            <tr>
                <td>{{ $contribution->year }}</td>
                <td>{{ $contribution->ae_max_salary ?? '-' }}$</td>
                <td>{{ $contribution->ae_rate_employee ?? '-' }}%</td>
                <td>{{ $contribution->ae_rate_employer ?? '-' }}</td>
                <td>{{ $contribution->ae_max_employee ?? '-' }}$</td>
                <td>{{ $contribution->ae_max_employer ?? '-' }}$</td>
                <td>{{ $contribution->ae_hourly_contribution ?? '-' }}$</td>
                <td>
                    <a href="{{ route('user.fardeauMO.contributions.show', $contribution->id) }}">
                        <i class="mr-50 fas fa-eye"></i>
                    </a>
                </td>
            </tr>
        @endif
    @empty
        <tr>
            <td colspan="8" class="text-center">Aucune contribution AE disponible</td>
        </tr>
    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- 🔹 RQAP -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#rqapCollapse">
                                            🔹 RQAP (Assurance Parentale)
                                        </button>
                                    </div>
                                    <div id="rqapCollapse" class="collapse">
                                        <div class="card-body">
                                            <table class="table ">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Année</th>
                                                        <th>Max Salaire</th>
                                                        <th>Taux Employeur (%)</th>
                                                        <th>Cotisation max</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($generalContributions as $contribution)
                                            
                                                        @if($contribution->rqap_max_salary)
                                                            <tr>
                                                                <td>{{ $contribution->year }}</td>
                                                                <td>{{ $contribution->rqap_max_salary ?? '-' }}$</td>
                                                                <td>{{ $contribution->rqap_rate_employer ?? '-' }}%</td>
                                                                <td>{{ $contribution->rqap_max_contribution ?? '-'  }}$</td>
                                                                <td>
                                                                    <a href="{{ route('user.fardeauMO.contributions.show', $contribution->id) }}">
                                                                        <i class="mr-50 fas fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                     @empty
                                                        <tr>
                                                         <td colspan="8" class="text-center">Aucune contribution RQAP disponible</td>
                                                        </tr>
                                                    @endforelse

                                                    
                                                </tbody>
                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                 <!-- 🔹 CNT -->
                                 <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#cntCollapse">
                                            🔹 CNT (Cotisation aux Normes du Travail)
                                        </button>
                                    </div>
                                    <div id="cntCollapse" class="collapse" data-parent="#contributionsAccordion">
                                        <div class="card-body">
                                            <table class="table ">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Année</th>
                                                        <th>Max Salaire</th>
                                                        <th>Taux CNT (%)</th>
                                                        <th>Cotisation max</th>
                                                        <th>Cotisation CNT à l'heure</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($generalContributions as $contribution)
                                                        @if($contribution->cnt_max_salary)
                                                            <tr>
                                                                <td>{{ $contribution->year }}</td>
                                                                <td>{{ $contribution->cnt_max_salary ?? '-'  }}$</td>
                                                                <td>{{ $contribution->cnt_rate ?? '-'  }}%</td>
                                                                <td>{{ $contribution->cnt_max_contribution ?? '-'  }}$</td>
                                                                <td>{{ $contribution->cnt_hourly_contribution  ?? '-' }}$</td>
                                                                <td>
                                                                    <a href="{{ route('user.fardeauMO.contributions.show', $contribution->id) }}">
                                                                        <i class="mr-50 fas fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @empty
                                                         <tr>
                                                         <td colspan="8" class="text-center">Aucune contribution CNT disponible</td>
                                                         </tr>
 
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔹 FSSQ -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#fssqCollapse">
                                            🔹 FSSQ (Fonds des Services de Santé du Québec)
                                        </button>
                                    </div>
                                    <div id="fssqCollapse" class="collapse" data-parent="#contributionsAccordion">
                                        <div class="card-body">
                                            <table class="table ">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Année</th>
                                                        <th>Taux FSSQ (%)</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($generalContributions as $contribution)
                                                        @if($contribution->fss_rate)
                                                            <tr>
                                                                <td>{{ $contribution->year }}</td>
                                                                <td>{{ $contribution->fss_rate ?? '-' }}%</td>
                                                                <td>
                                                                    <a href="{{ route('user.fardeauMO.contributions.show', $contribution->id) }}">
                                                                        <i class="mr-50 fas fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                       
    @empty
        <tr>
            <td colspan="8" class="text-center">Aucune contribution FSSQ disponible</td>
        </tr>
   
                                                    @endforelse

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔹 CSST (Modifiable par l'utilisateur) -->
                                <div class="card">
                                    <div class="card-header">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#csstCollapse">
                                            🔹 CSST (Commission de la santé et de la sécurité du travail)
                                        </button>
                                    </div>
                                    <div id="csstCollapse" class="collapse">
                                        <div class="card-body">
                                            <table class="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Année</th>
                                                        <th>Taux CSST (%)</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
    @if ($csstContribution)
        <tr>
            <td>{{ $csstContribution->year }}</td>
            <td>{{ $csstContribution->csst_rate }}%</td>
            <td>
                <a href="{{ route('user.fardeauMO.contributions.show', $csstContribution->id) }}">
                    <i class="mr-50 fas fa-eye"></i>
                </a>
                <a href="{{ route('user.fardeauMO.contributions.edit', $csstContribution->id) }}">
                    <i class="mr-50 fas fa-edit"></i>
                </a>
            </td>
        </tr>
    @else
        <tr>
            <td colspan="3" class="text-center">Aucune contribution CSST enregistrée</td>
        </tr>
    @endif
</tbody>

                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                

                            </div> <!-- FIN Accordéon -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
