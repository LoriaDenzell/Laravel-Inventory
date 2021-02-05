@includeWhen(Auth::user(), 'layouts.backend.app')

@auth
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.backend.partials.topbar')
        @include('layouts.backend.partials.sidebar')
        <div class="content-wrapper">
            <main class="py-4">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-6" style="float:none;margin:auto;">
                                <div class="error-page">
                                    <h1 class="headline text-warning"> 403</h1>
                                    <div class="error-content">
                                        <h2>
                                            <i class="fas fa-exclamation-triangle text-warning"></i>  Oops! Unauthorized Access Detected!.
                                        </h2>
                                        <p>
                                            <strong>{{\Auth::user()->first_name}} {{\Auth::user()->last_name}} </strong> You are not autthorized to access this page.
                                            Meanwhile, you may <a href="{{url('/home')}}">return to dashboard</a>
                                            <br><br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
             </main>
        </div>
    </div>
    @include('layouts.backend.partials.footer')
</body>
@endauth
@guest 
<body>
    <div class="app">
        @include('layouts.app')
        <main class="py-4">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6" style="float:none;margin:auto;">
                            <div class="error-page">
                                <h1 class="headline text-warning"> 403</h1>
                                <div class="error-page">
                                    <h1 class="headline text-warning"> 403</h1>
                                    <div class="error-content">
                                        <h2>
                                            <i class="fas fa-exclamation-triangle text-warning"></i>  Oops! Unauthorized Access Detected!.
                                        </h2>
                                        <p>
                                            <strong>{{\Auth::user()->first_name}} {{\Auth::user()->last_name}} </strong> You are not autthorized to access this page.
                                            Meanwhile, you may <a href="{{url('/home')}}">return to dashboard</a>
                                            <br><br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
@endguest


