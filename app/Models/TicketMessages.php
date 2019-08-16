<?php

namespace Pterodactyl\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMessages extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'id_admin',
    ];
}
