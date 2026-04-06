<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserStats extends Model
{
    use HasFactory;
 
    protected $table = 'user_stats';
 
    protected $fillable = [
        'nguoiDungId',
        'soTuDaHoc',
        'streak',
        'accuracy',
        'lastStudiedAt',
    ];
 
    protected $casts = [
        'soTuDaHoc'     => 'integer',
        'streak'        => 'integer',
        'accuracy'      => 'float',
        'lastStudiedAt' => 'datetime',
    ];

    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoiDungId');
    }

     public function incrementSoTu(int $amount = 1): void
    {
        $this->increment('soTuDaHoc', $amount);
    }

    public function updateAccuracy(float $sessionAccuracy, float $alpha = 0.3): void
    {
        $this->accuracy = ($this->accuracy * (1 - $alpha)) + ($sessionAccuracy * $alpha);
        $this->save();
    }

    public function updateStreak(): void
    {
        $today     = now()->startOfDay();
        $lastStudy = $this->lastStudiedAt?->startOfDay();
 
        if ($lastStudy === null || $lastStudy->lt($today->copy()->subDay())) {
            // Chưa học hoặc bỏ ngày → reset
            $this->streak = 1;
        } elseif ($lastStudy->lt($today)) {
            // Học ngày liền kề → tăng streak
            $this->streak += 1;
        }
        // Cùng ngày → không thay đổi streak
 
        $this->lastStudiedAt = now();
        $this->save();
    }

     public function estimateLevel(): string
    {
        return match(true) {
            $this->soTuDaHoc < 500   => 'A1',
            $this->soTuDaHoc < 1000  => 'A2',
            $this->soTuDaHoc < 2000  => 'B1',
            $this->soTuDaHoc < 4000  => 'B2',
            $this->soTuDaHoc < 7000  => 'C1',
            default                  => 'C2',
        };
    }
}