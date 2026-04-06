<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
     use HasFactory;
 
    protected $table = 'thong_baos';
 
    // Loại thông báo
    const LOAI_NHAC_HOC      = 'nhac_hoc';
    const LOAI_ON_TAP        = 'on_tap';
    const LOAI_STREAK        = 'streak';
    const LOAI_HE_THONG      = 'he_thong';
 
    protected $fillable = [
        'nguoiDungId',
        'loai',
        'noiDung',
        'thoiGianGui',
        'daDoc',
    ];
 
    protected $casts = [
        'thoiGianGui' => 'datetime',
        'daDoc'       => 'boolean',
    ];

    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoiDungId');
    }

    public function scopeChuaDoc(Builder $query): Builder
    {
        return $query->where('daDoc', false);
    }

    public function scopeByLoai(Builder $query, string $loai): Builder
    {
        return $query->where('loai', $loai);
    }

    public function scopeMoiNhat(Builder $query): Builder
    {
        return $query->orderBy('thoiGianGui', 'desc');
    }

    public function danhDauDaDoc(): void
    {
        $this->update(['daDoc' => true]);
    }
 
    /** Tạo nhanh thông báo nhắc học */
    public static function taoNhacHoc(int $nguoiDungId, string $noiDung): self
    {
        return self::create([
            'nguoiDungId'  => $nguoiDungId,
            'loai'         => self::LOAI_NHAC_HOC,
            'noiDung'      => $noiDung,
            'thoiGianGui'  => now(),
            'daDoc'        => false,
        ]);
    }

    public static function taoNhacOnTap(int $nguoiDungId, int $soTu): self
    {
        return self::create([
            'nguoiDungId'  => $nguoiDungId,
            'loai'         => self::LOAI_ON_TAP,
            'noiDung'      => "Bạn có {$soTu} từ cần ôn tập hôm nay!",
            'thoiGianGui'  => now(),
            'daDoc'        => false,
        ]);
    }
}