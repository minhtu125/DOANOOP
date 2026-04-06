<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoTuVung extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $table = 'bo_tu_vung';
 
    const TAGS = [
        'ielts', 'toeic', 'business', 'academic',
        'travel', 'daily', 'speaking', 'writing',
    ];
 
    protected $fillable = [
        'tenBoTu',
        'moTa',
        'nguoiDungId',
        'tags',
        'laCong',
        'hinhAnh',
    ];
 
    protected $casts = [
        'tags'   => 'array',
        'laCong' => 'boolean',
    ];

     public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoiDungId');
    }

     public function tuVungs(): HasMany
    {
        return $this->hasMany(TuVung::class, 'boTuVungId');
    }

    public function getSoTuAttribute(): int
    {
        return $this->tuVungs()->count();
    }

     public function getSoTuDaHocAttribute(): int
    {
        return $this->tuVungs()
            ->whereHas('tienDoHocs', fn($q) => $q->where('repetition', '>', 0))
            ->count();
    }

    public function getPhanTramHoanThanhAttribute(): float
    {
        $total = $this->soTu;
        if ($total === 0) return 0;
        return round(($this->soTuDaHoc / $total) * 100, 1);
    }

    public function getSoTuCanOnAttribute(): int
    {
        return $this->tuVungs()
            ->whereHas('tienDoHocs', fn($q) => $q->where('ngayOnTiep', '<=', now()))
            ->count();
    }

    public function scopeOfUser(Builder $query, int $nguoiDungId): Builder
    {
        return $query->where('nguoiDungId', $nguoiDungId);
    }
 
    public function scopeCong(Builder $query): Builder
    {
        return $query->where('laCong', true);
    }
 
    public function scopeByTag(Builder $query, string $tag): Builder
    {
        return $query->whereJsonContains('tags', $tag);
    }

     public function importTuVungs(array $rows): int
    {
        $imported = 0;
        foreach ($rows as $row) {
            TuVung::create([
                'boTuVungId'      => $this->id,
                'tu'              => $row['Word'] ?? '',
                'phienAm'         => $row['Pronunciation'] ?? null,
                'nghiaTiengViet'  => $row['Meaning'] ?? '',
                'moTaTiengAnh'    => $row['Description'] ?? null,
                'viDu'            => $row['Example'] ?? null,
                'collocation'     => $row['Collocation'] ?? null,
                'tuDongNghia'     => $row['Related words'] ?? null,
                'ghiChu'          => $row['Note'] ?? null,
            ]);
            $imported++;
        }
        return $imported;
    }
}