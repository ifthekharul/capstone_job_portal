-@extends ('layouts.app')
@section('main')
<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>

    @if(Session::has('success'))
    <div class="alert alert-success">
        <p class="mb-0 pb-0">{{ Session::get('success') }}</p>
    </div>
    @endif

    @if(Session::has('error'))
    <div class="alert alert-danger">
        <p class="mb-0 pb-0">{{ Session::get('error') }}</p>
    </div>
   @endif


        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Reset Password</h1>
                    <form action="{{ route('account.processResetPassword') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $tokenString }}">
                        <div class="mb-3">
                            <label for="" class="mb-2">New Password*</label>
                            <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="New Password" >
                             @error('new_password')
                              <div class="invalid-feedback">
                             {{ $message }}
                              </div>
                              @enderror
                        </div>  

                        <div class="mb-3">
                            <label for="" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password" >
                             @error('confirm_password')
                              <div class="invalid-feedback">
                             {{ $message }}
                              </div>
                              @enderror
                        </div>  

                        <div class="justify-content-between d-flex">
                        <button class="btn btn-primary mt-2">Submit</button>
                        </div>
                    </form>                    
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a  href="{{ route('account.login') }}">Back to Login</a></p>
                </div>
            </div>
        </div>
        <div class="py-lg-5">&nbsp;</div>
    </div>
</section>
@endsection