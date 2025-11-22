<?php

namespace App\Policies;

use App\Models\Konsultasi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KonsultasiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Konsultasi $konsultasi)
    {
        // Admin can view all consultations
        if ($user->is_admin) {
            return true;
        }

        // Users can only view their own consultations
        return $user->id === $konsultasi->user_id;
    }

    public function delete(User $user, Konsultasi $konsultasi)
    {
        // Admin can view all consultations
        if ($user->is_admin) {
            return true;
        }

        // Users can only view their own consultations
        return $user->id === $konsultasi->user_id;
    }
}
