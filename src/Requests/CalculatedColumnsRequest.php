<?php

namespace TestMonitor\CalculatedColumns\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CalculatedColumnsRequest extends Request
{
    /**
     * @var string
     */
    protected static string $calculatedColumnValueDelimiter = ',';

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Symfony\Component\HttpFoundation\Exception\BadRequestException
     * @throws \RuntimeException
     *
     * @return \TestMonitor\CalculatedColumns\Requests\CalculatedColumnsRequest
     */
    public static function fromRequest(Request $request): self
    {
        return static::createFrom($request, new self);
    }

    /**
     * @return string
     */
    public static function getCalculatedColumnValueDelimiter(): string
    {
        return static::$calculatedColumnValueDelimiter;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function columns(): Collection
    {
        $columns = $this->input(config('calculated-columns.parameter')) ?? '';

        if (!is_array($columns) && !is_null($columns)) {
            $columns = explode(static::getCalculatedColumnValueDelimiter(), $columns);
        }

        return collect($columns)->filter();
    }
}
