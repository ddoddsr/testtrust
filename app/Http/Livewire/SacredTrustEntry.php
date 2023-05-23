<?php

namespace App\Http\Livewire;

use Filament\Forms;
use App\Models\User;
use Livewire\Component;

use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;


class SacredTrustEntry extends Component implements HasForms
{
    use Forms\Concerns\InteractsWithForms;
 
    public $title = '';
    public $content = '';
    public static $schedLines;
 
    public function mount(): void 
    {
        $this->form->fill();
    } 

    public function render(): View
    {
        return view('livewire.sacred-trust-entry')
        ;
    }
    protected function getFormSchema(): array
    {
        return [
            
            Grid::make(3)
            ->schema([

                TextInput::make('first_name')
                ->minLength(2)->maxLength(255)
                ,//->required(),
                
                Forms\Components\TextInput::make('last_name')
                ->minLength(2)->maxLength(255)
               , // ->required(),
                
                Forms\Components\TextInput::make('email')->email()       
               , // ->required(),
         
                Forms\Components\TextInput::make('effective')->type('date')
                ->default(now()->toDateString())
               , // ->required(),  

                Select::make('designation_id')
                ->label('Designation')
                ->options(User::designations())->reactive()
                ->required()
                ->columnSpan(2), 

                Section::make('Full time and Part-time ')
                ->description('Description for full and part time')
                ->hidden(fn(Callable $get) => $get('designation_id') !== 'full' && $get('designation_id') !== 'part')
                ->schema([
                    // make dependant on full / part
                    Select::make('Department')
                    // TODO get departments from table
                        ->options(self::departments()),

                    // TODO make a super select from User->isSupervisor
                    TextInput::make('supervisor'),
                    TextInput::make('super_email_1'),
                    ])->columns(3),
                Repeater::make('Prayer Room Hours')
                // ->description('Description for Prayer Room Hours')
                ->schema([
                    Grid::make()->schema(self::schedLine(1))->columns(4),
                ]
                )
                // ->defaultItems(3)
                ->columnSpan(3)
                ->columns(4),  

                Repeater::make('Service Hours') 
                ->schema([
                    Grid::make()->schema(self::serviceHours(1))->columns(3),
                ])->defaultItems(3)->columnSpan(3)
                ->columns(3),  

                // Placeholder::make('Label')
                // ->content('Content, displayed underneath the label')
        ])

        ];
    }

    public function serviceHours ($pos = 1 ) {
        return [
            Select::make("service_department" . $pos )->label('')->placeholder("Service Department")->options(self::departments()),
            Forms\Components\TextInput::make("service_hours" . $pos )->label('')->placeholder("Hours Weekly"),
            Forms\Components\TextInput::make("service_supervisor" . $pos )->label('')->placeholder("Service Supervisor"),
        ];
    }
    public static function schedLine($pos = 1) {
        return [
            Forms\Components\Select::make("day_of_week_" . $pos )->options( self::daysOfWeek())->label('')->placeholder("Day"),
            Forms\Components\Select::make("start_time_" . $pos)->options( self::timeOfDay())->label('')->placeholder("Start Time"),
            Forms\Components\Select::make("end_time_" . $pos)->options( self::timeOfDay())->label('')->placeholder("End Time"),
            Forms\Components\Select::make("locaction_" . $pos)->options( self::locations())->label('')->placeholder("Location"),
        ];
    }

    public static function daysOfWeek() {
        return [
            1 => 'Sunday',
            2 => 'Monday',
            3 => 'Tuesday',
            4 => 'Wednesday',
            5 => 'Thursday',
            6 => 'Friday',
            7 => 'Saturday'
        ];
    }

    public static function timeOfDay() {
        return[ 
            '12:00 am','12:30 am','1:00 am','1:30 am','2:00 am','3:30 am','3:00 am','3:30 am',
            '4:00 am','4:30 am','5:00 am','5:30 am','6:00 am','6:30 am','7:00 am','7:30 am',
            '8:00 am','8:30 am','9:00 am', '9:30 am','10:00 am', '10:30 am','11:00 am', '11:30 am',
            '12:00 pm','12:30 pm','1:00 pm','1:30 pm','2:00 pm','3:30 pm','3:00 pm','3:30 pm',
            '4:00 pm','4:30 pm','5:00 pm','5:30 pm','6:00 pm','6:30 pm','7:00 pm','7:30 pm',
            '8:00 pm','8:30 pm','9:00 pm', '9:30 pm','10:00 pm', '10:30 pm','11:00 pm', '11:30 pm',
        ];
    }

    public static function locations() {
        return [
            1 => 'GPR',
            2 => 'ANPR',
            3 => 'FC', 
            4 => 'Hope City',
            5 => 'Malichai 6:6'
        ];
    }

    public static function departments() {
        return [
            'other' => 'Other',
            'hr' => 'HR', 
            'eng' => 'Engineering',
        ];
    }
    public function submit(){
        logger( "submitted");
        // logger( ["submitted" => $this->form->getState() ] );
    }
}
