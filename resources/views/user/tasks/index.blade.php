@extends('user.layouts.app')

@section('title', 'Suivi des Tâches')

@section('content')
<style>
.blink-red {
    animation: blink-red 1s infinite;
    color: #842029;
    font-weight: bold;
}
@keyframes blink-red {
    0%, 100% { background-color: #f8d7da; }
    50% { background-color: #f1aeb5; }
}
textarea.auto-expand {
    overflow: hidden;
    resize: none;
    min-height: 38px;
    width: 100%;
}
</style>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <h2 class="content-header-title">Suivi des Tâches</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Suivi des Tâches</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content-body">

            <form method="GET" action="{{ route('user.tasks.index') }}">
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <label for="filter_responsible" class="form-label">Responsable</label>
                    <select name="responsible" id="filter_responsible" class="form-select form-select-lg" style="min-width: 100%; height: 42px;">
                        <option value="">Tous</option>
                        @foreach($responsibles as $responsible)
                            <option value="{{ $responsible }}" {{ request('responsible') == $responsible ? 'selected' : '' }}>
                                {{ ucfirst($responsible) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="filter_status" class="form-label">Statut</label>
                    <select name="status" id="filter_status" class="form-select form-select-lg" style="min-width: 100%; height: 42px;">
                        <option value="">Tous</option>
                        <option value="À faire" {{ request('status') == 'À faire' ? 'selected' : '' }}>À faire</option>
                        <option value="En cours" {{ request('status') == 'En cours' ? 'selected' : '' }}>En cours</option>
                        <option value="Terminé" {{ request('status') == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end justify-content-end gap-2">
                    <button type="submit" class="btn btn-success btn-sm" style="height: 38px; min-width: 120px;">Filtrer</button>
                    <a href="{{ route('user.tasks.index') }}" class="btn btn-secondary btn-sm" style="height: 38px; min-width: 120px;">Clear</a>
                </div>
            </div>
        </div>
    </div>
</form>



            {{-- 📋 Tableau des tâches --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Tableau de gestion des tâches</h4>
                    <div>
                        <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">+ Ajouter une tâche</button>
                        <form id="massDeleteForm" method="POST" action="{{ route('user.tasks.massDelete') }}" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer les tâches sélectionnées ?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer sélection</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form id="taskForm" method="POST" action="{{ route('user.tasks.store') }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table text-center align-middle" style="border-collapse: collapse; border: none;">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Date de réunion</th>
                                        <th>Type de réunion</th>
                                        <th>Référence</th>
                                        <th style="min-width:200px;">Tâche</th>
                                        <th>Responsable</th>
                                        <th style="min-width:250px;">Email</th>
                                        <th>Date limite</th>
                                        <th style="min-width:140px;">Statut</th>
                                        <th style="min-width:200px;">Commentaires</th>
                                        <th>⚠</th>
                                    </tr>
                                </thead>
                                <tbody id="taskTableBody">
                                    @foreach($tasks as $index => $task)
                                    @php
                                        $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast();
                                        $autoStatus = $isOverdue && $task->status != 'Terminé' ? 'En cours' : $task->status;
                                        $statusColor = match($autoStatus) {
                                            'À faire' => '#f8d7da',
                                            'En cours' => '#fff3cd',
                                            'Terminé' => '#d4edda',
                                            default => '#ffffff'
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{ $task->id }}" form="massDeleteForm">
                                            <input type="hidden" name="task_id[]" value="{{ $task->id }}">
                                        </td>
                                        <td><input type="date" name="meeting_date[]" class="form-control form-control-sm" value="{{ $task->meeting_date }}"></td>
                                        <td><input type="text" name="meeting_type[]" class="form-control form-control-sm" value="{{ $task->meeting_type }}"></td>
                                        <td><input type="text" name="reference[]" class="form-control form-control-sm" value="#{{ str_pad($index + 1, 4, '0', STR_PAD_LEFT) }}" readonly></td>
                                        <td><textarea name="task[]" class="form-control form-control-sm auto-expand">{{ $task->task }}</textarea></td>
                                        <td><input type="text" name="responsible_name[]" class="form-control form-control-sm" value="{{ $task->responsible_name }}"></td>
                                        <td><input type="email" name="responsible_email[]" class="form-control form-control-sm" value="{{ $task->responsible_email }}"></td>
                                        <td><input type="date" name="due_date[]" class="form-control form-control-sm" value="{{ $task->due_date }}"></td>
                                        <td>
                                            <select name="status[]" class="form-control form-control-sm {{ $isOverdue && $autoStatus != 'Terminé' ? 'blink-red' : '' }}" style="background-color: {{ $statusColor }}">
                                                <option value="À faire" {{ $autoStatus == 'À faire' ? 'selected' : '' }}>À faire</option>
                                                <option value="En cours" {{ $autoStatus == 'En cours' ? 'selected' : '' }}>En cours</option>
                                                <option value="Terminé" {{ $autoStatus == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                                            </select>
                                        </td>
                                        <td><textarea name="comments[]" class="form-control form-control-sm auto-expand">{{ $task->comments }}</textarea></td>
                                        <td>
                                            @if($task->status === 'En cours' && $task->mail_sent)
                                                <span class="badge bg-danger">⏰</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-success">Enregistrer toutes les lignes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('checkAll').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

document.getElementById('addRow').addEventListener('click', function () {
    const tbody = document.getElementById('taskTableBody');
    const rowCount = tbody.rows.length + 1;
    const reference = `#${String(rowCount).padStart(4, '0')}`;
    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td><input type="hidden" name="task_id[]" value=""></td>
        <td><input type="date" name="meeting_date[]" class="form-control form-control-sm"></td>
        <td><input type="text" name="meeting_type[]" class="form-control form-control-sm"></td>
        <td><input type="text" name="reference[]" class="form-control form-control-sm" value="${reference}" readonly></td>
        <td><textarea name="task[]" class="form-control form-control-sm auto-expand"></textarea></td>
        <td><input type="text" name="responsible_name[]" class="form-control form-control-sm"></td>
        <td><input type="email" name="responsible_email[]" class="form-control form-control-sm"></td>
        <td><input type="date" name="due_date[]" class="form-control form-control-sm"></td>
        <td>
            <select name="status[]" class="form-control form-control-sm">
                <option value="À faire" style="background-color:#f8d7da">À faire</option>
                <option value="En cours" style="background-color:#fff3cd">En cours</option>
                <option value="Terminé" style="background-color:#d4edda">Terminé</option>
            </select>
        </td>
        <td><textarea name="comments[]" class="form-control form-control-sm auto-expand"></textarea></td>
        <td></td>
    `;
    tbody.appendChild(tr);
});

document.addEventListener('input', function (e) {
    if (e.target.tagName.toLowerCase() === 'textarea' && e.target.classList.contains('auto-expand')) {
        e.target.style.height = 'auto';
        e.target.style.height = e.target.scrollHeight + 'px';
    }
});
</script>
@endsection
