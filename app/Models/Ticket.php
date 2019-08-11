<?php

namespace Pterodactyl\Models;

use Illuminate\Database\Eloquent\Model;
use Pterodactyl\Models\Server;

class Ticket extends Model
{

    /**
     * Gets information for the server associated with this allocation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
