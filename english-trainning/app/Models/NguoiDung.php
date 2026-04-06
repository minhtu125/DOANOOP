<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ulluminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NguoiDung extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table ='nguoi_dung';

    protected $fillable = [
       'hoTen',
        'email',
        'matKhau',
        'mucTieu',
        'capDo',
        'google_id',
        'avatar',
        'email_verified_at',
    ];

    protected $hidden = [
        'matKhau',
        'remember_token',
        'google_id',
    ];

     protected $casts = [
        'email_verified_at' => 'datetime',
        'matKhau'           => 'hashed',
    ];

    public function getAuthPassword(): string
    {
        return $this->matKhau;
    }

    public function stas():HasOne
    {
        return $this->hasOne(Stas::class);
    }

     public function thongBaos(): HasMany
    {
        return $this->hasMany(ThongBao::class, 'nguoiDungId');
    }

    public function boTuVungs(): HasMany
    {
        return $this->hasMany(BoTuVung::class, 'nguoiDungId');
    }

     public function tienDoHocs(): HasMany
    {
        return $this->hasMany(TienDoHoc::class, 'nguoiDungId');
    }

     public function lichSuOnTaps(): HasMany
    {
        return $this->hasMany(LichSuOnTap::class, 'nguoiDungId');
    }

    public function getSoTuCanOnAttribute(): int
    {
        return $this->tienDoHocs()
            ->where('ngayOnTiep', '<=', now())
            ->count();
    }

    public function getStreakAttribute(): int
    {
        return $this->stats?->streak ?? 0;
    }

     public function getAccuracyAttribute(): float
    {
        return $this->stats?->accuracy ?? 0.0;
    }

     public function scopeByLevel($query, string $capDo)
    {
        return $query->where('capDo', $capDo);
    }
}