@props([
'code' => '500',
'title' => 'Server Error',
'subtitle' => fn () => user()?->isRootAdmin() ? $exception->getMessage() : 'Something went wrong.',
'icon' => 'tabler-bug'
])

@extends('errors::layout')