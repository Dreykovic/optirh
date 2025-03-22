<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Models\OptiHr\Employee;

class EmployeeObserver
{
    /**
     * Handle the Employee "created" event.
     */
    public function created(Employee $employee): void
    {
        Log::info('Observer called for employee: ' . $employee->first_name);
        //
        // Générer les initiales à partir du prénom et du nom
        $initials = strtoupper(substr($employee->last_name, 0, 1)) . strtoupper(substr($employee->first_name, 0, 1));

        // Rechercher les employés avec le même préfixe
        $latestEmployeeWithSameInitials = Employee::where('code', 'LIKE', "$initials-%")
            ->orderBy('code', 'desc')
            ->first();

        // Déterminer le prochain numéro séquentiel
        $nextSequence = 1;
        if ($latestEmployeeWithSameInitials) {
            $latestCode = $latestEmployeeWithSameInitials->code;
            $latestSequence = (int) substr($latestCode, strpos($latestCode, '-') + 1);
            $nextSequence = $latestSequence + 1;
        }

        // Formater le code final (exemple : JD-001)
        $employee->code = $initials . '-' . str_pad($nextSequence, 2, '0', STR_PAD_LEFT);
        $employee->save();
    }


    /**
     * Handle the Employee "updated" event.
     */
    public function updated(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "deleted" event.
     */
    public function deleted(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "restored" event.
     */
    public function restored(Employee $employee): void
    {
        //
    }

    /**
     * Handle the Employee "force deleted" event.
     */
    public function forceDeleted(Employee $employee): void
    {
        //
    }
}
