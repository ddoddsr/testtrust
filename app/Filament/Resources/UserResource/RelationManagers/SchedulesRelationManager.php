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

        return $form
            ->schema([
                Select::make('day')
                ->options(Set::dayOfWeek())
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
                ->options(Location::all()->pluck('name', 'id'))
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
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    // $timeOfDay = Schedule::timeOfDay();
                    // $data['start'] = $timeOfDay[$data['start']];
                    // $string = str_replace(' ', '', $string);
                    // remove spaces? 

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
        // aor A to AM p or P to PM
        
        // remove spaces
        $entry = str_replace(' ', '', $entry);
        // change '.' to ':'
        $entry = str_replace('.', ':', $entry);
        
        $entry = Str::upper($entry);
        // if end char = AP change to AM PM
    
        $entryLen = Str::length($entry);
        if (Str::startsWith($entry, '0')) {
            $entry = substr($entry,1);
        }
        if ( Str::endsWith($entry, '-')){
            $entry = substr($entry,0, $entryLen -1  ) . 'AM';
        }
        if ( Str::endsWith($entry, '+')){
            $entry = substr($entry,0, $entryLen -1  ) . 'PM';
        }
        if ( Str::endsWith($entry, ['A', 'P'])){
            $entry .= 'M';
        }
        logger($entry);
        if( $entryLen >=  2 && $entryLen <=  4 ) {
            $entry = substr($entry,0, $entryLen -1 ) . ':00' . substr($entry, $entryLen -1, 2  );
        }

        // if( $entryLen ==  3 && ) {
            
        // }

        return $entry;
    } 
}

