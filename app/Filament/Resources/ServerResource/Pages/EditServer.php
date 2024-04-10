<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\Allocation;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;

class EditServer extends EditRecord
{
    protected static string $resource = ServerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->columns(6)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Display Name')
                    ->columnSpan(4)
                    ->required()
                    ->maxLength(191),

                Forms\Components\Select::make('owner_id')
                    ->label('Owner')
                    ->columnSpan(2)
                    ->relationship('user', 'username')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('allocation_id')
                    ->columnSpan(2)
                    ->label('Primary Allocation')
                    ->relationship('allocation', 'port')
                    ->getOptionLabelFromRecordUsing(fn (Allocation $allocation) =>
                        "$allocation->ip:$allocation->port" .
                        ($allocation->ip_alias ? " ($allocation->ip_alias)" : '')
                    )
                    ->searchable(['ip', 'port', 'ip_alias'])
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->hidden()
                    ->default('')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('memory')
                    ->label('Allocated Memory')
                    ->suffix('MB')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('swap')
                    ->label('Allocated Swap')
                    ->suffix('MB')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('disk')
                    ->label('Disk Space Limit')
                    ->suffix('MB')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('io')
                    ->columnSpan(2)
                    ->label('Block IO Proportion')
                    ->hint('Advanced')
                    ->hintColor('danger')
                    ->required()
                    ->minValue(10)
                    ->maxValue(1000)
                    ->step(10)
                    ->default(500)
                    ->numeric(),

                Forms\Components\TextInput::make('cpu')
                    ->columnSpan(1)
                    ->label('CPU Limit')
                    ->suffix('%')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('threads')
                    ->hidden()
                    ->columnSpan(2)
                    ->hint('Advanced')
                    ->hintColor('danger')
                    ->helperText('Examples: 0, 0-1,3, or 0,1,3,4')
                    ->label('CPU Pinning')
                    ->suffixIcon('tabler-cpu')
                    ->maxLength(191),

                Forms\Components\ToggleButtons::make('skip_scripts')
                    ->label('Run Egg Install Script?')
                    ->columnSpan(1)
                    ->options([
                        false => 'Yes',
                        true => 'Skip',
                    ])
                    ->colors([
                        false => 'primary',
                        true => 'danger',
                    ])
                    ->inline()
                    ->required(),

                Forms\Components\ToggleButtons::make('oom_disabled')
                    ->columnSpan(2)
                    ->label('OOM Killer')
                    ->inline()
                    ->options([
                        false => 'Disabled',
                        true => 'Enabled',
                    ])
                    ->colors([
                        false => 'success',
                        true => 'danger',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('startup')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('image')
                    ->label('Docker Image')
                    ->columnSpan(2)
                    ->required(),

                Forms\Components\TextInput::make('allocation_limit')
                    ->label('Allocation Limit')
                    ->numeric(),

                Forms\Components\TextInput::make('database_limit')
                    ->label('Database Limit')
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('backup_limit')
                    ->label('Backup Limit')
                    ->required()
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('external_id')
                    ->maxLength(191)
                    ->hidden(),

            ]);
    }
}