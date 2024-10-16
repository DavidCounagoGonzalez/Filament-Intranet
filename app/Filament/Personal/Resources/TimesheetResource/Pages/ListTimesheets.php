<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Filament\Personal\Resources\TimesheetResource;
use App\Models\Timesheet;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        if($lastTimesheet == null){
            return [
            Action::make('inWork')
            ->label('Entrar a trabajar')
            ->color('success')
            //->keyBindings(['command+s', 'ctrl+s']) //asignar un comando para realizar la acción
            ->requiresConfirmation()
            ->action(function(){
                $user = Auth::user();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = $user->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->day_out= '';
                $timesheet->type = 'work';
                $timesheet->save();
            }),
            Actions\CreateAction::make(),
            ];
        }
        return [
            Action::make('inWork')
            ->label('Entrar a trabajar')
            ->color('success')
            //->keyBindings(['command+s', 'ctrl+s']) //asignar un comando para realizar la acción
            ->visible(!$lastTimesheet->day_out == null)
            ->disabled($lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function(){
                $user = Auth::user();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = $user->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->day_out= '';
                $timesheet->type = 'work';
                $timesheet->save();

                Notification::make()
                ->title('Has entrado a trabajar')
                ->body('Has comenzando a trabajar a las '.Carbon::now())
                ->color('success')
                ->success()
                ->send();
            }),
            Action::make('stopWork')
            ->label('Parar de trabajar')
            ->color('success')
            //->keyBindings(['command+s', 'ctrl+s']) //asignar un comando para realizar la acción
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type!='pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function() use ($lastTimesheet){
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();

                Notification::make()
                ->title('Has parado de trabajar')
                ->color('success')
                ->success()
                ->send();
            }),

            Action::make('inPause')
            ->label('Comenzar Pausa')
            ->color('info')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type!='pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function () use ($lastTimesheet){
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->day_out= '';
                $timesheet->type = 'pause';
                $timesheet->save();

                Notification::make()
                ->title('Comienzas tu descanso')
                ->info()
                ->send();
            }),
            Action::make('stopPause')
            ->label('Terminar Pausa')
            ->color('info')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type=='pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function () use ($lastTimesheet){
                $lastTimesheet->day_out = Carbon::now();
                $lastTimesheet->save();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon::now();
                $timesheet->day_out= '';
                $timesheet->type = 'work';
                $timesheet->save();

                Notification::make()
                ->title('Terminas tu descanso')
                ->info()
                ->send();
            }),
            Actions\CreateAction::make(),
        ];
    }
}
