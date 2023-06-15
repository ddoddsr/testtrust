<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Set;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput\Mask;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';
    public $timeOfDay; 
    // protected static ?string $recordTitleAttribute = 'name';
    public function __construct()
    {
        
        // $this->users = DB::table('users')
        // ->select('first_name', 'last_name', 'is_supervisor')
        // ->get();
        
        // $this->users = User::designations_key();
        // $this->locations = array_flip(Location::all()->pluck('name', 'id')->toArray());
        
    }
    public static function form(Form $form): Form
    {
        // $timeOfDay = Schedule::timeOfDay();
        $timeRegex = '/^([1-9]|1[0-2])((:|\.)[0-5][0-9])|()?([AaPp\-\+][Mm]?)$/';
        // $timeRegex = '/^(?:[01]?\d|2[0-3])(?::[0-5]\d){1,2}$/';
        // $timeRegex = '/((1[0-2]|0?[1-9]):([0-5][0-9])?([AaPp][Mm]))/';
        // $timeRegex = '/^(([0-1]{0,1}[0-9]( )?([AaPp][Mm]))|(([0]?[1-9]|1[0-2])(:|\.)[0-5][0-9]( )?([AaPp][Mm]))|(([0]?[0-9]|1[0-9]|2[0-3])(:|\.)[0-5][0-9]))$/';
        // dd(['Sunday', 'Monday',  'Tuesday',  'Wednesday',  'Thursday', 'Friday', 'Saturday']);
        return $form
        
            ->schema([
                Select::make('day')
                ->options(Set::dayOfWeekStr())
                
                ->searchable(),
                // TODO select from 
                // Select::make('start')
                // ->options( $timeOfDay)
                // ->label('Start Time')
                // ->placeholder("Start Time")
                // Select::make('end')
                // ->options( $timeOfDay)
                // ->label('End Time')
                // ->placeholder("End Time"),
                TextInput::make('start')
                    ->maxLength(7)
                    ->minLength(2)
                    ->regex($timeRegex)
                    ,
                TextInput::make('end')
                    ->maxLength(7)
                    ->minLength(2)
                    ->regex($timeRegex)
                    ,
                    
                Select::make('location')
                ->options(Location::all()->pluck('name', 'name'))
                ->default('GPR')
                ->searchable(),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        
        return $table
            ->columns([
                TextColumn::make('day'),
                TextColumn::make('start'),
                TextColumn::make('end'),
                TextColumn::make('location'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['start'] = SchedulesRelationManager::cleanTime($data['start']) ;
                    $data['end'] = SchedulesRelationManager::cleanTime($data['end']) ;      
                    return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $data['start'] = SchedulesRelationManager::cleanTime($data['start']) ;
                    $data['end'] = SchedulesRelationManager::cleanTime($data['end']) ;      
                    return $data;
                    }), 
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
    public static function cleanTime($entry) {
        // remove spaces
        $entry = str_replace(' ', '', $entry);
        // change '.' to ':'
        $entry = str_replace('.', ':', $entry);
        
        $entry = Str::upper($entry);
        
        $entryLen = Str::length($entry);
        // remove leaing 0
        if (Str::startsWith($entry, '0')) {
            $entry = substr($entry,1);
        }
        if ( Str::endsWith($entry, '-')){
            $entry = substr($entry,0, $entryLen -1  ) . 'AM';
        }
        if ( Str::endsWith($entry, '+')){
            $entry = substr($entry,0, $entryLen -1  ) . 'PM';
        }
        // if end char = A or P change to AM PM
        if ( Str::endsWith($entry, ['A', 'P'])){
            $entry .= 'M';
        }
        
        if( $entryLen >=  2 && $entryLen <=  4 ) {
            $entry = substr($entry,0, $entryLen -1 ) . ':00' . substr($entry, $entryLen -1, 2  );
        }

        // TODO make this work if needed
        // if( :?  then AorP  make it :?0  ) {
            // $entry = substr($entry,0, stripos($entry, ':' ) +1 ) . '0' . substr($entry, $entryLen -1, 2  );
        // }

        return $entry;
    } 
}

