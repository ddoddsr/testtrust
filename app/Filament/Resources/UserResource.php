<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
        // ->columns(1)
        ->schema([
            Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),
        // Tabs::make('Heading')
        //     ->tabs([
        //         Tabs\Tab::make('User Data')
        //             ->schema([
                            // Forms\Components\TextInput::make('firstName')
                            //     ->required()
                            //     ->maxLength(255),
                            // Forms\Components\TextInput::make('lastName')
                            //     ->required()
                            //     ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('designation')
                                ->maxLength(100),
                            Forms\Components\TextInput::make('supervisor')
                                ->maxLength(100),
                            Forms\Components\TextInput::make('superEmail1')
                                ->maxLength(100),
                    //     ]),
                    // Tabs\Tab::make('Sacred Trust Entry')
                    //     ->schema([
                            Forms\Components\TextInput::make('resultId'),
                            Forms\Components\DateTimePicker::make('startDate'),
                            Forms\Components\DateTimePicker::make('finishDate'),
                            Forms\Components\DateTimePicker::make('updateDate'),
                            Forms\Components\TextInput::make('resultStatus')
                                ->maxLength(100),
                        // ]),
                    
                    // ]),
            
                Forms\Components\Toggle::make('active')
                    ->required(),
                    
                Forms\Components\DatePicker::make('effectiveDate'),
            // Forms\Components\DateTimePicker::make('email_verified_at'),
            // Forms\Components\TextInput::make('password')
            //     ->password()
            //     ->required()
            //     ->maxLength(255),
            // Forms\Components\Textarea::make('two_factor_secret')
            //     ->maxLength(65535),
            // Forms\Components\Textarea::make('two_factor_recovery_codes')
            //     ->maxLength(65535),
            // Forms\Components\DateTimePicker::make('two_factor_confirmed_at'),
            // Forms\Components\TextInput::make('current_team_id'),
            // Forms\Components\TextInput::make('profile_photo_path')
                // ->maxLength(2048),
            // Forms\Components\TextInput::make('resultId'),
            // Forms\Components\DateTimePicker::make('startDate'),
            // Forms\Components\DateTimePicker::make('finishDate'),
            // Forms\Components\DateTimePicker::make('updateDate'),
            // Forms\Components\TextInput::make('resultStatus')
            //     ->maxLength(100),
            // Forms\Components\TextInput::make('designation')
            //     ->maxLength(100),
            // Forms\Components\TextInput::make('supervisor')
            //     ->maxLength(100),
            // Forms\Components\TextInput::make('superEmail1')
            //     ->maxLength(100),

            // Forms\Components\Section::make('Roles')->schema([
            //     Forms\Components\CheckboxList::make('roles')->relationship('roles','name'),
            // ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->sortable()->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('last_name')->sortable()->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(isIndividual: true),
                // Tables\Columns\TextColumn::make('email_verified_at')
                //     ->dateTime()->sortable(),
                // Tables\Columns\TextColumn::make('two_factor_secret'),
                // Tables\Columns\TextColumn::make('two_factor_recovery_codes'),
                // Tables\Columns\TextColumn::make('two_factor_confirmed_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('current_team_id')->sortable(),
                // Tables\Columns\TextColumn::make('profile_photo_path')->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()->sortable(),
                Tables\Columns\TextColumn::make('resultId')->sortable(),
                // Tables\Columns\TextColumn::make('startDate')
                //     ->dateTime(),
                Tables\Columns\TextColumn::make('finishDate')
                    ->dateTime(),
                // Tables\Columns\TextColumn::make('updateDate')
                //     ->dateTime(),
                Tables\Columns\TextColumn::make('resultStatus')->sortable(),
                Tables\Columns\TextColumn::make('designation')->sortable(),
                Tables\Columns\TextColumn::make('supervisor')->sortable()->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('superEmail1')->sortable(),
                Tables\Columns\TextColumn::make('effectiveDate')
                    ->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->label(trans('filament-user::user.resource.verified'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->label(trans('filament-user::user.resource.unverified'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
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
            RelationManagers\SchedulesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
