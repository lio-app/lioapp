@extends('layouts.header')

@section('content')

<div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h3>Enabel 2FA</h3>
                    <div class="panel panel-default">
                        <div class="panel-heading">Set up Google Authenticator</div>
                         <div class="panel-body" style="text-align: center;">
                            <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code<b> WAATLIB5ZUOR35F3</b>
                            </p> 
                            <div>
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAIAAAAiOjnJAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAFCElEQVR4nO3d0YrcOBCG0U3Y93/ksBcLwZD1Io/qs93DOZehx3ZPfjSFJJd+/Pr16y+Y9vPpB+B7EiwSgkVCsEgIFgnBIiFYJASLhGCRECwSgkVCsEgIFgnBIiFYJASLhGCRECwSgkVCsEgIFgnBIiFYJASLhGCR+Hv2cj9/zif17F3t472uvs999pwr19n5jivfZefzO2bfiTdikRAsEoJFYrjGOtr5m31WQ6zUFkX9cWanBtqp83Y+v/IM+4xYJASLhGCRCGuso5W/5Su1wvEzx2vuzAOdXfPsZ88+v2LqOa9ef+Ves4xYJASLhGCRuKnGmrJSo+zUPSvXWam9rt53ak7rPYxYJASLhGCR+LAaa2peauc6Z3Zqsp15tXcyYpEQLBKCReKmGmuqPrhacxTrelfvtbN+d+ferFlGLBKCRUKwSIQ11p17z1eeYWfeaOpdwqnnOXrD7/lPb3wmvgHBIiFYJH68Z+ajsLNnfGeP+U7d9j3+R4xYJASLhGCReFF/rGLe6MzU/qqV9cGdNc07/32WEYuEYJEQLBLD81hX9x5NvSf4VA/S4jnrGuie+TMjFgnBIiFYJMK1wp2ao+gLtXLfqWu+bT7v/jktIxYJwSIhWCReNI81NccztX+83o+1ct/ime9hxCIhWCQEi8TD/bF21tp25niK9bKiN+nOfa9e3zwWH0CwSAgWiZvmsYp9Tm/Y333n+dBXr7PCWiEfRrBICBaJ4XmsnTmVei98Xbtcrfmmnn9qvXKWEYuEYJEQLBLhPNYbekFN7fd68/uPdQ+wrzFikRAsEoJF4oH9WMVc1FT9tFO73FnD7cxprTzPPiMWCcEiIVgkHj5LZ6qmuXr284o37AnbOUvn6lnUs4xYJASLhGCRuOksneIcwKN76oY/73Xmzu91Z3/8dUYsEoJFQrBIPPBeYbGeNbXfq+6PtfKZYg7sjP1YfBjBIiFYJB7u3XBn74On+rBP1VtHU31Nz55hnxGLhGCRECwS4X6snd4HR28402bqXle/4xvWPb/GiEVCsEgIFomb3iss5rfqdb2rNdPVemvqd1LP232NEYuEYJEQLBIvWiuse0etPM/KvYq+pnWP+5VnmGXEIiFYJASLxE09SFc+f+bOM3CmenueXfPqz654w5k8/3H3wWvBb4JFQrBIPNCDdGqNbGfOZqr/1tSzTfUjfbYn1pERi4RgkRAsEi/aj3XnWTRXFf3fi9poZc+WHqR8MMEiIVgkHpjHOlOsqU3VZDvn8Jw9z9W66g39w9YZsUgIFgnBIvFwjVXMXRX7wOr99TtzVGefebbeMmKRECwSgkViuMba2f9U3HflHMOir1Xhaq22U2/tM2KRECwSgkViuMYq/mYX630r99rpK3Gm6Il15/mJ64xYJASLhGCReFGf96Op9az6vMKpaxb7zOp68f8ZsUgIFgnBIvHwe4VHV+djdvaMF33Y33DG83t80rPyQQSLhGCReNF7hVOm5mzuPK+w7qfgTGi+CcEiIVgkvmGNdbRTr9Trayv78c8+P3Uve975MIJFQrBI3FRj3VmjXFWfn1O821icbzjLiEVCsEgIFomwxqr3D03tf7r6HuLOvvupuaviu1sr5AMIFgnBIjF8JjT8y4hFQrBICBYJwSIhWCQEi4RgkRAsEoJFQrBICBYJwSIhWCQEi4RgkRAsEoJFQrBICBYJwSIhWCQEi4RgkRAsEoJF4h8K3rVV6+RwhAAAAABJRU5ErkJggg==">
                            </div>
                             <p style="color: red;">You must set up your Google Authenticator app before continuing. You will be unable to login otherwise</p> 
                             <a href="#" class="btn btn-primary"  data-toggle="modal" href="#ignismyModal"> Complete </a>
                              <div class="modal fade" id="ignismyModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                         </div>
                        
                        <div class="modal-body">
                           
                            <div class="thank-you-pop">
                                <img src="http://goactionstations.co.uk/wp-content/uploads/2017/03/Green-Round-Tick.png" alt="">
                                <h1>Thank You!</h1>
                                <p>Your submission is received and we will contact you soon</p>
                                <h3 class="cupon-pop">Your Id: <span>12345</span></h3>
                                
                            </div>
                             
                        </div>
                        
                    </div>
                </div>
            </div>
    </div></div></div></div>
</div>
@endsection
