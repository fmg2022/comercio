<?php

namespace App\Traits;

use Livewire\Attributes\Url;

trait HasSortableTable
{
    #[Url(history: true)]
    public string $sortDir = 'desc';

    public function sort(string $field): void
    {
        $field = $this->sortAliases[$field] ?? $field;

        if (! array_key_exists($field, $this->sorts)) {
            return;
        }

        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $field;
            $this->sortDir = $this->sorts[$field]['default'] ?? 'asc';
        }

        $this->afterSort();
    }

    public function resetSort(): void
    {
        $first = array_key_first($this->sorts) ?? $this->sortBy;

        $this->sortBy  = $first;
        $this->sortDir = $this->sorts[$first]['default'] ?? 'desc';

        $this->afterSort();
    }

    protected function afterSort(): void
    {
        $this->resetPage();
    }

    public function sortStatus(): string
    {
        $field = $this->sortAliases[$this->sortBy] ?? $this->sortBy;
        $label = $this->sorts[$field]['label'] ?? $this->sortBy;
        $dir   = $this->sortDir === 'asc' ? 'Ascendente' : 'Descendente';

        return "Ordenado por {$label} · {$dir}";
    }

    protected function sortColumn(string $field): string
    {
        $field = $this->sortAliases[$field] ?? $field;

        return $this->sorts[$field]['column'] ?? $field;
    }

    protected function isSortable(string $field): bool
    {
        $field = $this->sortAliases[$field] ?? $field;

        return array_key_exists($field, $this->sorts);
    }
}
