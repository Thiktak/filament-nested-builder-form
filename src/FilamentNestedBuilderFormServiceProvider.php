<?php

namespace Thiktak\FilamentNestedBuilderForm;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Thiktak\FilamentNestedBuilderForm\Testing\TestsFilamentNestedBuilderForm;

class FilamentNestedBuilderFormServiceProvider extends PackageServiceProvider
{
    public static string $name = 'thiktak-filament-nested-builder-form';

    public static string $viewNamespace = 'thiktak-filament-nested-builder-form';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name);

        $configFileName = $package->shortName();

        /*if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }*/

        /*if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }*/

        /*if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }*/

        /*if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }*/
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        /*if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/skeleton/{$file->getFilename()}"),
                ], 'skeleton-stubs');
            }
        }*/

        // Testing
        Testable::mixin(new TestsFilamentNestedBuilderForm());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'Thiktak/filament-nested-builder-form';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('skeleton', __DIR__ . '/../resources/dist/components/skeleton.js'),
            //Css::make('skeleton-styles', __DIR__ . '/../resources/dist/skeleton.css'),
            //Js::make('skeleton-scripts', __DIR__ . '/../resources/dist/skeleton.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            //SkeletonCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            //'create_skeleton_table',
        ];
    }
}
