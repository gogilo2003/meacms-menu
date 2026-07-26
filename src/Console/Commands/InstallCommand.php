<?php

namespace MeaCms\Menu\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'meacms:install
                            {stack? : The Breeze stack (vue, react, blade, api)}
                            {--dark : Indicate that dark mode support should be installed}
                            {--typescript : Install TypeScript support}
                            {--ssr : Install Inertia SSR support}
                            {--force : Overwrite existing files}';

    protected $description = 'Install MeaCMS starter kit resources and trigger Breeze authentication installation.';

    public function handle(): int
    {
        $this->components->info('Installing MeaCMS...');

        // 1. Trigger breeze:install command if available
        if ($this->getApplication()->has('breeze:install')) {
            $stack = $this->argument('stack') ?? 'vue';

            $breezeOptions = [
                'stack' => $stack,
            ];

            if ($this->option('dark')) {
                $breezeOptions['--dark'] = true;
            }
            if ($this->option('typescript')) {
                $breezeOptions['--typescript'] = true;
            }
            if ($this->option('ssr')) {
                $breezeOptions['--ssr'] = true;
            }

            $this->components->task('Triggering breeze:install...', function () use ($breezeOptions) {
                return $this->call('breeze:install', $breezeOptions) === 0;
            });
        } else {
            $this->components->warn('Command breeze:install not found. Make sure gogilo/breeze is installed.');
        }

        // 2. Publish MeaCMS shared resources
        $this->components->task('Publishing MeaCMS shared resources...', function () {
            return $this->call('vendor:publish', ['--tag' => 'meacms-menu-shared', '--force' => true]) === 0;
        });

        $this->components->info('MeaCMS installation completed successfully!');

        return Command::SUCCESS;
    }
}
