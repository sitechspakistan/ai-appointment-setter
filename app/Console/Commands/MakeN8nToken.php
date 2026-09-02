<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MakeN8nToken extends Command
{
    protected $signature = 'n8n:token
                            {name=n8n : A label for this token}
                            {--email=n8n@webefytoday.com : Service-account email}
                            {--fresh : Revoke this account\'s existing tokens first}';

    protected $description = 'Issue a Sanctum API token for the n8n automation backend';

    public function handle(): int
    {
        $user = User::firstOrCreate(
            ['email' => $this->option('email')],
            [
                'name' => 'n8n Automation',
                'password' => Hash::make(Str::random(40)),
                'role' => User::ROLE_SERVICE,
                'tenant_id' => null,
            ],
        );

        if ($user->role !== User::ROLE_SERVICE) {
            $this->error("User {$user->email} exists but is not a service account (role: {$user->role}).");

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            $count = $user->tokens()->count();
            $user->tokens()->delete();
            $this->warn("Revoked {$count} existing token(s).");
        }

        $token = $user->createToken($this->argument('name'), ['*'])->plainTextToken;

        $this->newLine();
        $this->info('API token issued — store it now, it will not be shown again:');
        $this->line("  <comment>{$token}</comment>");
        $this->newLine();
        $this->line('  Base URL : '.rtrim(config('app.url'), '/').'/api/v1');
        $this->line('  Header   : Authorization: Bearer <token>');
        $this->line('  Probe    : GET /api/v1/whoami');
        $this->newLine();

        return self::SUCCESS;
    }
}
