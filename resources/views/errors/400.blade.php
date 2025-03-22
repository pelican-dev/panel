@props([
'code' => '400',
'title' => 'Bad request',
'subtitle' => $exception->getMessage(),
'icon' => 'tabler-exclamation-circle'
])

@extends('errors::layout')