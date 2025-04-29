<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Asset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'file_path',
        'size_kb',
        'upload_date',
        'description',
        'storage_type',
        'assetable_id',
        'assetable_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'upload_date' => 'datetime',
        'size_kb' => 'integer',
    ];

    /**
     * Get the parent entity that owns the asset.
     */
    public function assetable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include assets of a specific assetable type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('assetable_type', $type);
    }

    /**
     * Scope a query to only include assets with a specific storage type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $storageType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStorage($query, string $storageType)
    {
        return $query->where('storage_type', $storageType);
    }

    /**
     * Get the full storage path of the asset based on storage type.
     *
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        $basePath = match($this->storage_type) {
            'local' => 'storage/',
            's3' => 'https://s3.example.com/',
            'cloud' => 'https://cloud.example.com/',
            'google_drive' => 'https://drive.google.com/file/',
            default => 'storage/',
        };

        return $basePath . $this->file_path;
    }

    /**
     * Get the file extension.
     *
     * @return string|null
     */
    public function getFileExtensionAttribute(): ?string
    {
        $pathInfo = pathinfo($this->file_path);
        return $pathInfo['extension'] ?? null;
    }

    /**
     * Check if the file is an image.
     *
     * @return bool
     */
    public function getIsImageAttribute(): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    /**
     * Check if the file is a document.
     *
     * @return bool
     */
    public function getIsDocumentAttribute(): bool
    {
        $documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];
        return in_array(strtolower($this->file_extension), $documentExtensions);
    }
}
