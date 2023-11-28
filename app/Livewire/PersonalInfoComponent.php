<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class PersonalInfoComponent extends  MyProfileComponent
{
    protected string $view = "filament-breezy::livewire.personal-info";
                       // 'livewire.profile-component';

    public ?array $data = [];
    public $user;
    public $userClass;
    public bool $hasAvatars;
    public array $only = ['first_name','last_name','email'];

    public static $sort = 10;

    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);
        $this->hasAvatars = filament('filament-breezy')->hasAvatars();
        if ($this->hasAvatars){
            $this->only[] = filament('filament-breezy')->getAvatarUploadComponent()->getStatePath(false);
        }
        $this->form->fill($this->user->only($this->only));
    }

    protected function getProfileFormSchema()
    {
        // $name = Forms\Components\TextInput::make('name')
        //             ->required()
        //             ->label(__('filament-breezy::default.fields.name'));
        $first_name = Forms\Components\TextInput::make('first_name')
                    ->required();
                    // ->label(__('filament-breezy::default.fields.name'));
        $last_name = Forms\Components\TextInput::make('last_name')
                    ->required();
                    // ->label(__('filament-breezy::default.fields.name'));
        $email = Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique($this->userClass, ignorable: $this->user)
                    ->label(__('filament-breezy::default.fields.email'));

        if ($this->hasAvatars){
            return [
                filament('filament-breezy')->getAvatarUploadComponent(),
                Forms\Components\Group::make([
                    // $name,
                    $first_name,
                    $last_name,
                    $email
                ])->columnSpan(2),
            ];
        } else {
            return [
                Forms\Components\Group::make([
                    // $name,
                    $first_name,
                    $last_name,
                    $email
                ])->columnSpan(3)
            ];
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getProfileFormSchema())->columns(3)
            ->statePath('data');
    }

    public function submit()
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        Notification::make()
            ->success()
            ->title(__('filament-breezy::default.profile.personal_info.notify'))
            ->send();
    }

}

