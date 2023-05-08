<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
        // ->columns(1)
        ->schema([
            // Forms\Components\FileUpload::make('profile_photo_path')
            //     ->label(__('fields.images.src'))
            //     ->helperText(__('fields.images.src.helper'))
            //     // ->required()
            //     ->disk('public')
            //     ->directory('profile-photos')
            //     // ->maxSize(env('DRIVER_MEDIA_MAX_UPLOAD_BYTES'))
            //     ->image(),
            Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\DatePicker::make('effectiveDate'),
            Select::make('designationId')
                ->label('DesignationID')
                ->options(User::designations()),
            Select::make('supervisorId')
                ->label('SupervisorID')
                ->options(User::all()
                ->pluck('full_name', 'id'))
                ->searchable(),
            Forms\Components\TextInput::make('designation')
                ->maxLength(100),
                
            Forms\Components\TextInput::make('supervisor')
                ->maxLength(100),
                // Forms\Components\TextInput::make('superEmail1')
                // ->maxLength(100),
                Forms\Components\FileUpload::make('profile_photo_path')
                ->label(__('fields.images.src'))
                ->helperText(__('fields.images.src.helper'))
                // ->required()
                ->disk('public')
                ->directory('profile-photos')
                // ->maxSize(env('DRIVER_MEDIA_MAX_UPLOAD_BYTES'))
                ->image(),
            Tabs::make('Heading')
                ->tabs([
                    Tabs\Tab::make('User Data')->columns(2)
                    ->schema([
                        // photo 
                        Forms\Components\Toggle::make('active')
                            ->required(),
                        Forms\Components\Toggle::make('isSupervisor')
                            ->label('Is Supervisor')->default('false'),
                        Forms\Components\Toggle::make('isSectionLeader')
                            // ->helperText('or Associate Section Leader')
                            ->label('Is Section Leader')->default('false'),
                        Forms\Components\Toggle::make('isWorshipLeader')
                            // ->helperText('or Associate Worship Leader')
                            ->label('Is Worship Leader')->default('false') ,
                        Forms\Components\Toggle::make('isPrayerLeader')
                            // ->helperText('or Associate Prayer Leader')
                            ->label('Is Prayer Leader')->default('false') ,
                    ]),
                    Tabs\Tab::make('Sacred Trust Entry')
                    ->schema([
                        Forms\Components\TextInput::make('designation')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('supervisor')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('resultId'),
                        Forms\Components\DateTimePicker::make('startDate'),
                        Forms\Components\DateTimePicker::make('finishDate'),
                        Forms\Components\DateTimePicker::make('updateDate'),
                        Forms\Components\TextInput::make('resultStatus')
                            ->maxLength(100),
                    ]),
                
                ]),
            
            Forms\Components\TextInput::make('superEmail1')
                ->maxLength(100), 
                
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
                    Tables\Columns\IconColumn::make('isSupervisor')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('isSectionLeader')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('isWorshipLeader')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('isPrayerLeader')
                    ->boolean(),
                // Tables\Columns\TextColumn::make('resultId')->sortable(),
                // Tables\Columns\TextColumn::make('startDate')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('finishDate')
                //     ->dateTime(),
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
                Tables\Filters\Filter::make('isSupervisor')
                    ->label(trans('filament-user::user.resource.supervisor'))
                    ->query(fn (Builder $query): Builder => $query->where('isSupervisor', true)),
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
            UserResource\RelationManagers\UserRelationManager::class
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
