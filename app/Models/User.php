<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // 1. Import ini
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasTranslations;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    // Relasi: Penulis punya banyak berita
    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    // Relasi: User punya banyak komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Helper check admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Helper untuk mengambil inisial nama (Untuk Avatar).
     * Contoh: "Budi Santoso" -> "BS", "Admin" -> "AD"
     */
    public function initials()
    {
        $words = explode(' ', $this->name);

        // Jika nama terdiri dari 2 kata atau lebih (Contoh: Budi Santoso)
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }

        // Jika hanya 1 kata (Contoh: Admin), ambil 2 huruf pertama
        return strtoupper(substr($this->name, 0, 2));
    }
}
