<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Notifications\DueDateApproachingNotification;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $responsibles = Task::select('responsible_name')->distinct()->pluck('responsible_name');

    $tasks = Task::query();

    if ($request->filled('responsible')) {
        $tasks->where('responsible_name', $request->responsible);
    }

    if ($request->filled('status')) {
        $tasks->where('status', $request->status);
    }

    $tasks = $tasks->get();

    return view('user.tasks.index', compact('tasks', 'responsibles'));
}

public function store(Request $request)
{
    $count = count($request->input('task'));
    $taskIds = $request->input('task_id');

    for ($i = 0; $i < $count; $i++) {
        // Ignorer les lignes vides
        if (empty($request->task[$i]) || empty($request->responsible_email[$i])) {
            continue;
        }

        // Préparation des données
        $data = [
            'meeting_date'      => $request->meeting_date[$i] ?? null,
            'meeting_type'      => $request->meeting_type[$i] ?? null,
            'reference'         => $request->reference[$i] ?? null,
            'task'              => $request->task[$i],
            'responsible_name'  => $request->responsible_name[$i] ?? null,
            'responsible_email' => $request->responsible_email[$i],
            'due_date'          => $request->due_date[$i] ?? null,
            'status'            => $request->status[$i] ?? 'À faire',
            'comments'          => $request->comments[$i] ?? null,
        ];

        // Si la tâche existe, on la met à jour
        if (!empty($taskIds[$i])) {
            $task = Task::find($taskIds[$i]);
            if ($task) {
                $task->update($data);
            }
        } else {
            // Sinon, on la crée
            $task = Task::create($data);
        }

        // Envoi mail si < 48h et statut ≠ Terminé
        if (
            $task->due_date &&
            now()->diffInHours($task->due_date, false) <= 48 &&
            $task->status !== 'Terminé' &&
            !$task->mail_sent
        ) {
            Notification::route('mail', $task->responsible_email)
                ->notify(new DueDateApproachingNotification($task));

            $task->mail_sent = true;
            $task->save();
        }
    }

    return redirect()->route('user.tasks.index')->with('success', 'Les tâches ont été enregistrées et mises à jour avec succès.');
}





    public function update(Request $request, Task $task)
    {
        $task->update($request->all());
        return redirect()->route('user.tasks.index');
    }

    public function delete(Request $request)
    {
        Task::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => 'Tâches supprimées']);
    }
    public function massDelete(Request $request)
{
    if ($request->has('ids')) {
        Task::whereIn('id', $request->ids)->delete();
        return redirect()->route('user.tasks.index')->with('success', 'Les tâches sélectionnées ont été supprimées.');
    }

    return redirect()->route('user.tasks.index')->with('error', 'Aucune tâche sélectionnée.');
}
}
