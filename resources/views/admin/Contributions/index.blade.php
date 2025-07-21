@extends('admin.layouts.app')

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
                            <a href="{{ route('admin.dashboard') }}">{{ __('labels.fields.dashboard') }}</a>
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
                            <a href="{{ route('admin.contributions.create') }}" class="btn btn-outline-primary">
                                <i data-feather="plus"></i> Ajouter une contribution
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
                                            <table class="table ">
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
                                                    @foreach ($contributions as $contribution)
                                                        @if($contribution->rrq_max_salary)
                                                            <tr>
                                                                <td>{{ $contribution->year }}</td>
                                                                <td>{{ number_format($contribution->rrq_max_salary,2) }}$</td>
                                                                <td>{{ number_format($contribution->rrq_exemption, 2)}}$</td>
                                                                <td>{{ number_format($contribution->rrq_max_gains,2)}}$</td>
                                                                <td>{{ number_format($contribution->rrq_max_contribution,2)}}$</td>
                                                                <td>{{ number_format($contribution->rrq_hourly_exemption,2) }}$</td>
                                                                <td>{{ number_format($contribution->rrq_hourly_contribution, 2) }}$</td>
                                                                <td>{{ number_format($contribution->taux_de_cotisation_rrq,2)}}%</td>
                                                                
                                                            <td>
                                                                
                                                                       <!-- Icône Voir -->
                                                                        <a href="{{ route('admin.contributions.show', $contribution->id) }}" title="Voir">
                                                                          <i class="fas fa-eye text-primary"></i>
                                                                          </a>

                                                                        <!-- Icône Modifier -->
                                                                       <a href="{{ route('admin.contributions.edit', $contribution->id) }}" title="Modifier">
                                                                        <i class="fas fa-edit text-primary"></i>
                                                                       </a>

                                                                       <!-- Icône Supprimer -->
                                                                      <form action="{{ route('admin.contributions.destroy', $contribution->id) }}" method="POST" class="d-inline">
                                                                          @csrf @method('DELETE')
                                                                          <input type="hidden" name="delete_rrq" value="1"> <!-- 🔹 Identifie le type de contribution -->
                                                                         <button type="submit" class="border-0 bg-transparent text-primary">
                                                                         <i class="fas fa-trash"></i>
                                                                          </button>
                                                                        </form>
                                                                </td>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
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
                                            <table class="table ">
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
                                                    @foreach ($contributions as $contribution)
                                                        @if($contribution->ae_max_salary)
                                                            <tr>
                                                                <td>{{ $contribution->year }}</td>
                                                                <td>{{ $contribution->ae_max_salary }}$</td>
                                                                <td>{{ $contribution->ae_rate_employee }}%</td>
                                                                <td>{{ $contribution->ae_rate_employer }}</td>
                                                                <td>{{ $contribution->ae_max_employee }}$</td>
                                                                <td>{{ $contribution->ae_max_employer }}$</td>
                                                                <td>{{ $contribution->ae_hourly_contribution }}$</td>
                                                                <td>
                                                                    <!-- Icône Voir -->
                                                                    <a href="{{ route('admin.contributions.show', $contribution->id) }}" title="Voir">
                                                                          <i class="fas fa-eye text-primary"></i>
                                                                          </a>

                                                                        <!-- Icône Modifier -->
                                                                       <a href="{{ route('admin.contributions.edit', $contribution->id) }}" title="Modifier">
                                                                        <i class="fas fa-edit text-primary"></i>
                                                                       </a>

                                                                       <!-- Icône Supprimer -->
                                                                      <form action="{{ route('admin.contributions.destroy', $contribution->id) }}" method="POST" class="d-inline">
                                                                          @csrf @method('DELETE')
                                                                          <input type="hidden" name="delete_ae" value="1"> <!-- 🔹 Identifie le type de contribution -->
                                                                         <button type="submit" class="border-0 bg-transparent text-primary">
                                                                         <i class="fas fa-trash"></i>
                                                                          </button>
                                                                        </form>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
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
                                                        <th>Taux Employee (%)</th>
                                                        <th>Cotisation max</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($contributions as $contribution)
                                                        @if($contribution->rqap_max_salary)
                                                            <tr>
                                                                <td>{{ $contribution->year }}</td>
                                                                <td>{{ $contribution->rqap_max_salary }}$</td>
                                                                <td>{{ $contribution->rqap_rate_employee }}%</td>
                                                                <td>{{ $contribution->rqap_max_contribution }}$</td>
                                                                <td>
                                                                    <!-- Icône Voir -->
                                                                    <a href="{{ route('admin.contributions.show', $contribution->id) }}" title="Voir">
                                                                          <i class="fas fa-eye text-primary"></i>
                                                                          </a>

                                                                        <!-- Icône Modifier -->
                                                                       <a href="{{ route('admin.contributions.edit', $contribution->id) }}" title="Modifier">
                                                                        <i class="fas fa-edit text-primary"></i>
                                                                       </a>

                                                                       <!-- Icône Supprimer -->
                                                                      <form action="{{ route('admin.contributions.destroy', $contribution->id) }}" method="POST" class="d-inline">
                                                                          @csrf @method('DELETE')
                                                                          <input type="hidden" name="delete_rqap" value="1"> <!-- 🔹 Identifie le type de contribution -->
                                                                           
                                                                         <button type="submit" class="border-0 bg-transparent text-primary">
                                                                         <i class="fas fa-trash"></i>
                                                                          </button>
                                                                        </form>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
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
                                                    @foreach ($contributions as $contribution)
                                                        @if($contribution->cnt_max_salary)
                                                            <tr>
                                                                <td>{{ $contribution->year }}</td>
                                                                <td>{{ $contribution->cnt_max_salary }}$</td>
                                                                <td>{{ $contribution->cnt_rate }}%</td>
                                                                <td>{{ $contribution->cnt_max_contribution }}$</td>
                                                                <td>{{ $contribution->cnt_hourly_contribution }}$</td>
                                                                <td>
                                                                    <!-- Icône Voir -->
                                                                    <a href="{{ route('admin.contributions.show', $contribution->id) }}" title="Voir">
                                                                          <i class="fas fa-eye text-primary"></i>
                                                                          </a>

                                                                        <!-- Icône Modifier -->
                                                                       <a href="{{ route('admin.contributions.edit', $contribution->id) }}" title="Modifier">
                                                                        <i class="fas fa-edit text-primary"></i>
                                                                       </a>

                                                                       <!-- Icône Supprimer -->
                                                                      <form action="{{ route('admin.contributions.destroy', $contribution->id) }}" method="POST" class="d-inline">
                                                                          @csrf
                                                                          @method('DELETE')
                                                                          <input type="hidden" name="delete_cnt" value="1"> <!-- 🔹 Identifie le type de contribution -->

                                                                         <button type="submit" class="border-0 bg-transparent text-primary">
                                                                         <i class="fas fa-trash"></i>
                                                                          </button>
                                                                        </form>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
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
                                                    @foreach ($contributions as $contribution)
                                                        @if($contribution->fss_rate)
                                                            <tr>
                                                                <td>{{ $contribution->year }}</td>
                                                                <td>{{ $contribution->fss_rate }}%</td>
                                                                <td>
                                                                    <!-- Icône Voir -->
                                                                    <a href="{{ route('admin.contributions.show', $contribution->id) }}" title="Voir">
                                                                          <i class="fas fa-eye text-primary"></i>
                                                                          </a>

                                                                        <!-- Icône Modifier -->
                                                                       <a href="{{ route('admin.contributions.edit', $contribution->id) }}" title="Modifier">
                                                                        <i class="fas fa-edit text-primary"></i>
                                                                       </a>

                                                                       <!-- Icône Supprimer -->
                                                                      <form action="{{ route('admin.contributions.destroy', $contribution->id) }}" method="POST" class="d-inline">
                                                                          @csrf 
                                                                          @method('DELETE')
                                                                          <input type="hidden" name="delete_fss" value="1"> <!-- 🔹 Identifie le type de contribution -->

                                                                         <button type="submit" class="border-0 bg-transparent text-primary">
                                                                         <i class="fas fa-trash"></i>
                                                                          </button>
                                                                        </form>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                <div class="card-header">
                                   <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#csstCollapse">
                                   🔹 CSST (Commission des normes, de l'équité, de la santé et de la sécurité du travail)
                                   </button>
                                 </div>
                                 <div id="csstCollapse" class="collapse" data-parent="#contributionsAccordion">
                                 <div class="card-body">
                                    <table class="table ">
                                        <thead class="thead-light">
                                          <tr>
                                          <th>Année</th>
                                          <th>ID Entreprise</th>
                                          <th>Taux CSST (%)</th>
                                          <th>Actions</th>
                                          </tr>
                                        </thead>
                                    <tbody>
                                         @foreach ($contributions as $contribution)
                                            @if($contribution->csst_rate)
                                               <tr>
                                                 <td>{{ $contribution->year }}</td>

                                                 <td>{{ $contribution->company ? $contribution->company->id : 'N/A' }}</td> <!-- Affichage ID -->
                                                 <td>{{ $contribution->csst_rate }}%</td>
                                                 <td>
                                                 <!-- Affichage seulement, pas de modification pour l'admin SaaS -->
                                                 <a href="{{ route('admin.contributions.show', $contribution->id) }}" title="Voir">
                                                                          <i class="fas fa-eye text-primary"></i>
                                                                          </a>
                                                 </td>
                                                </tr>
                                            @endif
                                         @endforeach
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
