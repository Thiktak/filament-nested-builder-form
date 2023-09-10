<?php

namespace Thiktak\FilamentNestedBuilderForm\Forms\Components;

use Filament\Forms\Components\Builder;

class NestedSubBuilder extends Builder
{
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
                ]
            ));

        return $builder;
    }

    /*protected Collection $debug;
    protected int $level = 1;
    protected Closure | string | null $nestedDefinition = 'default';

    function importNestedDefinition(Closure | string | null $nestedDefinition = 'default'): static
    {
        $this->nestedDefinition = $nestedDefinition;
        return $this;
    }

    function getImportNestedDefinition(): mixed
    {
        return $this->nestedDefinition;
    }

    public function getChildComponents(): array
    {
        $this->debug = collect();
        $nestedConfig = $this->getRootNestedBuilder();

        /*if (count($this->debug) > 8) {
            dd(
                $nestedConfig->get('root'),
                $nestedConfig->get('level'),
                $nestedConfig->get('root')
                    ->getNestedNamedChildComponents(
                        $this->nestedDefinition
                    )
            );
        }*-/

        if ($nestedConfig && $nestedConfig->has('root')) {
            // Apply default configuration for each Builder
            /*$nestedConfig
                ->get('root')
                ->getNestedConfiguration(
                    $nestedConfig->get('level')
                );*-/

            return $this->evaluate(
                $nestedConfig
                    ->get('root')
                    ?->getNestedNamedChildComponents(
                        $this->nestedDefinition
                    )
            ) ?? [];
        }
        return [];
    }


    protected function getRootNestedBuilderIterator($data, $object): Collection
    {
        if (!is_object($object)) {
            return $data;
        }

        if ($data->count() > 999) {
            return $data;
        }

        $data->add($object);

        if (method_exists($object, 'getContainer')) {
            $this->debug->add(['method_exists getContainer', $object]);
            return $this->getRootNestedBuilderIterator($data, $object->getContainer());
        }
        //
        else if (method_exists($object, 'getParentComponent')) {
            $this->debug->add(['method_exists getParentComponent', $object]);
            return $this->getRootNestedBuilderIterator($data, $object->getParentComponent());
        }

        return $data;
    }

    public function getRootNestedBuilder(): mixed
    {
        $recursive = $this->getRootNestedBuilderIterator(collect(), $this)
            // Keep only what we need
            ->filter(function ($step) {
                return $step instanceof \Filament\Forms\Components\Builder or $step instanceof NestedBuilder;
            });

        //dd($recursive);

        return collect([
            'root'  => $recursive
                ->filter(fn ($step) => $step instanceof NestedBuilder)
                ->first(),

            'level' => $recursive
                ->count()
        ]);
    }

    public function a()
    {
        $this->debug = collect();
        $continue = true;
        $block = $this;
        $this->level = 1;

        while ($continue) {

            if (is_null($block) || $this->level >= 999) {
                $continue = false;
                break;
            }

            //dd($this, $block, get_class($block), is_a($block, \Filament\Forms\Components\Builder::class));
            $this->debug->add([
                'class' => get_class($block),
                'level' => $this->level,
                'object' => $block,
            ]);

            switch (true) {
                case is_a($block, NestedBuilder::class):
                    $continue = false;
                    return collect([
                        'root' => $block,
                        'level' => $this->level
                    ]);
                    break;

                case is_a($block, \Filament\Forms\Components\Builder::class);
                    $continue = true;
                    $this->level++;
                    break;

                case is_a($block, \Filament\Forms\Components\Builder\Block::class);
                    $continue = true;
                    break;

                case is_a($block, \Filament\Forms\Components\Component::class); // protect other objects
                    $continue = true;
                    break;

                default:
                    $continue = false;
                    break;
            }

            $this->debug->add($continue);

            $block = $block?->getContainer()?->getParentComponent();
        }

        return [];
    }*/
}
