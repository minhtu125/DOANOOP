<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LichSuOnTap extends Model
{
    use HasFactory;
 
    protected $table = 'lich_su_on_tap';

    const KET_QUA_AGAIN = 'again';
    const KET_QUA_HARD  = 'hard';
    const KET_QUA_GOOD  = 'good';
    const KET_QUA_EASY  = 'easy';

     const KET_QUA_DUNG = [self::KET_QUA_GOOD, self::KET_QUA_EASY];

    public $timestamps = false;

     protected $fillable = [
        'nguoiDungId',
        'tuVungId',
        'ketQua',
        'thoiGian',
    ];

    protected $casts = [
        'thoiGian' => 'datetime',
    ];

    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoiDungId');
    }

     public function tuVung(): BelongsTo
    {
        return $this->belongsTo(TuVung::class, 'tuVungId');
    }

    public function scopeOfUser(Builder $query, int $nguoiDungId): Builder
    {
        return $query->where('nguoiDungId', $nguoiDungId);
    }
 
    public function scopeHomNay(Builder $query): Builder
    {
        return $query->whereDate('thoiGian', today());
    }
 
    public function scopeTrongNgay(Builder $query, int $ngay): Builder
    {
        return $query->where('thoiGian', '>=', now()->subDays($ngay)->startOfDay());
    }

    public function scopeDung(Builder $query): Builder
    {
        return $query->whereIn('ketQua', self::KET_QUA_DUNG);
    }

     public static function tinhAccuracy(int $nguoiDungId, int $ngay = 30): float
    {
        $total = self::ofUser($nguoiDungId)->trongNgay($ngay)->count();
        if ($total === 0) return 0.0;
 
        $dung = self::ofUser($nguoiDungId)->trongNgay($ngay)->dung()->count();
        return round(($dung / $total) * 100, 1);
    }

    public static function hoatDongTheoNgay(int $nguoiDungId, int $ngay = 7): \Illuminate\Support\Collection
    {
        return self::ofUser($nguoiDungId)
            ->trongNgay($ngay)
            ->selectRaw('DATE(thoiGian) as ngay, COUNT(*) as soTu')
            ->groupBy('ngay')
            ->orderBy('ngay')
            ->get();
    }

    public static function retentionTheoBoTu(int $nguoiDungId): \Illuminate\Support\Collection
    {
        return self::ofUser($nguoiDungId)
            ->with('tuVung.boTuVung')
            ->trongNgay(30)
            ->get()
            ->groupBy(fn($item) => $item->tuVung?->boTuVung?->tenBoTu ?? 'Không rõ')
            ->map(function ($items, $tenBoTu) {
                $total = $items->count();
                $dung  = $items->filter(fn($i) => in_array($i->ketQua, LichSuOnTap::KET_QUA_DUNG))->count();
                return [
                    'tenBoTu'  => $tenBoTu,
                    'total'    => $total,
                    'dung'     => $dung,
                    'phanTram' => $total > 0 ? round(($dung / $total) * 100, 1) : 0,
                ];
            })
            ->values();
    }
}