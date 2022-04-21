@extends('adminchat::layouts.app')
 
@section('content')
<div class="container">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card chat-app">
                <div id="plist" class="people-list">
                    <div class="input-group">
                        <input type="text" class="form-control input-group-text" placeholder="Search" id="txtSearch"/>
                        <div class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="search">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </div>
                    </div>
                    <ul class="list-unstyled chat-list mt-2 mb-0" id="user-list">
                        @foreach ( $users as $u )
                            <li class="clearfix">
                                <a href="{{ url("admin/adminchat/{$u->id}") }}">
                                    <img src="{{ $u->image }}" alt="avatar">
                                    <div class="about">
                                        <div class="name">{{ $u->name }} {{ $u->surname ?? '' }}</div>
                                        <div class="status">{{ $u->email }}</div>                                            
                                        <div class="status"> <i class="fa fa-circle offline"></i> new messages </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach 
                    </ul>
                </div>
                <div class="chat">
                    <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-lg-6">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                                    <img src="{{ $user->image }}" alt="avatar">
                                </a>
                                <div class="chat-about">
                                    <h6 class="m-b-0">{{ $user->name }} {{ $user->surname ?? '' }}</h6>
                                    <small>{{ $user->last_activity ? 'Last seen: ' . $user->last_activity->toDateTimeString() : '' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-history">
                        <ul id="messages" class="m-b-0">
                            @foreach ( $messages->sortBy('id') as $message )
                                <li class="clearfix">
                                    @if ( $message->from_user )
                                        <div class="message-data">
                                            <span class="message-data-time">{{ $message->created_at }}</span>
                                        </div>
                                        <div class="message my-message">
                                            <div>{{ $message->text }}</div>
                                            @if ( $message->file )
                                                <div>
                                                    <a href="{{ url(Storage::url($message->file)) }}" target="_blank" download>File: {{ url(Storage::url($message->file)) }}</a>                                  
                                                </div>
                                            @endif
                                        </div>                                    
                                    @else
                                        <div class="message-data text-right">
                                            <span class="message-data-time">{{ $message->created_at }}</span>
                                        </div>
                                        <div class="message other-message float-right">
                                            <div>{{ $message->text }}</div>
                                            @if ( $message->file )
                                                <div>
                                                    <a href="{{ url(Storage::url($message->file)) }}" target="_blank" download>File: {{ url(Storage::url($message->file)) }}</a>                                  
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="chat-message clearfix">
                        <div class="">{{ $messages->links("pagination::bootstrap-4") }}</div>
                        <form id="message-form">
                            <div class="form-group">
                                <textarea class="form-control" id="text" name="text" rows="3"></textarea>
                            </div>
                            <input type="file" class="form-control" id="file" name="file">
                            <button id="send" type="button" class="btn btn-primary mb-2">
                                <span class="input-group-text"><i class="fa fa-send"></i></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
