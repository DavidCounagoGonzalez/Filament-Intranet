<?php

namespace App\Filament\Personal\Resources\HolidayResource\Pages;

use App\Filament\Personal\Resources\HolidayResource;
use App\Mail\HolidayPending;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        $data['type'] = 'pending';
        $dataToSend = array(
            'day' => $data['day'],
            'name' => User::find($data['user_id'])->name,
            'email' => User::find($data['user_id'])->email,
        );
        $userAdmin = User::find(1);
        Mail::to(($userAdmin))->send(new HolidayPending($dataToSend));

        return $data;
    }
}
