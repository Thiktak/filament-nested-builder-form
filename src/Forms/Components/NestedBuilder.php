<?php

namespace Thiktak\FilamentNestedBuilderForm\Forms\Components;

use Closure;

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
            'builder' => $builder,
            'parent' => $this,
        ]);

        return $this;
    }
}
