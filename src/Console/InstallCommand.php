<?php

namespace QuetzalStudio\Maple\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maple:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Maple resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->callSilent('jetstream:install', ['stack' => 'inertia']);

        $this->updatePackageFile();

        $this->updateNodePackages(function ($packages) {
            return [
                '@vitejs/plugin-vue' => '^2.3.1',
                'autoprefixer' => '^10.4.5',
                'postcss' => '^8.4.12',
                'vite' => '^2.9.5',
            ] + $packages;
        });

        copy(__DIR__ . '/../../stubs/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__ . '/../../stubs/vite-cleanup', base_path('vite-cleanup'));
        copy(__DIR__ . '/../../stubs/.prettierrc', base_path('.prettierrc'));

        copy(__DIR__ . '/../../stubs/resources/js/app.js', resource_path('js/app.js'));
        copy(__DIR__ . '/../../stubs/resources/js/bootstrap.js', resource_path('js/bootstrap.js'));
        copy(__DIR__ . '/../../stubs/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        copy(__DIR__ . '/../../stubs/public/dist/.gitignore', public_path('dist/.gitignore'));
        copy(__DIR__ . '/../../stubs/public/dist/assets/.gitignore', public_path('dist/assets/.gitignore'));

        if (file_exists(base_path('webpack.mix.js'))) {
            unlink(base_path('webpack.mix.js'));
        }

        $this->info('Maple scaffolding installed successfully.');
        $this->comment('Please execute "yarn install && yarn dev" to build your assets.');
    }

    /**
     * Update the "package.json" file.
     *
     * @return void
     */
    protected static function updatePackageFile()
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages['scripts'] = [
            "dev" => "vite",
            "build" => "vite build && ./vite-cleanup",
            "preview" => "vite preview",
        ];

        $packages['postcss'] = [
            "plugins" => [
                "autoprefixer" => [],
                "tailwindcss" => [
                    "config" => "tailwind.config.js",
                ],
            ],
        ];

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );

        file_put_contents(
            base_path('package.json'),
            preg_replace(
                '/"laravel-mix".*\n/',
                '',
                file_get_contents(base_path('package.json'))
            ),
            JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }
}
