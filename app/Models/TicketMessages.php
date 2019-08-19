<?php

namespace Pterodactyl\Models;

use Illuminate\Database\Eloquent\Model;
use Pterodactyl\Models\User;

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

    /**
     * Gets information for the user associated with this message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gets the sender name or 'you' if the sender is the current user.
     *
     * @return string
     */
    public function sender()
    {
        return auth()->user()->id === $this->user_id ? trans('support.messages.you') : $this->user->name;
    }
}
