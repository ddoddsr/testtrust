<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Department;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Staff';
    protected static ?string $pluralModelLabel = 'Staff';
    protected static ?string $navigationLabel = 'Staff';
    // protected static ?string $recordTitleAttribute = 'full_name';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
        ->columns(3)
        ->schema([
            Forms\Components\TextInput::make('first_name')
                ->required()
                ->minLength(2)
                ->maxLength(255),
            Forms\Components\TextInput::make('last_name')
                ->required()
                ->minLength(2)
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                // ->unique()
                ->unique(table: User::class, ignoreRecord: true)
                ->maxLength(255),
            // Forms\Components\DatePicker::make('effective_date'),
            
            Select::make('designation_id')
                ->label('Designation')
                ->options(User::designations()),
                
            Select::make('department_id')
                ->label('Department')
                ->options(Department::all()
                ->pluck('name', 'id'))
                ->searchable(),

            Select::make('supervisor_id')
                ->label('Supervisor lookup')
                ->options(User::all()
                ->pluck('name_and_email', 'id'))
                ->searchable(),
            
            Forms\Components\FileUpload::make('profile_photo_path')
                // ->label(__('fields.images.src'))
                // ->helperText(__('fields.images.src.helper'))
                // ->required()
                ->disk('public')
                ->directory('profile-photos')
                // ->maxSize(env('DRIVER_MEDIA_MAX_UPLOAD_BYTES'))
                ->image(),
            Tabs::make('Heading')->columnSpan(2)
                ->tabs([
                    Tabs\Tab::make('Staff Data')
                    ->schema([
                        // photo 
                        // Forms\Components\Toggle::make('active')
                        //     ->required(),
                        Forms\Components\DatePicker::make('effective_date'),
                        Forms\Components\DateTimePicker::make('exit_date'),
                        Forms\Components\Toggle::make('is_supervisor')
                        ->label('Is Supervisor')->default(0),
                        Forms\Components\Toggle::make('review')
                        ->default(0),
                        Forms\Components\Toggle::make('is_approved')
                        ->label("Supervisor Approved ST")
                        ->default(0),
                        // Select::make('section')
                        // ->options(['various', 'morning', 'afternoon', 'evening','nightwatch']),
                        // Forms\Components\Toggle::make('isSectionLeader')
                        //     // ->helperText('or Associate Section Leader')
                        //     ->label('Is Section Leader')->default('false'),
                        // Forms\Components\Toggle::make('isWorshipLeader')
                        //     // ->helperText('or Associate Worship Leader')
                        //     ->label('Is Worship Leader')->default('false') ,
                        // Forms\Components\Toggle::make('isPrayerLeader')
                        //     // ->helperText('or Associate Prayer Leader')
                        //     ->label('Is Prayer Leader')->default('false') ,
                    ]),
                    Tabs\Tab::make('FormSite Data Entry')
                    ->schema([
                        Forms\Components\TextInput::make('designation')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('supervisor')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('super_email1')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('result_id'),
                        Forms\Components\DateTimePicker::make('start_date'),
                        Forms\Components\DateTimePicker::make('finish_date'),
                        Forms\Components\DateTimePicker::make('update_date'),
                        Forms\Components\TextInput::make('result_status')
                            ->maxLength(100),
                    ]),
                    Tabs\Tab::make('Admin Only')
                        ->schema([
                            Forms\Components\DateTimePicker::make('email_verified_at'),
                            // Forms\Components\TextInput::make('password')
                            //     ->password()
                            //     ->required()
                            //     ->maxLength(255),
                            // Forms\Components\Textarea::make('two_factor_secret')
                            //     ->maxLength(65535),
                            // Forms\Components\Textarea::make('two_factor_recovery_codes')
                            //     ->maxLength(65535),
                            Forms\Components\DateTimePicker::make('two_factor_confirmed_at'),
                            // Forms\Components\TextInput::make('current_team_id'),
                            // ->maxLength(2048),
                            Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),
                            Select::make('permissions')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload(),
                    ]),
                
                ])->columns(2),

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
                // Tables\Columns\IconColumn::make('active')
                //     ->boolean()->sortable(),
                Tables\Columns\IconColumn::make('is_supervisor')
                ->boolean(),
                Tables\Columns\IconColumn::make('review')
                ->boolean(),
                    
                    // Tables\Columns\IconColumn::make('isSectionLeader')
                    // ->boolean(),
                    // Tables\Columns\IconColumn::make('isWorshipLeader')
                    // ->boolean(),
                    // Tables\Columns\IconColumn::make('isPrayerLeader')
                    // ->boolean(),
                // Tables\Columns\TextColumn::make('resultId')->sortable(),
                // Tables\Columns\TextColumn::make('startDate')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('finishDate')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updateDate')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('result_status')->sortable(),
                Tables\Columns\SelectColumn::make('designation_id')
                ->options(fn () => User::designations_short())
                ->sortable(),
                // Tables\Columns\TextColumn::make('designation')->sortable(),
                Tables\Columns\TextColumn::make('supervisor')->sortable()->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('super_email1')->sortable(),
                Tables\Columns\TextColumn::make('effective_date')
                    ->date()->sortable(),
                Tables\Columns\TextColumn::make('exit_date')
                    ->date()->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime(),
                //Tables\Columns\TextColumn::make('section')
            ])
            ->filters([
                // Tables\Filters\Filter::make('verified')
                //     ->label(trans('filament-user::user.resource.verified'))
                //     ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                // Tables\Filters\Filter::make('unverified')
                //     ->label(trans('filament-user::user.resource.unverified'))
                //     ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),

                SelectFilter::make('designation_id')
                ->multiple()
                ->options(User::designations_short()),

                // Add to above options?
                Tables\Filters\Filter::make('no_designation')
                ->label(trans('No Designation'))
                ->query(fn (Builder $query): Builder => $query->where('designation_id', null)),

                Tables\Filters\Filter::make('effective_date')
                ->label(trans('Old Effective Date'))
            
                ->query(fn (Builder $query, array $data): Builder => 
                    $query->where('effective_date', '<', Carbon::today()->addYears(-1))
                    // $query->where('effective_date', '<', Carbon::createFromFormat('d-m-Y', $data['effective_from']))
                    ->where('exit_date', null)
                ),

                Tables\Filters\Filter::make('review')
                ->label(trans('To Be Reviewed'))
                ->query(fn (Builder $query): Builder => $query->where('review', true)),
                
                Tables\Filters\Filter::make('is_approved')
                ->label(trans('Not Supervisor Approved'))
                ->query(fn (Builder $query): Builder => $query->where('is_approved', false)),
                
                Tables\Filters\Filter::make('is_supervisor')
                ->label(trans('Is supervisor'))
                ->query(fn (Builder $query): Builder => $query->where('is_supervisor', true)),
                
                Tables\Filters\Filter::make('not_supervisor')
                ->label(trans('Not supervisor'))
                ->query(fn (Builder $query): Builder => $query->where('is_supervisor', false)),

                TernaryFilter::make('is_admin'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\SchedulesRelationManager::class,
            RelationManagers\SupervisingRelationManager::class,
            RelationManagers\ServiceHoursRelationManager::class,
            RelationManagers\DirectReportRelationManager::class,
            RelationManagers\EmailAliasRelationManager::class,
            AuditsRelationManager::class,
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            // ->where('id', '!=', 1)
            // ->where('id', '!=', $user->id )
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


}
