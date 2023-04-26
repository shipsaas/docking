<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class AbstractIndexRequest extends FormRequest
{
    /**
     * Eloquent Model related to the Index listing
     */
    abstract protected function getModel(): Model;

    /**
     * List of sortable columns
     *
     * @return string[]
     */
    protected function getAllowedSortColumns(): array
    {
        return ['created_at'];
    }

    public function rules(): array
    {
        return [
            'sort_by' => [
                'nullable',
                Rule::in($this->getAllowedSortColumns()),
            ],
            'sort_direction' => [
                'nullable',
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],
            'search' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ];
    }

    public function getLimit(): int
    {
        return $this->integer('limit') ?: 20;
    }

    public function buildQueryBuilder(): Builder
    {
        $builder = $this->getModel()->newQuery();

        $builder->orderBy(
            $this->input('sort_by') ?: 'created_at',
            $this->input('sort_direction') ?: 'ASC'
        );

        return $builder;
    }
}
