@extends('layouts.admin')

@section('title')
    @lang('admin/index.title')
@endsection

@section('content-header')
    <h1>@lang('admin/index.header.title')<small>@lang('admin/index.header.subtitle')</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">@lang('admin/navigation.breadcrumb.admin')</a></li>
        <li class="active">@lang('admin/navigation.breadcrumb.index')</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box
            @if($version->isLatestPanel())
                box-success
            @else
                box-danger
            @endif
        ">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('admin/index.content.title')</h3>
            </div>
            <div class="box-body">
                @if ($version->isLatestPanel())
                    {!! trans('admin/index.content.up-to-date', ['version' => config('app.version')]) !!}
                @else
                    {!! trans('admin/index.content.not-up-to-date1') !!}
                    <a>
                        <code>{{ $version->getPanel() }}</code>
                    </a>
                    {!! trans('admin/index.content.not-up-to-date2', ['version' => config('app.version')]) !!}
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="{{ $version->getDiscord() }}"><button class="btn btn-warning" style="width:100%;"><i class="fa fa-fw fa-support"></i> {!! trans('admin/index.content.get-help') !!}</button></a>
    </div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="https://pelican.dev"><button class="btn btn-primary" style="width:100%;"><i class="fa fa-fw fa-link"></i> @lang('admin/index.content.documentation')</button></a>
    </div>
    <div class="clearfix visible-xs-block">&nbsp;</div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="https://github.com/pelican-dev/panel"><button class="btn btn-primary" style="width:100%;"><i class="fa fa-fw fa-support"></i> @lang('admin/index.content.github')</button></a>
    </div>
    <div class="col-xs-6 col-sm-3 text-center">
        <a href="{{ $version->getDonations() }}"><button class="btn btn-success" style="width:100%;"><i class="fa fa-fw fa-money"></i> @lang('admin/index.content.support-the-project')</button></a>
    </div>
</div>
@endsection
