{{-- Pterodactyl - Panel - Support Plugin --}}
{{-- Copyright (c) 2019 Benjamin Cornou <benjamin@cornou.dev> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.master')

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
                <h3 class="box-title">@lang('support.base.requests')</h3>
                <div class="box-tools">
                    <a class="btn btn-sm btn-primary" href="{{ route('index.support.new') }}">@lang('support.strings.new')</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
