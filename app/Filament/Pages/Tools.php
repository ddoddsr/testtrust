<?php

namespace App\Filament\Pages;
use App\Models\User;
use App\Models\Schedule;
use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
// use App\Http\Controllers\StaffController;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use App\Http\Controllers\PdfController;
use Filament\Notifications\Notification;
use App\Http\Controllers\FormsiteController;
// use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Tools extends Page
{
    // use HasPageShield;
    
    protected array $duplicateNames = [];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tools';

    // https://github.com/filamentphp/filament/discussions/6275
    // protected static bool $shouldRegisterNavigation = false;
    // protected static bool $shouldRegisterNavigation = false;
    // https://filamentphp.com/docs/2.x/admin/navigation#disabling-resource-or-page-navigation-items



    // protected static function shouldRegisterNavigation(): bool
    // {
    //     return auth()->user()->canManageSettings();
    // }
 
    // public function mount(): void
    // {
    //     abort_unless(auth()->user()->canManageSettings(), 403);
    // }

    /*
    What are the steps to protect a filimant Page?
    I created the page 
    */
    public function newResults()
    {
        $formSite = new FormsiteController;
        // get highest result_id in schedules table
        $latest = User::max('resultId'); 
        $formSite->storeForms($latest);
        
        Notification::make() 
            ->title('New results downloaded')
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
    public function genPdf()
    {
        $filePath = 'storage/sacred_trust.pdf';
        $pdf = new PdfController;
        $pdf->generatePdf($filePath);

        Notification::make() 
            ->title('Generation complete.')
            ->success()
            ->send(); 
        
        return response()->download($filePath);
    }

    
    protected function getActions(): array
    {
        return [
            Action::make('testme')
            ->label('Test Me')
            ->action('testMe'),
            //
            Action::make('duplicateNameCheck')
            ->label('duplicateNameCheck')
            ->action('duplicateNameCheck'),

            Action::make('newest')
                ->action('newResults'),
            Action::make('genPdf')
                ->action('genPdf'),
            Action::make('Re-import all Results')
                ->action('allResults')
                ->requiresConfirmation()
                    ->modalHeading('Re-import all Results')
                    ->modalSubheading('Are you sure you want to Drop Schedule data and re-import?')
                    ->modalContent(new HtmlString('<div class="text-center">FormSite Data.<br>from {date} .</div>'))
                    ->modalButton('Get All Results'),
            //
            Action::make('Re-import supers')
                ->action('allSupers')
                // ->hidden(true),
                // ->requiresConfirmation()
                //     ->modalHeading('Re-import all Supers')
                //     ->modalSubheading('Are you sure you want to Drop Schedule data and re-import?')
                //     ->modalButton('Get All Supers'),
        ];
    }
    

    public function testMe($name = 'testMe')
    {
        Notification::make() 
            ->title('TestMe complete')
            ->success()
            ->send(); 
    }
    public function duplicateNameCheck()
    {
        $collection = \App\Models\User::all();

        // Group models by sub_id and name
        $collection
        ->groupBy(function ($item) { return $item->first_name.'_'.$item->last_name; })
        // Filter to remove non-duplicates
        ->filter(function ($arr) { return $arr->count()>1; })
        // Collect duplicates groups
        ->each(function ($arr) {
            $arr->each(function ($model) {
                $this->duplicateNames[] = [$model->first_name, $model->last_name, $model->email, $model->supervisor];
            });
        });
        logger($this->duplicateNames);
        dd($this->duplicateNames);
    }
}
