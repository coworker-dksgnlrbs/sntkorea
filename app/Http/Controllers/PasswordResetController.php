<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetCreated;
use App\Models\PasswordReset;
use App\Models\SMS;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PasswordResetController extends \ShinHyungJune\SocialLogin\Http\PasswordResetController
{
    public function store(Request $request)
    {
        $request->validate([
            "contact" => "required|string|max:500"
        ]);

        if(!User::where("contact", $request->contact)->exists()) {
            return redirect()->back()->with("error", "가입되지 않은 아이디입니다.");
        }

        $token = random_int(100000000,999999999);

        $passwordReset = PasswordReset::where("contact", $request->contact)->first();

        $passwordReset ? $passwordReset->update([
            "contact" => $request->contact,
            "token" => $token
        ]) : $passwordReset = PasswordReset::create([
            "contact" => $request->contact,
            "token" => $token
        ]);

        $sms = new SMS();

        try {
            $sms->send("+82".$request->contact, "[비밀번호 초기화 링크] ".$passwordReset->resetUrl()."\n"."-".config("app.name")."-");
        }catch(\Exception $exception){
            return redirect()->back()->with("error", "유효하지 않은 폰번호 아이디입니다.");
        }

        return redirect()->back()->with("success", "비밀번호 초기화 문자가 발송되었습니다.");
    }

    public function edit(Request $request)
    {
        return Inertia::render("PasswordResets/Edit", [
            "contact" => $request->contact,
            "token" => $request->token
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            "contact" => "required|string|max:500",
            "token" => "required|string|max:5000",
            "password" => "required|string|min:8|max:500|confirmed"
        ]);

        $passwordReset = PasswordReset::where("contact", $request->contact)
            ->where("token", $request->token)
            ->first();

        $user = User::where("contact", $request->contact)->first();

        if(!$user || !$passwordReset){
            return redirect()->back()->with("error", "유효하지 않은 토큰이거나 존재하지 않는 아이디입니다.");
        }

        $user->update(["password" => Hash::make($request->password)]);

        return redirect("/login")->with("success", "비밀번호가 변경되었습니다.");
    }
}
