@extends('layouts.main')
@section('content')
<div class="wrapper" id="app">
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                <router-view></router-view>
                <vue-progress-bar></vue-progress-bar>
            </div>
        </div>
    </div>
</div>
@endsection

