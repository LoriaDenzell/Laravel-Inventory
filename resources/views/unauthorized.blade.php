@extends('layouts.app')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-md-6" style="float:none;margin:auto;">
            <div class="error-page">
                <h1 class="headline text-warning"> 401</h1>

                <div class="error-content">
                    <h2>
                        <i class="fas fa-exclamation-triangle text-warning"></i> Oops! Access not authorized.
                    </h2>
                    <p>
                        The request was a legal request, but the server is refusing to respond to it. 
                        For use when authentication is possible but has failed or not yet been provided
                        <a href="{{url('login')}}">return to login page</a>.
                        <br><br>
                        To learn more about HTTP messages visit this 
                        <a href = "https://www.w3schools.com/tags/ref_httpmessages.asp">link</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection