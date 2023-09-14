# Nested Builder Form (Filament v3 Form)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thiktak/filament-nested-builder-form.svg?style=flat-square)](https://packagist.org/packages/thiktak/filament-nested-builder-form)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/thiktak/filament-nested-builder-form/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/thiktak/filament-nested-builder-form/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/thiktak/filament-nested-builder-form/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/thiktak/filament-nested-builder-form/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/thiktak/filament-nested-builder-form.svg?style=flat-square)](https://packagist.org/packages/thiktak/filament-nested-builder-form)


Filament V3 Form plugin.
Add a new feature for Nested Builder.

## Installation

You can install the package via composer:

```bash
composer require thiktak/filament-nested-builder-form
```


## Usage

On any Form :

1. Call ```NestedBuilder``` instead of ```Builder```
2. All all your Builder configuration inside ```nestedConfiguration(Closure)```.
3. Use ```nestedSchema(Closure)``` instad of ```schema(Closure | array)```

Use ```$builder->getLevel()``` of ```NestedSubBuilder``` to know where you are (level 1 to n)

Note:
* NestedBuilder like Builder works with an array/json data.

```php

use Thiktak\FilamentNestedBuilderForm\Forms\Components\NestedBuilder;
use Thiktak\FilamentNestedBuilderForm\Forms\Components\NestedSubBuilder;
// ...

        NestedBuilder::make('array_configuration')
            // Add configuration to Builder & sub-builder
            ->nestedConfiguration(function (NestedSubBuilder $builder) {
                // Apply only for the root level
                $builder->blockNumbers($builder->getLevel() == 1);

                // Apply for all others levels
                $builder->columnSpanFull(); // full width
            })

            // Replace schema() by nestedSchema
            ->nestedSchema(function (NestedSubBuilder $builder) { // Closure is mandatory
                return [
                    Block::make('group')
                        ->schema([
                            Select::make('title')
                                ->required(),

                            // Call builder importer and call it children
                            $builder->importNestedBlocks('children'),
                        ]),

                    Block::make('rule')
                        ->schema([
                            TextInput::make('field')
                                ->required(),
                        ])
                ];
            });

```

![image](https://github.com/Thiktak/filament-nested-builder-form/assets/1201486/11c5a674-c001-4508-b7ca-0a6a318a1ba0)


## Example

One concrete example of this package, allow you to create a nested AND/OR field/condition/value like complexe group SQL queries.

```php
use Thiktak\FilamentNestedBuilderForm\Forms\Components\NestedBuilder;
use Thiktak\FilamentNestedBuilderForm\Forms\Components\NestedSubBuilder;
// ...

        NestedBuilder::make('array_configuration')
            ->nestedConfiguration(function (NestedSubBuilder $builder) {
                $builder->blockNumbers($builder->getLevel() == 1);
                $builder->columnSpanFull(); // full width
            })
            ->nestedSchema(function (NestedSubBuilder $builder) {
                return [
                    Block::make('group')
                        ->label(sprintf('Group (%s)', $builder->getLevel()))
                        ->schema([
                            Select::make('condition')
                                ->options(['and' => 'AND', 'or' => 'OR'])
                                ->default('and')
                                ->required(),

                            $builder->importNestedBlocks('children'),
                        ])
                        ->columns(1),

                    Block::make('rule')
                        ->label(sprintf('Rule (%s)', $builder->getLevel()))
                        ->schema([
                            TextInput::make('field')
                                ->required()
                                ->columnSpan(2),

                            Select::make('sign')
                                ->options(['=', '<>', '>', '<', 'in(,)'])
                                ->default('=')
                                ->required(),

                            TextInput::make('value')
                                ->columnSpan(3),
                        ])
                        ->columns(6)
                ];
            });

```

![image](https://github.com/Thiktak/filament-nested-builder-form/assets/1201486/9d86014c-93c7-4acf-baee-97c325a9ce5c)



#### Example of advanced usage of this package:

![image](https://github.com/Thiktak/filament-nested-builder-form/assets/1201486/cd9190c7-5400-4dee-b761-e6b594c0e500)


###  How it work ?

You can achieve the same behavior with few lines of code.

1. Create a function with your builder (```$nested = fn($builder) => [...];```)
2. On your schema, pass the function that call itself inside a lambda function (```fn() => $nested($nested)```).
3. On you builder function, add a new Builder with identical logic (```Builder::make()->schema(fn() => $builder($builder))```)

Example:

```php
use Thiktak\FilamentNestedBuilderForm\Forms\Components\NestedBuilder;
use Thiktak\FilamentNestedBuilderForm\Forms\Components\NestedSubBuilder;
// ...

    public static function form(Form $form): Form
    {
        $nested = function ($builder) {
            return [
                Block::make('group')
                    ->schema([
                        Select::make('condition')
                            ->options(['and' => 'AND', 'or' => 'OR'])
                            ->default('and')
                            ->required(),

                        \Filament\Forms\Components\Builder::make('children')
                            ->schema(fn () => $builder($builder))
                    ])
                    ->columns(1),

                Block::make('rule')
                    ->schema([
                        TextInput::make('field')
                            ->required()
                            ->columnSpan(2),

                        Select::make('sign')
                            ->options(['=', '<>', '>', '<', 'in(,)'])
                            ->default('=')
                            ->required(),

                        TextInput::make('value')
                            ->columnSpan(3),
                    ])
                    ->columns(6)
            ];
        };

        
        return $form
            ->schema([
                \Filament\Forms\Components\Builder::make('array_configuration')
                    ->schema(fn () => $nested($nested))
            ]);
    }
```

Use [thiktak/filament-nested-builder-form](https://github.com/Thiktak/filament-nested-builder-form) if you want to implement a rich SQL selector & query builder.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Thiktak](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
