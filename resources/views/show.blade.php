@extends('adminchat::layouts.app')
 
@section('content')
<div class="container">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card chat-app">
                <div id="search"></div>
                <div class="chat">
                    <div id="chat-header">
                        <chat-header 
                            image="{{ $user->image }}"
                            user-name={{ $user->name }}
                            surname={{ $user->surname }}
                            last-activity="{{ $user->last_activity }}"
                        >
                        </chat-header>
                    </div>
                    <div id="chat"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
