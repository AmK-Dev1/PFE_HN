<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Entêtes d'activités</h4>
        <button class="btn btn-primary btn-sm" onclick="addEnteteRow()">+ Ajouter</button>
    </div>
    <div class="card-body">
        <form id="enteteForm">
            @csrf
            @include('user.fardeauMO.partials.entete-table')
        </form>
    </div>
</div>
