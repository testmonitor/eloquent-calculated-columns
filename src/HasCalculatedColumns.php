<?php

namespace TestMonitor\CalculatedColumns;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use TestMonitor\CalculatedColumns\Requests\CalculatedColumnsRequest;
use TestMonitor\CalculatedColumns\Exceptions\InvalidCalculatedColumn;

trait HasCalculatedColumns
{
    /**
     * @var \TestMonitor\CalculatedColumns\Requests\CalculatedColumnsRequest
     */
    public CalculatedColumnsRequest $calculatedColumnsRequest;

    /**
     * Define your calculated columns.
     *
     * @return array<string,Closure>
     */
    abstract public function calculatedColumns(): array;

    /**
     * Includes the requested calculated columns.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request|null $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCalculatedColumns(Builder $query, ?Request $request = null): Builder
    {
        $this->calculatedColumnsRequest = $request
            ? CalculatedColumnsRequest::fromRequest($request)
            : app(CalculatedColumnsRequest::class);

        $columns = $this->calculatedColumnsRequest->columns();

        $columns->each(function ($name) use ($query) {
            if (! key_exists($name, $this->calculatedColumns())) {
                throw InvalidCalculatedColumn::make($name);
            }

            $this->addCalculatedColumnToQuery(
                $query,
                $name,
                $this->calculatedColumns()[$name]
            );
        });

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @param \Closure $column
     */
    protected function addCalculatedColumnToQuery(Builder $query, string $name, Closure $column): void
    {
        $query->addSelect([
            $name => function ($query) use ($column) {
                $column($query);
            },
        ]);
    }
}
