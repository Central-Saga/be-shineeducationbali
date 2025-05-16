<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetMultipleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [            'model_type' => 'required|string|in:students,teachers,programs,assignments,materials',
            'model_id' => 'required|integer',

            // Untuk upload multiple file
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
            'file_descriptions' => 'nullable|array',
            'file_descriptions.*' => 'nullable|string',

            'storage_type' => 'nullable|string|in:local,s3,cloud,google_drive'
        ];
    }

    /**
     * Get model table name based on model type.
     *
     * @param string $modelType
     * @return string
     */    protected function getModelClass($modelType): string
    {
        return match($modelType) {
            'students' => \App\Models\Student::class,
            'teachers' => \App\Models\Teacher::class,
            'programs' => \App\Models\Program::class,
            'assignments' => \App\Models\Assignment::class,
            'materials' => \App\Models\Material::class,
            default => throw new \InvalidArgumentException('Invalid model type')
        };
    }
}