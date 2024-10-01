@props([
'code' => '403',
'title' => 'Forbidden',
'subtitle' => $exception->getMessage(),
'icon' => 'tabler-exclamation-circle'
])

@extends('errors::layout')