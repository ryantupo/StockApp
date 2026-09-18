<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class CheckCommand extends Command
{
    protected $signature = 'check';

    protected $description = 'Run Pint, PHPStan, and the test suite';

    public function handle(): int
    {
        $this->info('Running Pint...');

        $pint = Process::forever()->run('cmd /c vendor\bin\pint.bat', function (string $type, string $output) {
            $this->output->write($output);
        });

        if ($pint->failed()) {
            $this->error('Pint failed.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Running PHPStan...');

        $phpstan = Process::forever()->run('cmd /c vendor\bin\phpstan.bat analyse --memory-limit=1G', function (string $type, string $output) {
            $this->output->write($output);
        });

        if ($phpstan->failed()) {
            $this->error('PHPStan found issues.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Running tests...');

        $tests = Process::forever()
            ->env(['APP_ENV' => 'testing'])
            ->run('php artisan test', function (string $type, string $output) {
                $this->output->write($output);
            });

        if ($tests->failed()) {
            $this->error('Tests failed.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('All checks passed.');

        return self::SUCCESS;
    }
}
