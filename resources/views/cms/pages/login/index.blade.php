{{-- @author Abed Halawi <abed.halawi@vinelab.com> --}}

<!DOCTYPE html>
<html lang="en">
    <head>
        @include('cms.layout.templates.head')
    </head>

    <body class="login-layout">
        <div class="main-container">
            <div class="main-content">

                @include('cms.layout.templates.alerts')

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <i class="icon-leaf green"></i>
                                    <span class="red">{{Lang::get('posts/form.agency_cms')}}</span>
                                    <small class="blue">&copy; {{date('Y')}}</small>
                                </h1>
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="icon-coffee green"></i>
                                                {{Lang::get('login.welcome_login')}}
                                            </h4>

                                            <div class="space-6"></div>

                                            {{ Form::open(['url' => URL::route('cms.login.attempt'), 'method'=>'POST']) }}
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            {{ Form::text('email','', ['class'=>'form-control', 'placeholder'=>Lang::get('login.email')]) }}
                                                            <i class="icon-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            {{ Form::password('password', ['class'=>'form-control', 'placeholder'=>Lang::get('login.password')]) }}
                                                            <i class="icon-lock"></i>
                                                        </span>
                                                    </label>

                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                        <label class="inline">
                                                            <input type="checkbox" name="remember" class="ace" />
                                                            <span class="lbl">{{Lang::get('login.remember_me')}}</span>
                                                        </label>
                                                        {{ Form::submit(Lang::get('login.login'),['class'=> 'width-35 pull-right btn btn-sm btn-primary']) }}
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            {{ Form::close() }}

                                        </div><!-- /widget-main -->

                                        <div class="toolbar clearfix">
                                            <div>
                                                <a href="#" onclick="show_box('forgot-box'); return false;" class="forgot-password-link">
                                                    <i class="icon-arrow-left"></i>
                                                   {{Lang::get('login.forget_password')}}
                                                </a>
                                            </div>

                                        </div>
                                    </div><!-- /widget-body -->
                                </div><!-- /login-box -->

                                <div id="forgot-box" class="forgot-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header red lighter bigger">
                                                <i class="icon-key"></i>
                                               {{Lang::get('login.retrieve_password')}}
                                            </h4>

                                            <div class="space-6"></div>
                                            <p>
                                                {{Lang::get('login.enter_email_to_receive_instruction')}}
                                            </p>

                                            {{ Form::open(['url' => URL::route('cms.password.email'), 'method'=>'POST']) }}
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" name="email" class="form-control" placeholder={{Lang::get('login.email')}} />
                                                            <i class="icon-envelope"></i>
                                                        </span>
                                                    </label>

                                                    <div class="clearfix">

                                                        {{ Form::submit(Lang::get('login.send_me'),['class'=> 'width-35 pull-right btn btn-sm btn-danger']) }}

                                                    </div>
                                                </fieldset>
                                            {{ Form::close() }}
                                        </div><!-- /widget-main -->

                                        <div class="toolbar center">
                                            <a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
                                                {{Lang::get('login.back_to_login')}}
                                                <i class="icon-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div><!-- /widget-body -->
                                </div><!-- /forgot-box -->


                            </div><!-- /position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->





         @section('scripts')
            @include('cms.layout.templates.scripts')

            <script type="text/javascript">
            function show_box(id) {
             jQuery('.widget-box.visible').removeClass('visible');
             jQuery('#'+id).addClass('visible');
            }
        </script>
             <script type="text/javascript">
            window.jQuery || document.write("<script src='{{Cdn::asset('assets/js/jquery-ui.custom.min.js')}}'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

        <script type="text/javascript">
            if("ontouchend" in document) document.write("<script src='{{Cdn::asset('assets/js/jquery-ui.custom.min.js')}}'>"+"<"+"/script>");
        </script>

        <!-- inline scripts related to this page -->
        @show
    </body>
</html>
