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

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                @if($admin)
                    <h3 class="box-title">@lang('support.admin.requests')</h3>
                @else
                    <h3 class="box-title">@lang('support.base.requests')</h3>
                    <div class="box-tools">
                        <a class="btn btn-sm btn-primary" href="{{ route('index.support.new') }}">@lang('support.strings.new')</a>
                    </div>
                @endif
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('support.list.id')</th>
                            <th>@lang('support.list.subject')</th>
                            <th>@lang('support.list.server')</th>
                            <th>@lang('support.list.status')</th>
                            @if($admin)
                                <th>@lang('support.list.admin')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td><code>{{ $ticket->id }}</code></td>
                                <td><a href="{{ route(($admin ? 'admin' : 'index') . '.support.see', $ticket) }}">{{ $ticket->subject }}</a></td>
                                <td>
                                    @isnull($ticket->server)
                                        <i>@lang('support.list.noserver')</i>
                                    @else
                                        <code title="{{ $ticket->server->name }}">{{ $ticket->server->uuidShort }}</code>
                                    @endisnull
                                </td>
                                <td>
                                    @if($ticket->is_closed)
                                        <span class="label label-danger">@lang('support.status.closed')</span>
                                    @elseif(!is_null($ticket->admin))
                                        <span class="label label-warning">@lang('support.status.assigned')</span>
                                    @else
                                        <span class="label label-success">@lang('support.status.open')</span>
                                    @endif
                                </td>
                                @if($admin)
                                    <td>
                                        @isnull($ticket->admin)
                                            <i>@lang('support.list.noadmin')</i>
                                        @else
                                            {{ $ticket->admin->name }}
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
