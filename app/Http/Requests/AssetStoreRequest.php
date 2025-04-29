<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class AssetStoreRequest extends FormRequest
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
        return [
            'model_type' => 'required|string|in:students,teachers,programs,assignments,materials',
            'model_id' => 'required|integer|exists:' . $this->getModelTable($this->model_type) . ',id',
            'file' => 'required_without:file_path|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120',
            'file_path' => 'required_without:file|string',
            'description' => 'nullable|string',
            'storage_type' => 'nullable|string|in:local,s3,cloud,google_drive',
        ];
    }


    /**
     * Get model table name based on model type.
     *
     * @param string $modelType
     * @return string
     */
    protected function getModelTable($modelType)
    {
        $tables = [
            'students' => 'students',
            'teachers' => 'teachers',
            'programs' => 'programs',
            'assignments' => 'assignments',
            'materials' => 'materials',
        ];

        return $tables[$modelType] ?? 'students';
    }
}
