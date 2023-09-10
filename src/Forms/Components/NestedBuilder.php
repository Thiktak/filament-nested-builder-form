<?php

namespace Thiktak\FilamentNestedBuilderForm\Forms\Components;

use Closure;
use Illuminate\Support\Collection;

class NestedBuilder extends NestedSubBuilder
{
    public ?Closure $nestedConfiguration = null;

    protected array $nestedSchemas = [];

    protected NestedSubBuilder $nestedSubBuilder;

    public function nestedSchema(Closure $components, string $name = 'default'): static
    {
        $this->nestedSchemas[$name] = $components;

        return $this;
    }

    /*public function incrementNestedNamedChildComponents(string $name = 'default', string $fallbackName = 'default'): mixed
    {
        if (isset($this->nestedSchemas[$name])) {
            return ++$this->nestedSchemas[$name];
        } elseif (isset($this->nestedSchemas[$fallbackName])) {
            return ++$this->nestedSchemas[$fallbackName];
        }
    }*/

    public function getNestedSubBuilder(): NestedSubBuilder
    {
        return $this->nestedSubBuilder ?? $this;
    }

    public function getNestedNamedChildComponents(string $name = 'default', string $fallbackName = 'default'): mixed
    {
        if (isset($this->nestedSchemas[$name])) {
            return $this->nestedSchemas[$name];
        } elseif (isset($this->nestedSchemas[$fallbackName])) {
            return $this->nestedSchemas[$fallbackName];
        }

        return [];
    }

    public function nestedConfiguration(Closure $nestedConfiguration = null): static
    {
        $this->nestedConfiguration = $nestedConfiguration;

        $this->getNestedConfiguration($this->getNestedSubBuilder());

        return $this;
    }

    public function getNestedConfiguration(NestedSubBuilder $builder): static
    {
        $this->evaluate($this->nestedConfiguration, [
            'builder' => $builder ?? $this,
        ]);

        return $this;
    }

    /*protected function getRootNestedBuilderIterator($data, $object): Collection
    {
        if (!is_object($object)) {
            return $data;
        }

        if ($data->count() > 999) {
            return $data;
        }

        $data->add($object);

        if (method_exists($object, 'getContainer')) {
            //$this->debug->add(['method_exists getContainer', $object]);
            return $this->getRootNestedBuilderIterator($data, $object->getContainer());
        }
        //
        else if (method_exists($object, 'getParentComponent')) {
            //$this->debug->add(['method_exists getParentComponent', $object]);
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

        return collect([
            'root'  => $recursive
                ->filter(fn ($step) => $step instanceof NestedBuilder)
                ->first(),

            'level' => $recursive
                ->count()
        ]);
    }*/
}
