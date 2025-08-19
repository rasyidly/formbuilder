<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $title = 'Settings';
    protected static ?int $navigationSort = 100;

    public $mail_mailer;
    public $mail_host;
    public $mail_port;
    public $mail_username;
    public $mail_password;
    public $mail_encryption;
    public $mail_from_address;
    public $mail_from_name;

    public $test_to_email;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('mail_mailer')->label('Mail Driver'),
                Forms\Components\TextInput::make('mail_host')->label('Host'),
                Forms\Components\TextInput::make('mail_port')->label('Port'),
                Forms\Components\TextInput::make('mail_username')->label('Username'),
                Forms\Components\TextInput::make('mail_password')->label('Password')->password(),
                Forms\Components\TextInput::make('mail_encryption')->label('Encryption'),
                Forms\Components\TextInput::make('mail_from_address')->label('From Address'),
                Forms\Components\TextInput::make('mail_from_name')->label('From Name'),
            ])->columns(2)
        ];
    }

    public function mount(): void
    {
        $this->mail_mailer = env('MAIL_MAILER');
        $this->mail_host = env('MAIL_HOST');
        $this->mail_port = env('MAIL_PORT');
        $this->mail_username = env('MAIL_USERNAME');
        $this->mail_password = env('MAIL_PASSWORD');
        $this->mail_encryption = env('MAIL_ENCRYPTION');
        $this->mail_from_address = env('MAIL_FROM_ADDRESS');
        $this->mail_from_name = env('MAIL_FROM_NAME');
    }

    public function save()
    {
        $values = [
            'MAIL_MAILER' => $this->mail_mailer,
            'MAIL_HOST' => $this->mail_host,
            'MAIL_PORT' => $this->mail_port,
            'MAIL_USERNAME' => $this->mail_username,
            'MAIL_PASSWORD' => $this->mail_password,
            'MAIL_ENCRYPTION' => $this->mail_encryption,
            'MAIL_FROM_ADDRESS' => $this->mail_from_address,
            'MAIL_FROM_NAME' => $this->mail_from_name,
        ];

        $this->updateEnv($values);

        Notification::make()->title('Settings saved')->success()->send();
    }

    protected function updateEnv(array $values): void
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            return;
        }

        $content = file_get_contents($path);

        foreach ($values as $key => $value) {
            $escaped = str_replace("\n", "\\n", $value);

            if (Str::contains($content, "{$key}=")) {
                $content = preg_replace(
                    "/^{$key}=.*$/m",
                    "{$key}=" . ($escaped === null ? '' : $escaped),
                    $content
                );
            } else {
                $content .= "\n{$key}=" . ($escaped === null ? '' : $escaped);
            }
        }

        // Use Laravel's Filesystem for safer file writing
        app('files')->put($path, $content);

        return;
    }

    public function sendTestEmail()
    {
        if (!filter_var($this->test_to_email, FILTER_VALIDATE_EMAIL)) {
            Notification::make()->title('Invalid email')->danger()->send();
            return;
        }

        try {
            Mail::raw('This is a test email from Settings page.', function ($message) {
                $message->to($this->test_to_email)
                    ->subject('Test Email from Application');
            });

            Notification::make()->title('Test email sent')->success()->send();
        } catch (\Throwable $e) {
            Notification::make()->title('Failed to send')->body($e->getMessage())->danger()->send();
        }
    }
}
