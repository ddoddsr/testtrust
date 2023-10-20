<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Set;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Location;
use App\Models\Schedule;
use Illuminate\Support\Str;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
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
    public function form(Form $form): Form
    {
        // $timeOfDay = Schedule::timeOfDay();
        // $startRegex = '/^([1-9]|1[0-2])((:|\.)[0-5][0-9])|()?([AaPp\-\+][Mm]?)$/';
        $startRegex = '/(^[1-9]|^1[0-2])[:\.]?([0-5][0-9])?[a\-p\+]m?$/mi';
        $endRegex   = '/^
        (?<hours>[1-9]|1[0-2])
        [:\.]?
        (?<minutes>[0-5][0-9])?
        (?>(?<am>am?|\-)|(?<pm>pm?|\+))
        $/mix';
        // $timeRegex = '';
        // $timeRegex = '';
        // $timeRegex = '';
        return $form
        
            ->schema([
                Select::make('day')
                ->options(Set::dayOfWeekStr())
                ->searchable(),

                TextInput::make('start')
                ->maxLength(7)
                ->minLength(2)
                ->regex($startRegex),

                TextInput::make('end')
                ->maxLength(7)
                ->minLength(2)
                ->regex($endRegex),
                    
                Select::make('location_id')
                ->options(Location::all()->pluck('name', 'id'))
                ->default(1)
                ->searchable(),
            ])->columns(4);
    }

    public function table(Table $table): Table
    {
        
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('id')
                // ->getStateUsing(function(Schedule $record) {
                //     return $record->id;
                // }),
                // Tables\Columns\TextColumn::make('id_test')
                // ->getStateUsing(function(Schedule $record) {
                //     return $record->id;
                // }),
                TextColumn::make('day'),
                TextColumn::make('start'),
                TextColumn::make('end'),
                Tables\Columns\TextColumn::make('calc_time')
                ->label('Calculated Time')
                ->getStateUsing(function(Schedule $record) {
                    $schedStartM = Carbon::parse($record->start); 
                    if ($record->end == '12:00AM')  {
                        $schedEndM = Carbon::parse('11:59PM')->addMinutes(1);
                    } else {
                        $schedEndM = Carbon::parse($record->end);
                    }

                    $schedDuration = $schedStartM->diff($schedEndM)->format('%h:%I');
                    // $schedDuration = $schedStartM->diffInMinutes($schedEndM);
                    // logger($schedDuration );
                    return $schedDuration ;
                }),
                Tables\Columns\SelectColumn::make('location_id')
                ->options(Location::all()->pluck('name', 'id'))
               ,
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add Schedule Line')
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

    // public array $data_list= [
    //     'calc_columns' => [
    //         'calc_time', 
    //         // 'id',
    //         // 'id_test',   
    //     ],
    // ];
    // protected function getTableContentFooter(): ?View
    // {
    //     return view('table.footer', $this->data_list);
    // }

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

