{{-- Pterodactyl - Panel - Support Plugin --}}
{{-- Copyright (c) 2019 Benjamin Cornou <benjamin@cornou.dev> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}

<?php
    $admin = isset($admin) ?: false;
?>

@extends('layouts.' . ($admin ? 'admin' : 'master'))

@if($admin)
    @section('content-header')
        <h1>@lang('support.base.header')<small>@lang('support.admin.header_sub')</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}">@lang('strings.home')</a></li>
            <li class="active">@lang('support.strings.support')</li>
        </ol>
    @endsection
@else
    @section('content-header')
        <h1>@lang('support.base.header')<small>@lang('support.base.header_sub')</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('index') }}">@lang('strings.home')</a></li>
            <li class="active">@lang('support.strings.support')</li>
        </ol>
    @endsection
@endif

@section('title')
    @lang('support.base.header')
@endsection

@section('scripts')
    @parent
    <style>
        .d-inline-block {
            display: inline-block;
        }
        .ml {
            margin-left: 1em;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('support.strings.ticket')</h3>
            </div>
            <div class="box-body row">
                <div class="form-group col-xs-6">
                    <label for="subject">@lang('support.list.subject')</label>
                    <input type="text" id="subject" class="form-control"
                        value="{{ $ticket->subject }}" disabled />
                </div>
                <div class="form-group col-xs-6">
                    <label for="srv_id">@lang('support.list.server')</label>
                    <input type="text" id="srv_id" class="form-control"
                        value="{{ ($ticket->server !== null) ? $ticket->server->name .' ('. $ticket->server->uuidShort .')' : trans('support.list.noserver') }}" disabled />
                </div>
            </div>
            @if(!$ticket->is_closed)
                <div class="box-footer">
                    <form action="{{ route(($admin ? 'admin' : 'index') . '.support.see', $ticket) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <textarea name="message" placeholder="@lang('support.strings.post_reply')" class="form-control" rows="7"></textarea>
                        </div>
                        <div class="text-right">
                            <div class="checkbox checkbox-primary no-margin-bottom text-left d-inline-block">
                                <input type="checkbox" name="close" id="close_ticket" value="true" />
                                <label for="close_ticket" class="strong">@lang('support.strings.close_ticket')</label>
                            </div>
                            <input type="submit" class="ml btn btn-primary" value="@lang('support.strings.send')" />
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@foreach($ticket->messages->sortByDesc('id') as $message)
    <div class="row">
    @if(!$message->is_admin)
        <div class="col-xs-2">&nbsp;</div>
    @endif
    <div class="col-xs-10">
        <div class="box box-{{ $message->is_admin ? 'warning' : 'info' }}">
            <div class="box-body">
                {!! nl2br(e($message->message)) !!}
            </div>
            <div class="box-footer text-muted">
                @lang('support.messages.sent_by') {{ $message->sender() }}, {{ date(trans('support.messages.date_format'), strtotime($message->created_at)) }}
            </div>
        </div>
    </div>
    @if($message->is_admin)
        <div class="col-xs-2">&nbsp;</div>
    @endif
    </div>
@endforeach

@endsection
