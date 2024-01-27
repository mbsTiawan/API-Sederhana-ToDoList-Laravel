<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class toDoListModel extends Model
{
    use HasFactory;

    protected $fillable = [

        'title',
        'description',
        'priority',
        'due_date',
        'completed',
        'status'
    ];

    public function getStatusAttribute(): string
    {
        if (now()->greaterThan($this->attributes['due_date'])) {
            return 'Tenggat Waktu Habis';
        }

        return $this->attributes['completed'] ? 'Selesai' : 'Belum Selesai';
    }
}
