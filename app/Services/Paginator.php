<?php

namespace App\Services;

class Paginator
{
    private int $current_page;
    private int $totalRecords;


    private int $itemsPerPage;
    private string $modelClass;

    public function __construct(string $modelClass, int $current_page = 1, int $itemsPerPage = 10)
    {
        $this->modelClass = $modelClass;
        $this->current_page = $current_page;
        $this->itemsPerPage = $itemsPerPage;
        $this->totalRecords = $modelClass::count();
    }

    public function getCurrentPage(): int
    {
        return $this->current_page;
    }

    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    public function getTotalPages(): int
    {
        return ceil($this->totalRecords / $this->itemsPerPage);
    }

    public function getItems(): array|bool
    {
        $offset = ($this->current_page - 1) * $this->itemsPerPage;
        return $this->modelClass::all($this->itemsPerPage, $offset);
    }

    public function hasNextPage(): bool
    {
        return $this->current_page < $this->getTotalPages();
    }

    public function hasPreviousPage(): bool
    {
        return $this->current_page > 1;
    }

    public function nextPage(): int
    {
        return $this->current_page + 1;
    }

    public function previousPage(): int
    {
        return $this->current_page - 1;
    }
}