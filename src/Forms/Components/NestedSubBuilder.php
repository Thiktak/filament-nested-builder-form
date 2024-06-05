<?php

namespace Thiktak\FilamentNestedBuilderForm\Forms\Components;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Concerns\CanBeCollapsed;

class NestedSubBuilder extends Builder
{

    use CanBeCollapsed;
    public NestedBuilder $nestedBuilder;

    public int $level = 1;

    public function nestedBuilder(NestedBuilder $nestedBuilder): self
    {
        $this->nestedBuilder = $nestedBuilder;

        return $this;
    }

    public function getNestedBuilder(): NestedBuilder
    {
        return $this->nestedBuilder ?? $this;
    }

    public function level(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getChildComponents(): array
    {
        if (! $this->childComponents) {
            $this->childComponents(
                (array) $this->evaluate(
                    $this->getNestedBuilder()->getNestedNamedChildComponents(),
                    [
                        'builder' => $this,
                        'parent' => $this->getNestedBuilder(),
                    ]
                )
            );
        }

        if ($this->childComponents) {
            return parent::getChildComponents();
        }

        return [];
    }

    public function importNestedBlocks($make, string $name = null): Builder
    {
        $nestedComponents = $this->getNestedBuilder()->getNestedNamedChildComponents($name ?: 'default');

        $builder = NestedSubBuilder::make($make)
            ->nestedBuilder($this->getNestedBuilder())
            ->level($this->getLevel() + 1);

        // Call nestedConfiguration for each block created
        // -> Use $builder->getLevel() to know where you are
        $this->getNestedBuilder()
            ->getNestedConfiguration($builder);

        // Add schema
        $builder = $builder
            ->schema(fn () => $this->evaluate(
                $nestedComponents,
                [
                    'builder' => $builder,
                    'parent' => $this->getNestedBuilder(),
                ]
            ));

        return $builder;
    }
}
