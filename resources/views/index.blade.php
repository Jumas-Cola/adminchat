@extends('adminchat::layouts.app')
 
@section('content')
<div class="container">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card chat-app height-300">
                <div id="search"></div>
                <div class="chat">
                    <div class="chat-history">
                        👈 Начните вводить имя или email пользователя.
                    </div>
                    <div class="chat-message clearfix">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
