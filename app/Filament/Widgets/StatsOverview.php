<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEmployees = User::all()->count();
        $totalHolidays = Holiday::all()->count();
        $totalTimsheets = Timesheet::all()->count();
        return [
            Stat::make('Employees', $totalEmployees)
            ->description('32k increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Holidays', $totalHolidays),
            Stat::make('Timesheets', $totalTimsheets),
        ];
    }
}
