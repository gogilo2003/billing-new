@extends('layouts.pdf')
@section('title')
        Test
    @endsection
    @push('styles')
    <style>
        h1{
            font-family: Helvetica, sans-serif;
            text-align: center;
            text-transform: uppercase;
            font-weight: 200;
            color: red;
        }
        p{
            text-align: justify;
        }
        img{
            width: 100%;
        }
    </style>
    @endpush

    @section('content')
    <h1>Test Page</h1>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde non nulla labore cumque eum perspiciatis consequuntur nihil eligendi magni illum. Aliquam, molestias mollitia placeat qui exercitationem veritatis quae possimus similique.</p>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Beatae pariatur iste, voluptate ratione nobis culpa hic magnam eum molestiae? Saepe nostrum facere ea ipsum at voluptate amet molestiae assumenda recusandae.</p>
    <p>{{ date('j, F Y') }}</p>
    <img src="https://images.unsplash.com/photo-1523633589114-88eaf4b4f1a8?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" alt="" srcset="">
    @endsection
