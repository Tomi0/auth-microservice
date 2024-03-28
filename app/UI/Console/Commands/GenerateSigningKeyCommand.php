<?php

namespace App\UI\Console\Commands;

use Authentication\Application\Service\SigningKey\GenerateSigningKey;
use Authentication\Application\Service\SigningKey\GenerateSigningKeyRequest;
use Illuminate\Console\Command;

class GenerateSigningKeyCommand extends Command
{
    protected $signature = 'generate:signing-key';

    protected $description = 'Generate a new signing key or replace the existing one.';

    private GenerateSigningKey $createSigningKey;

    public function __construct(GenerateSigningKey $createSigningKey)
    {
        parent::__construct();
        $this->createSigningKey = $createSigningKey;
    }

    public function handle(): int
    {
        $this->createSigningKey->handle(new GenerateSigningKeyRequest());

        return Command::SUCCESS;
    }
}
