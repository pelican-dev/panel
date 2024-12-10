@props([
'code' => '500',
'title' => 'Server Error',
'subtitle' => fn () => auth()->user()?->isRootAdmin() ? $exception->getMessage() : 'Something went wrong.',
'icon' => 'tabler-bug'
])

@extends('errors::layout')