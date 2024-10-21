<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {

        // Obtén el registro del usuario recién creado
        $user = $this->record;

        // Asegúrate de que el campo 'role' está presente en los datos del formulario
        $role = $this->form->getState()['role'] ?? null; // Cambia 'role_id' según tu configuración

        if ($role) {
            // Asigna el rol al usuario
            $user->syncRoles([$role]); // Usa assignRole para asignar el rol
        }
    }

}
