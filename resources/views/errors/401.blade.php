@includeWhen(Auth::user(), 'layouts.backend.app')
@includeWhen(Auth::guest(), 'layouts.app')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-md-6" style="float:none;margin:auto;">
            <div class="error-page">
                <h1 class="headline text-warning"> 401</h1>

                <div class="error-content">
                    <h2>
                        <i class="fas fa-exclamation-triangle text-warning"></i>  Oops! Page not found.
                    </h2>

                    <p>
                        We could not find the page you were looking for.
                        Meanwhile, you may <a href="{{url('/')}}">return to dashboard</a>
                        <br><br>
                        To learn more about HTTP messages visit this 
                        <a href = "https://www.w3schools.com/tags/ref_httpmessages.asp">link</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection