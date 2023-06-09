<?php

namespace App\Filament\Pages;
use App\Models\User;
use App\Models\Location;
use App\Models\Schedule;
use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
// use App\Http\Controllers\StaffController;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Http\Controllers\WallPdfController;
use App\Http\Controllers\FormsiteController;
use App\Http\Controllers\SchedPdfController;
// use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Tools extends Page
{
    // use HasPageShield;
    // TODO Hmmm protected static ?string $model = Tools::class;
    public array $duplicateNames = []; 
    public array $ownSuperNames = [];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tools';

    // https://github.com/filamentphp/filament/discussions/6275
    protected static bool $shouldRegisterNavigation = false;
    
    
    // https://filamentphp.com/docs/2.x/admin/navigation#disabling-resource-or-page-navigation-items




    protected function getActions(): array
    {
        return [
            Action::make('newest')
            ->action('newResults'),

            Action::make('genWallPdf')
            ->label('Generate Wall PDF')
            ->action('genWallPdf')
            ->form([
                Select::make('location')
                    ->options(Location::query()->pluck('name', 'id'))
                    ->required()
                    ->default(1)
                    ->disablePlaceholderSelection(),
            ]),

            Action::make('genSchedPdf')
            ->label('Generate Schedule PDF')
            ->action('genSchedPdf')
            ->form([
                Select::make('location')
                    ->options(Location::query()->pluck('name', 'id'))
                    ->required()
                    ->default(1)
                    ->disablePlaceholderSelection(),
            ]),

            Action::make('duplicateNameCheck')
            ->label('Duplicate Name Check')
            ->action('duplicateNameCheck'),

            Action::make('ownSuperCheck')
            ->label('Own Supervisor Check')
            ->action('ownSuperCheck'),

            Action::make('Re-import all Results')
                ->action('allResults')
                ->requiresConfirmation()
                    ->modalHeading('Re-import all Results')
                    ->modalSubheading('Are you sure you want to Drop Schedule data and re-import?')
                    ->modalContent(new HtmlString('<div class="text-center">FormSite Data.<br>from {date} .</div>'))
                    ->modalButton('Get All Results')
                    ->visible(env('FORMSITE_IMPORT', 'false')),
            //
            Action::make('Re-import supers')
                ->action('allSupers')
                // ->visible(fn (Post $record): bool => auth()->user()->can('update', $record))
                ->visible(env('FORMSITE_IMPORT', 'false')),
            Action::make('testme')
                ->label('Test Me')
                ->action('testMe')
                ->visible(env('FORMSITE_IMPORT', 'false')),
            Action::make('TestWallPdf')
                // ->label('Test Me')
                ->action('genWallPdf'),
        ];
    }
    public function newResults()
    {
        $formSite = new FormsiteController;
        // get highest result_id in schedules table
        $latest = User::max('result_id'); 
        $formSite->storeForms($latest);
        
        Notification::make() 
            ->title('New download request completed.')
            ->success()
            ->send(); 
    }
    
    public function allResults() 
    {
        Schedule::truncate();
        $formSite = new FormsiteController;
        
        $formSite->storeForms();
        Notification::make() 
        ->title('All results import  complete.')
        ->success()
        ->send(); 
        return $this->redirect('/admin/tools');
    }

    public function allSupers() 
    {
        Schedule::truncate();
        $formSite = new FormsiteController;
        
        $formSite->storeSupers();
        Notification::make() 
        ->title('All supervisors import  complete.')
        ->success()
        ->send(); 
        return $this->redirect('/admin/tools');
    }
    public function genWallPdf($data)
    {
        $filePath = 'storage/sacred_trust_wall.pdf';
        $pdf = new WallPdfController;
        $pdf->generatePdf($filePath, 6);

        Notification::make() 
            ->title('Generation complete.')
            ->success()
            ->send(); 
        
        return response()->download($filePath);
    }

    public function genSchedPdf($data)
    {
        $filePath = 'storage/sacred_trust_schedule.pdf';
        $pdf = new SchedPdfController;
        $pdf->generatePdf($filePath, $data['location']);

        Notification::make() 
            ->title('Generation complete.')
            ->success()
            ->send(); 
        
        return response()->download($filePath);
    }

    public function testMe($name = 'testMe')
    {
        Notification::make() 
            ->title('TestMe complete')
            ->success()
            ->send(); 
    }
    public function testWallPdf($name = 'testMe')
    {
        Notification::make() 
            ->title('Test Wall Pdf complete')
            ->success()
            ->send(); 
    }
    public function duplicateNameCheck()
    {
        $this->duplicateNames = [];
        $collection = \App\Models\User::all();

        // Group models by sub_id and name
        $collection
        ->groupBy(function ($item) { return $item->first_name.'_'.$item->last_name; })
        // Filter to remove non-duplicates
        ->filter(function ($arr) { return $arr->count()>1; })
        // Collect duplicates groups
        ->each(function ($arr) {
            $arr->each(function ($model) {
                $this->duplicateNames[] = [
                    'user_id' => $model->id, 
                    'user_name' => $model->full_name,
                    'email' => $model->email, 
                    'super' => $model->supervisor,
                    'effective' => $model->effective_date,
                ];
            });
        });
        
    }
    public function ownSuperCheck()
    {
        $this->duplicateNames = []; //clears out existing
        $this->ownSuperNames = [];  // resets to empty
        $staff = DB::table('users')
        ->whereColumn('email', '=', 'super_email1')
        ->get();
        foreach($staff as $own) {
            $this->ownSuperNames[] =  [
                'user_id' => $own->id, 
                'user_name' => $own->first_name . ' ' . $own->last_name,
                'email' => $own->email, 
                'super' => $own->super_email1,
                'effective' => $own->effective_date,
            ];
        }
    }
}

