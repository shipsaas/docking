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

    /**
     * List of searchable columns
     * Note: it will do LIKE %keyword% search
     *
     * @return string[]
     */
    protected function getAllowedSearchColumns(): array
    {
        return [];
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

        $wantsSearchByKeyword = !empty($this->getAllowedSearchColumns())
            && $this->filled('search');

        $builder->orderBy(
            $this->input('sort_by') ?: 'created_at',
            $this->input('sort_direction') ?: 'ASC'
        )->when(
            $wantsSearchByKeyword,
            fn ($query) => $query->where($this->searchByKeyword(...))
        );

        return $builder;
    }

    private function searchByKeyword(Builder $query): void
    {
        $keyword = '%' . $this->validated('search') . '%';

        array_map(function (string $column) use ($query, $keyword) {
            $query->orWhere($column, 'LIKE', $keyword);
        }, $this->getAllowedSearchColumns());
    }
}
