<!DOCTYPE html>
<html lang="en">

    @include('adminchat::head')

    <body>

        <div class="container">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card chat-app height-300">
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
                            <div class="chat-history">
                            </div>
                            <div class="chat-message clearfix">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('adminchat::footer')

    </body>
</html>
