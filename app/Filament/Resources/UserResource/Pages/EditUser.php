<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        
        // Obtén el registro del usuario actual
        $user = $this->record;

        // Asegúrate de que el campo 'role' está presente en los datos del formulario
        $role = $this->form->getState()['role'] ?? null; // Cambia 'role_id' según tu configuración

        if ($role) {
            // Sincroniza el rol del usuario
            $user->syncRoles([$role]);
        }
    }

}
