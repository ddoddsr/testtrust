<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Department;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ServiceHoursRelationManager extends RelationManager
{
    protected static string $relationship = 'ServiceHours';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 'department_id'
                Select::make('department_id')
                ->label('Department')
                ->options(Department::all()
                ->pluck('name', 'id'))
                ->searchable(),
                Forms\Components\TextInput::make('hours')
                ->required()
                ->maxLength(255),
                
                Select::make('supervisor_id')
                ->label('Supervisor lookup')
                ->options(User::all()
                ->pluck('name_and_email', 'id'))
                ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('department.name'),
                Tables\Columns\TextColumn::make('hours'),
                TextColumn::make('supervisor.name')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
