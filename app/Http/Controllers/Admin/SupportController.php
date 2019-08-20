<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Pterodactyl\Http\Controllers\Controller;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Models\Ticket;
use Pterodactyl\Models\TicketMessages;
use Pterodactyl\Http\Requests\Base\UpdateTicketFormRequest;

class SupportController extends Controller
{

    /**
     * @var \Prologue\Alerts\AlertsMessageBag
     */
    private $alert;

    /**
     * SupportController constructor.
     *
     * @param \Prologue\Alerts\AlertsMessageBag $alert
     */
    public function __construct(AlertsMessageBag $alert)
    {
        $this->alert = $alert;
    }

    /**
     * Display ticket list page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tickets = Ticket::orderBy('is_closed', 'asc')->get();
        return view('base/support/index', [
            'tickets' => $tickets,
            'admin' => true
        ]);
    }

    /**
     * Display a ticket details and reply form.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Pterodactyl\Models\Ticket
     * @return \Illuminate\View\View
     */
    public function view(Request $request, Ticket $ticket)
    {
        if(!$request->session()->has('assigned_warning') && !is_null($ticket->admin_id) && $ticket->admin_id !== $request->user()->id) {
            $this->alert->warning(trans('support.strings.warning_assigned') . ' ' . $ticket->admin->name)->flash();
            $request->session()->flash('assigned_warning', true);
            return redirect(route('admin.support.see', $ticket));
        }
        return view('base/support/ticket', [
            'ticket' => $ticket,
            'admin' => true
        ]);
    }

    /**
     * Update a ticket.
     *
     * @param \Pterodactyl\Http\Requests\Base\UpdateTicketFormRequest $request
     * @param \Pterodactyl\Models\Ticket $ticket
     * @return \Illuminate\View\View
     */
    public function update(UpdateTicketFormRequest $request, Ticket $ticket) {
        if(!is_null($ticket->admin_id) && $ticket->admin_id !== $request->user()->id) {
            $this->alert->warning(trans('support.strings.warning_assigned') . ' ' . $ticket->admin->name)->flash();
            $request->session()->flash('assigned_warning', true);
        }
        if($ticket->is_closed) {
            $this->alert->danger(trans('support.strings.reply_closed'))->flash();
        } else {
            $message = $request->input('message');
            if($message !== null && $message !== "") {
                $tm = new TicketMessages;
                $tm->fill([
                    'ticket_id' => $ticket->id,
                    'user_id' => $request->user()->id,
                    'message' => $message,
                    'is_admin' => true,
                ]);
                $tm->save();
                $this->alert->success(trans('support.strings.reply_posted'))->flash();
            }
            $close = $request->input('close');
            if($close !== null && $close === "true") {
                $ticket->is_closed = true;
                $ticket->save();
                $this->alert->success(trans('support.strings.ticket_closed'))->flash();
            }
            if($ticket->admin === null) {
                $ticket->admin_id = $request->user()->id;
                $ticket->save();
                $this->alert->info(trans('support.strings.assigned_to_you'))->flash();
            }
        }
        return redirect(route('admin.support.see', $ticket));
    }

}
