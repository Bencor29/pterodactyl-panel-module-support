<?php

namespace Pterodactyl\Http\Controllers\Base;

use Illuminate\Http\Request;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Base\StoreTicketFormRequest;
use Pterodactyl\Http\Requests\Base\UpdateTicketFormRequest;
use Pterodactyl\Models\Ticket;
use Pterodactyl\Models\TicketMessages;
use Prologue\Alerts\AlertsMessageBag;

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
        $tickets = Ticket::where('user_id', auth()->user()->id)->get();
        return view('base/support/index', [
            'tickets' => $tickets
        ]);
    }

    /**
     * Returns ticket creation form.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $servers = $request->user()->servers;
        return view('base/support/new', [
            'servers' => $servers
        ]);
    }

    /**
     * Store a new ticket.
     *
     * @param \Pterodactyl\Http\Requests\Base\StoreTicketFormRequest $request
     * @return \Illuminate\View\View
     */
    public function store(StoreTicketFormRequest $request)
    {
        $subject = $request->input('subject');
        $server_id = $request->input('server_id');
        $message = $request->input('message');

        if($server_id == 0) {
            $server_id = null;
        }

        $t = new Ticket;
        $t->fill([
            'subject' => $subject,
            'user_id' => $request->user()->id,
            'server_id' => $server_id,
            'admin_id' => null,
            'is_closed' => false,
        ]);

        $t->save();

        $tm = new TicketMessages;
        $tm->fill([
            'ticket_id' => $t->id,
            'user_id' => $request->user()->id,
            'message' => $message,
            'is_admin' => false,
        ]);

        $tm->save();

        $this->alert->success(trans('support.strings.posted'))->flash();

        return redirect(route('index.support'));
    }

    /**
     * Display a ticket.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Pterodactyl\Models\Ticket $ticket
     * @return \Illuminate\View\View
     */
    public function view(Request $request, Ticket $ticket)
    {
        if($ticket->user_id !== $request->user()->id) {
            $this->alert->danger(trans('support.strings.not_your'))->flash();
            return redirect(route('index.support'));
        }
        return view('base/support/ticket', ['ticket' => $ticket]);
    }

    /**
     * Update a ticket.
     *
     * @param \Pterodactyl\Http\Requests\Base\UpdateTicketFormRequest $request
     * @param \Pterodactyl\Models\Ticket $ticket
     * @return \Illuminate\View\View
     */
    public function update(UpdateTicketFormRequest $request, Ticket $ticket) {
        if($ticket->user_id !== $request->user()->id) {
            $this->alert->danger(trans('support.strings.not_your'))->flash();
            return redirect(route('index.support'));
        } elseif($ticket->is_closed) {
            $this->alert->danger(trans('support.strings.reply_closed'))->flash();
        } else {
            $message = $request->input('message');
            if($message !== null && $message !== "") {
                $tm = new TicketMessages;
                $tm->fill([
                    'ticket_id' => $ticket->id,
                    'user_id' => $request->user()->id,
                    'message' => $message,
                    'is_admin' => false,
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
        }
        return redirect(route('index.support.see', $ticket));
    }

}
