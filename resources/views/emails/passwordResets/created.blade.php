@component('mail::message')
    <div id="resetPassword">
        <h3 class="title type01">Reset Password</h3>

        <p class="body type01">Hi, {{$receiver ? $receiver->email : ""}}.</p>

        <p class="body type01">
            [{{config("app.name")}}] To reset your password, click the Reset Password button.
        </p>

        <div class="btns" style="text-align:center;">
            <a href="{{$passwordReset ? $passwordReset->resetUrl() : ''}}" class="btn type01 width-100 bg-primary">Reset Password</a>
        </div>
    </div>
@endcomponent
