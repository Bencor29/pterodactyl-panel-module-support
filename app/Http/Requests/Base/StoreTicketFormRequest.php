<?php
/**
 * Pterodactyl - Panel - Support Plugin
 * Copyright (c) 2019 Benjamin Cornou <benjamin@cornou.dev>.
 *
 * This software is licensed under the terms of the MIT license.
 * https://opensource.org/licenses/MIT
 */

namespace Pterodactyl\Http\Requests\Base;

use Pterodactyl\Models\Ticket;
use Pterodactyl\Models\Server;
use Illuminate\Validation\Rule;
use Pterodactyl\Http\Requests\FrontendUserFormRequest;

class StoreTicketFormRequest extends FrontendUserFormRequest
{

    /**
     * Rules to be applied to this request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required|string|max:191',
            'server_id' => 'required|int|min:0',
            'message' => 'required|string',
        ];
    }

    /**
     * Run validation after the rules above have been applied.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $server_id = intval($this->input('server_id'));

            if($server_id !== 0) {
                $server = Server::find($server_id);
                if($server === null) {
                    $validator->errors()->add('server_id', trans('support.strings.unknown_server'));
                }
                if($server->owner_id !== $this->user()->id) {
                    $validator->errors()->add('server_id', trans('support.strings.not_server_owner'));
                }
            }
        });
    }
}
