<?php

namespace App\JsonApi\V1\Users;

use Illuminate\Validation\Rule;
use CloudCreativity\LaravelJsonApi\Rules\HasMany;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = ['name', 'email', 'description',  'created_at'];

    /**
     * The filters a client is allowed send.
     *
     * @var string[]|null
     *      the allowed filters, an empty array for none allowed, or null to allow all.
     */
    protected $allowedFilteringParameters = ['name', 'email'];

    /**
     * Get resource validation rules.
     *
     * @param mixed|null $record
     *      the record being updated, or null if creating a resource.
     * @return array
     */
    protected function rules($record, array $data): array
    {
        if ($record) {
            return [
                'id' => 'not_in:1',
                'name' => 'sometimes',
                'email' => [
                    'sometimes',
                    'email',
                    Rule::unique('users')->ignore($record->id)
                ],
                'description' => 'sometimes',
                'password' => 'sometimes|confirmed|regex:/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/'
            ];
        }

        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')
            ],
            'password' => 'required|confirmed|regex:/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/'
        ];
    }

    /**
     * Get query parameter validation rules.
     *
     * @return array
     */
    protected function queryRules(): array
    {
        return [];
    }

}
