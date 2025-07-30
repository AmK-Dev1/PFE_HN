<tr data-id="{{ $entete->id ?? 'new' }}">
    <td><input type="number" class="form-control form-control-sm" name="annee" value="{{ $entete->annee ?? '' }}"></td>
    <td><input type="number" class="form-control form-control-sm" name="jours_feries" value="{{ $entete->jours_feries ?? '' }}"></td>
    <td><input type="number" class="form-control form-control-sm" name="minutes_pause" value="{{ $entete->minutes_pause ?? '' }}" onchange="calculateEntetePercentages(this)"></td>
    <td><input type="number" class="form-control form-control-sm" name="minutes_temps_mort" value="{{ $entete->minutes_temps_mort ?? '' }}" onchange="calculateEntetePercentages(this)"></td>
    <td><input type="number" class="form-control form-control-sm" name="pourcentage_pause" value="{{ $entete->pourcentage_pause ?? '' }}" readonly></td>
    <td><input type="number" class="form-control form-control-sm" name="pourcentage_temps_mort" value="{{ $entete->pourcentage_temps_mort ?? '' }}" readonly></td>
    <td>
        <div class="d-flex justify-content-center gap-1">
            <button class="btn btn-success btn-sm btn-save-entete" title="Enregistrer">
                <i class="fas fa-save"></i>
            </button>
            <button class="btn btn-danger btn-sm btn-delete-entete" title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
