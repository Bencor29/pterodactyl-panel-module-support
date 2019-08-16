{{-- Pterodactyl - Panel - Support Plugin --}}
{{-- Copyright (c) 2019 Benjamin Cornou <benjamin@cornou.dev> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.master')

@section('scripts')
    {!! Theme::css('vendor/select2/select2.min.css?t={cache-version}') !!}
    @parent
@endsection

@section('title')
    @lang('support.base.header')
@endsection

@section('content-header')
    <h1>@lang('support.base.header')<small>@lang('support.base.header_sub')</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('index') }}">@lang('strings.home')</a></li>
        <li class="active">@lang('support.strings.support')</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('support.strings.new')</h3>
            </div>
            <form action="{{ route('index.support.new') }}" method="post">
                @csrf
                <div class="box-body row">
                    <div class="form-group col-xs-6">
                        <label for="pSubject">@lang('support.list.subject')</label>
                        <input name="subject" type="text" id="pSubject" class="form-control" required
                            placeholder="@lang('support.strings.placeholder_sub')" />
                    </div>
                    <div class="form-group col-xs-6">
                        <label for="pServerId">@lang('support.list.server')</label>
                        <select name="server_id" id="pServerId" class="form-control" required>
                            <option value="0">@lang('support.list.noserver')</option>
                            @foreach($servers as $server)
                                <option value="{{ $server->id }}"
                                    @if($server->id === old('server_id')) selected @endif
                                >{{ $server->name }} ({{ $server->uuidShort }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="pMessage">@lang('support.strings.message')</label>
                        <textarea name="message" id="pMessage" class="form-control" rows="10" required
                            placeholder="@lang('support.strings.placeholder_msg')"></textarea>
                    </div>
                </div>
                <div class="box-footer with-border text-right">
                    <input type="submit" class="btn btn-primary" value="@lang('support.strings.send')" />
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('footer-scripts')
    @parent

    {!! Theme::js('vendor/lodash/lodash.js') !!}
    {!! Theme::js('vendor/select2/select2.full.min.js?t={cache-version}') !!}

    <script type="text/javascript">
        $(document).ready(function() {
            $('#pServerId').select2({
                placeholder: 'Select a server',
            });
        });
    </script>
@endsection
