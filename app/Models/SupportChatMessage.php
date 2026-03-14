<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChatMessage extends Model
{
    use HasFactory;

    protected $table = 'support_chat_messages';

    protected $fillable = [
        'chat_token',
        'sender_type',
        'name',
        'email',
        'message',
        'file_path',
        'file_name',
        'file_mime',
    ];
}

