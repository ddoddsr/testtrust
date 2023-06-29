<?php

namespace App\Filament\Resources\LocationManagerResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SetsRelationManager extends RelationManager
{
    protected static string $relationship = 'sets';
    protected static ?string $recordTitleAttribute = 'location_id';
    
    protected function getTableRecordsPerPageSelectOptions(): array 
    {
        return [12, 24, 48, 84];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('section_leader_id')
                ->label('Section Leader')
                ->options(User::all()
                  //   ->where('is_section_leader', '=', true)
                  ->pluck('full_name', 'id'))
                ->searchable(),
                Select::make('worship_leader_id')
                ->label('Worship Leader')
                ->options(User::all()
                  //   ->where('is_worship_leader', '=', true)
                  ->pluck('full_name', 'id'))
                ->searchable(),
                Select::make('associate_worship_leader_id')
                ->label('Associate Worship Leader')
                ->options(User::all()
                  // ->where('is_associate_worship_leader', '=', true)
                  ->pluck('full_name', 'id'))
                ->searchable(),
                Select::make('prayer_leader_id')
                ->label('Prayer Leader')
                ->options(User::all()
                  //   ->where('is_prayer_leader', '=', true)
                  ->pluck('full_name', 'id'))
                ->searchable(),
                TextInput::make('title')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('dayOfWeek')->sortable(),
                TextColumn::make('setOfDay')->sortable(),
                // TextColumn::make('location')->sortable(),
                TextColumn::make('sectionLeader.full_name')->searchable(),
                TextColumn::make('worshipLeader.full_name')->searchable(),
                TextColumn::make('associateWorshipLeader.full_name')->searchable(),
                TextColumn::make('prayerLeader.full_name')->searchable(),
                TextColumn::make('title')->sortable(),
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
