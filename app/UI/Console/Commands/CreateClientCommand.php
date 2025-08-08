<?php

namespace App\UI\Console\Commands;

use Authentication\Application\Service\Client\CreateClient;
use Authentication\Application\Service\Client\CreateClientRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CreateClientCommand extends Command
{
    protected $signature = 'client:create';

    protected $description = 'Create a new client.';

    private CreateClient $createClient;

    public function __construct(CreateClient $createClient)
    {
        parent::__construct();
        $this->createClient = $createClient;
    }

    public function handle(): int
    {
        $clientName = Str::slug($this->ask('Enter the client name'));
        $clientRedirectUri = $this->ask('Enter the client redirect URI');

        $clientSecret = $this->createClient->handle(new CreateClientRequest(
            $clientName,
            $clientRedirectUri
        ));

        $this->info('Client created successfully.');
        $this->info('Client name: ' . $clientName);
        $this->info('Client Secret (one time display): ' . $clientSecret);

        return SymfonyCommand::SUCCESS;
    }
}

