<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>Joutes</title>

        <link href="{{ asset('css/vendor/app.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/vendor/sweetalert.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/vendor/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/vendor/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/vendor/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('css/layout.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css" />

    </head>
    <body>

    <div id="login_popup" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content removeRaduis">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Connect as</h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('url' => route('admin.store'), 'method' => 'post', 'id' => 'login-form')) }}
                    {{ Form::radio('role', 'administrator', true, ['class' => 'roles']) }} Administrator <br>
                    {{ Form::radio('role', 'participant', null, ['class' => 'roles']) }} Participant <br>
                    {{ Form::radio('role', 'writer', null, ['class' => 'roles']) }} Writer <br>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default removeRaduis" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary btn-login-form">Connexion</button>
                </div>
                {{ Form::close() }}
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="navbar-header">
        <div class="user-infos">
            @if(Auth::check())
                <span class="username">{{ Auth::user()->username }}</span>
                {{ Form::open(array('url' => route('admin.destroy', 0), 'method' => 'delete')) }}
                {{ Form::button('<i class="fa fa-power-off" aria-hidden="true"></i>', array('type' => 'submit','class' => 'logout')) }}
                {{ Form::close() }}
            @else
                @if (isset($_ENV['OFFLINE']) && $_ENV['OFFLINE'] == "YES")
                    <span id="login_link">Off-Line Connexion</span>
                @else
                    <span><b><a href="/saml2/login">Connexion</a></b></span>
                @endif
            @endif
        </div>
    </div>
    <nav class="navbar">
            <div class="navbar-right">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('events.index') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="">
                </a>
            </div>
            <ul class="nav navbar-nav">
                <li class="@if(Route::is('events.index')) active @endif"><a href="{{ route('events.index') }}"> <i class="fa fa-calendar" aria-hidden="true"></i> Evénements</a></li>
                <li class="@if(Route::is('tournaments.index')) active @endif"><a href="{{ route('tournaments.index') }}"> <i class="fa fa-trophy" aria-hidden="true"></i> Tournois</a></li>
                @if(Auth::check())
                    @if(Auth::user()->role == 'administrator')
                        <li class="@if(Route::is('sports.index')) active @endif"><a href="{{ route('sports.index') }}"> <i class="fa fa-futbol-o" aria-hidden="true"></i> Sports</a></li>
                        <li class="@if(Route::is('courts.index')) active @endif"><a href="{{ route('courts.index') }}"> <i class="fa fa-map-marker" aria-hidden="true"></i> Terrains</a></li>
                        <li class="@if(Route::is('teams.index')) active @endif"><a href="{{ route('teams.index') }}"> <i class="fa fa-users" aria-hidden="true"></i> Equipes</a></li>
                        <li class="@if(Route::is('participants.index')) active @endif"><a href="{{ route('participants.index') }}"> <i class="fa fa-user" aria-hidden="true"></i> Participants</a></li>
                    @endif
                    @if(Auth::user()->role == 'participant')
                        <li class="@if(Route::is('profile.index')) active @endif"><a href="{{ route('profile.index') }}"> <i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
                    @endif
                @endif
            </ul>
            <div class="nav navbar-nav navbar-bottom">

                <footer class="footer">

                    <div class="copyright">© CPNV - 2017</div>
                    <div class="devs">
                        <a href="#" class="show-devs">Développeurs</a>

                        <div class="dev-names hide">
                            <a href="#" class="dev">Doran Kayoumi</a>
                            <a href="#" class="dev">Loïc Dessaules</a>
                            <a href="#" class="dev">Antoine Dessauges</a>
                        </div>
                    </div>
                </footer>


            </div>
        </nav>

        <div id="page">

            <div id="content">

                @yield('content')

            </div><!-- content -->

        </div><!-- page -->

        <script src="{{ asset('js/vendor/app.js') }}"></script>
        <script src="{{ asset('js/vendor/sweetalert.min.js') }}"></script>
        <script src="{{ asset('js/vendor/select2.full.min.js') }}"></script>
        <script src="{{ asset('js/vendor/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/vendor/dataTables.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('js/all.js') }}"></script>
    </body>
</html>
