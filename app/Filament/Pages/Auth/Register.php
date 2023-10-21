<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament./pages.register';

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getFirstNameFormComponent(),
                        $this->getLastNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            // ->label(__('name'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }
    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('last_name')
            // ->label(__('name'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }
}
