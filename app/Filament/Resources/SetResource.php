<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetResource\Pages;
use App\Filament\Resources\SetResource\RelationManagers;
use App\Models\Set;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SetResource extends Resource
{
    protected static ?string $model = Set::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('sequence')->disabled()->dehydrated(false),
            Forms\Components\TextInput::make('dayOfWeek')->disabled()->dehydrated(false)
                ->maxLength(10),
            Forms\Components\TextInput::make('setOfDay')->disabled()->dehydrated(false)
                ->maxLength(10),
            Forms\Components\TextInput::make('location')->disabled()->dehydrated(false)
                ->maxLength(24),
            Forms\Components\TextInput::make('sectionLeader')->autofocus()
                ->maxLength(24),
            Forms\Components\TextInput::make('worshipLeader')
                ->maxLength(24),
            Forms\Components\TextInput::make('prayerLeader')
                ->maxLength(24),
            Forms\Components\TextInput::make('title')
                ->maxLength(24),
            Forms\Components\Toggle::make('active')->disabled()->dehydrated(false)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            // Tables\Columns\TextColumn::make('sequence'),
            Tables\Columns\TextColumn::make('dayOfWeek')->sortable(),
            Tables\Columns\TextColumn::make('setOfDay')->sortable(),
            Tables\Columns\TextColumn::make('location')->sortable(),
            Tables\Columns\TextColumn::make('sectionLeader')->sortable(),
            Tables\Columns\TextColumn::make('worshipLeader')->sortable(),
            Tables\Columns\TextColumn::make('prayerLeader')->sortable(),
            Tables\Columns\TextColumn::make('title')->sortable(),
            Tables\Columns\IconColumn::make('active')
                ->boolean()->sortable(),
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
