<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_REJECTED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING = 2;

    /**
     * Status mapping array
     */
    public static $statusMap = [
        self::STATUS_REJECTED => 'ditolak',
        self::STATUS_APPROVED => 'disetujui',
        self::STATUS_PENDING => 'menunggu konfirmasi'
    ];

    /**
     * Convert numeric status to string
     */
    public static function getStatusString(int $status): string
    {
        return self::$statusMap[$status] ?? throw new \InvalidArgumentException('Invalid status code');
    }

    /**
     * Convert string status to numeric
     */
    public static function getStatusCode(string $status): int
    {
        return array_flip(self::$statusMap)[$status] ?? throw new \InvalidArgumentException('Invalid status string');
    }

    /**
     * Get status as numeric value
     */
    public function getStatusCodeAttribute(): int
    {
        return self::getStatusCode($this->status);
    }

    /**
     * Nama tabel yang terkait dengan model ini.
     *
     * @var string
     */
    protected $table = 'leaves';

    /**
     * Kolom-kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'user_type',
        'deduction_amount',
    ];

    /**
     * Relasi ke model User (satu user dapat memiliki banyak leaves).
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
