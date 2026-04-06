<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TuVung extends Model
{
    use HasFactory;

    protected $table = 'tu_vung';
 
    protected $fillable = [
        'boTuVungId',
        'tu',
        'phienAm',
        'nghiaTiengViet',
        'moTaTiengAnh',
        'viDu',
        'collocation',
        'tuDongNghia',
        'ghiChu',
    ];

     public function boTuVung(): BelongsTo
    {
        return $this->belongsTo(BoTuVung::class, 'boTuVungId');
    }

    public function tienDoHocs(): HasMany
    {
        return $this->hasMany(TienDoHoc::class, 'tuVungId');
    }

     public function lichSuOnTaps(): HasMany
    {
        return $this->hasMany(LichSuOnTap::class, 'tuVungId');
    }

     public function tienDoHocOfUser(int $nguoiDungId): HasOne
    {
        return $this->hasOne(TienDoHoc::class, 'tuVungId')
                    ->where('nguoiDungId', $nguoiDungId);
    }

     public function scopeInBoTu(Builder $query, int $boTuVungId): Builder
    {
        return $query->where('boTuVungId', $boTuVungId);
    }

    public function scopeCanOnTap(Builder $query, int $nguoiDungId): Builder
    {
        return $query->whereHas('tienDoHocs', function ($q) use ($nguoiDungId) {
            $q->where('nguoiDungId', $nguoiDungId)
              ->where('ngayOnTiep', '<=', now());
        });
    }

     public function scopeChuaHoc(Builder $query, int $nguoiDungId): Builder
    {
        return $query->whereDoesntHave('tienDoHocs', function ($q) use ($nguoiDungId) {
            $q->where('nguoiDungId', $nguoiDungId);
        });
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('tu', 'LIKE', "%{$keyword}%")
              ->orWhere('nghiaTiengViet', 'LIKE', "%{$keyword}%")
              ->orWhere('moTaTiengAnh', 'LIKE', "%{$keyword}%");
        });
    }

     public function getOrCreateTienDoHoc(int $nguoiDungId): TienDoHoc
    {
        return TienDoHoc::firstOrCreate(
            ['nguoiDungId' => $nguoiDungId, 'tuVungId' => $this->id],
            [
                'easeFactor' => TienDoHoc::DEFAULT_EASE_FACTOR,
                'interval'   => 0,
                'repetition' => 0,
                'ngayOnTiep' => now(),
            ]
        );
    }
}