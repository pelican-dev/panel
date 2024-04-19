@extends('layouts.admin')
@include('partials/admin.settings.nav', ['activeTab' => 'basic'])

@section('title')
    Settings
@endsection

@section('content-header')
    <h1>{{ trans('views/admin.index_settings.panel_settings') }}<small>{{ trans('views/admin.index_settings.title_desc') }}</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">{{ trans('views/admin.index_settings.admin') }}</a></li>
        <li class="active">{{ trans('views/admin.index_settings.settings') }}</li>
    </ol>
@endsection

@section('content')
    @yield('settings::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('views/admin.index_settings.panel_settings') }}</h3></h3>
                </div>
                <form action="{{ route('admin.settings') }}" method="POST">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ trans('views/admin.index_settings.company_name_title') }}</label>
                                <div>
                                    <input type="text" class="form-control" name="app:name" value="{{ old('app:name', config('app.name')) }}" />
                                    <p class="text-muted"><small>{{ trans('views/admin.index_settings.desc_company_name') }}</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ trans('views/admin.index_settings.title_2fa_auth') }}</label>
                                <div>
                                    <div class="btn-group" data-toggle="buttons">
                                        @php
                                            $level = old('panel:auth:2fa_required', config('panel.auth.2fa_required'));
                                        @endphp
                                        <label class="btn btn-primary @if ($level == 0) active @endif">
                                            <input type="radio" name="panel:auth:2fa_required" autocomplete="off" value="0" @if ($level == 0) checked @endif> {{ trans('views/admin.index_settings.not_required') }}
                                        </label>
                                        <label class="btn btn-primary @if ($level == 1) active @endif">
                                            <input type="radio" name="panel:auth:2fa_required" autocomplete="off" value="1" @if ($level == 1) checked @endif> {{ trans('views/admin.index_settings.admin_only') }}
                                        </label>
                                        <label class="btn btn-primary @if ($level == 2) active @endif">
                                            <input type="radio" name="panel:auth:2fa_required" autocomplete="off" value="2" @if ($level == 2) checked @endif> {{ trans('views/admin.index_settings.all_users') }}
                                        </label>
                                    </div>
                                    <p class="text-muted"><small>{{ trans('views/admin.index_settings.2fa_setting_desc') }}</small></p>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ trans('views/admin.index_settings.default_lang_title') }}</label>
                                <div>
                                    <select name="app:locale" class="form-control">
                                        @foreach($languages as $key => $value)
                                            <option value="{{ $key }}" @if(config('app.locale') === $key) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted"><small>{{ trans('views/admin.index_settings.default_lang_desc') }}</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! csrf_field() !!}
                        <button type="submit" name="_method" value="PATCH" class="btn btn-sm btn-primary pull-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
