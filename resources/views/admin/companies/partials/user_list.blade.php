@if($users->isEmpty())
    <p>Aucun utilisateur trouvé.</p>
@else
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        <input type="checkbox" id="user_{{ $user->id }}" name="users[]" value="{{ $user->id }}">
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
