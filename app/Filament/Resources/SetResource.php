<?php

namespace App\Filament\Resources;

use App\Models\Set;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
// use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SetResource\Pages;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
// use App\Filament\Resources\SetResource\RelationManagers;

class SetResource extends Resource
{
    protected static ?string $model = Set::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    // TODO show for super_admin Hides from admin menu 
    protected static bool $shouldRegisterNavigation = false;
    
    public static function form(Form $form): Form
    {
        return $form
        ->columns(3)
        ->schema([
            // Forms\Components\TextInput::make('sequence')->disabled()->dehydrated(false),
            Forms\Components\TextInput::make('dayOfWeek')->disabled()->dehydrated(false)
                ->maxLength(10),
            Forms\Components\TextInput::make('setOfDay')->disabled()->dehydrated(false)
                ->maxLength(10),
            Forms\Components\TextInput::make('location')->disabled()->dehydrated(false)
                ->maxLength(24),
            
            Select::make('section_leader_id')
                ->label('Section Leader')
                ->options(User::all()
                 ->pluck('full_name', 'id'))
                ->searchable(),
            Select::make('worship_leader_id')
                ->label('Worship Leader')
                ->options(User::all()
                ->pluck('full_name', 'id'))
                ->searchable(),
            Select::make('associate_worship_leader_id')
                ->label('Associate Worship Leader')
                ->options(User::all()
                ->pluck('full_name', 'id'))
                ->searchable(),
            Select::make('prayer_leader_id')
                ->label('Prayer Leader')
                ->options(User::all()
                ->pluck('full_name', 'id'))
                ->searchable(),
            Forms\Components\TextInput::make('title')
                ->maxLength(24),
            // Forms\Components\Toggle::make('active')->disabled()->dehydrated(false)
            //     ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            // Tables\Columns\TextColumn::make('sequence'),
            TextColumn::make('dayOfWeek')->sortable(),
            TextColumn::make('setOfDay')->sortable(),
            TextColumn::make('location')->sortable(),
            TextColumn::make('sectionLeader.full_name')->searchable(),
            TextColumn::make('worshipLeader.full_name')->searchable(),
            TextColumn::make('associateWorshipLeader.full_name')->searchable(),
            TextColumn::make('prayerLeader.full_name')->searchable(),
            Tables\Columns\TextColumn::make('title')->sortable(),
            // Tables\Columns\IconColumn::make('active')
            //     ->boolean()->sortable(),
            // Tables\Columns\TextColumn::make('created_at')
                // ->dateTime(),
            // Tables\Columns\TextColumn::make('updated_at')
                // ->dateTime(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSets::route('/'),
            'create' => Pages\CreateSet::route('/create'),
            'edit' => Pages\EditSet::route('/{record}/edit'),
        ];
    }    
}
