@props([
'code' => '404',
'title' => 'Not found',
'subtitle' => $exception->getMessage() ?? 'The requested resource was not found.',
'icon' => 'tabler-zoom-question'
])

@extends('errors::layout')
