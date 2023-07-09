<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;
use App\Models\VerifyNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class UserController extends \ShinHyungJune\SocialLogin\Http\UserController
{
    public function login(Request $request)
    {
        $data = $request->validate([
            "contact" => "required|string|max:500",
            "password" => "required|string|max:500",
        ]);

        if(auth()->attempt($request->all())) {
            session()->regenerate();

            return redirect()->intended();
        }

        return Inertia::render("Users/Login", [
            "errors" => [
                "contact" => __("socialLogin.invalid")
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "contact" => "required|string|max:500|unique:users",
            "name" => "required|string|max:500",
            "password" => "required|string|max:500|min:8|confirmed",
            "agree_privacy" => "required|boolean",
            "agree_ad" => "required|boolean",
        ]);

        if(!$request->agree_privacy)
            return redirect()->back()->with("error", "개인정보처리방침에 동의해야만 회원가입을 진행할 수 있습니다.");

        $verifyNumber = VerifyNumber::where('contact', $request->contact)
            ->where('verified', true)->first();

        if(!$verifyNumber)
            return redirect()->back()->with("error", "인증된 전화번호만 사용할 수 있습니다.");

        User::create([
            "contact" => $request->contact,
            "name" => $request->name,
            "order_contact" => $request->contact,
            "order_name" => $request->name,
            "agree_ad" => $request->agree_ad,
            "password" => Hash::make($request->password)
        ]);

        $verifyNumber->delete();

        return redirect("/login")->with("success", "성공적으로 가입되었습니다.");
    }

    public function socialLogin(Request $request, $social)
    {
        $socialUser = Socialite::driver($social)->user();

        // 일단 네이버
        $user = User::where("social_id", $socialUser->id)->where("social_platform", $social)->first();

        if(!$user) {
            $user = User::create([
                "name" => $social,
                "social_id" => $socialUser->id,
                "social_platform" => $social
            ]);
        }

        Auth::login($user);

        return redirect()->intended();
    }

    public function update(Request $request)
    {
        $request->validate([
            "contact_change" => "nullable|string|max:500|unique:users,contact",
            "name" => "nullable|string|max:500",
            "password" => "nullable|string|max:500|min:8|confirmed",
            "agree_ad" => "nullable|boolean",

            "bank" => "nullable|string|max:500",
            "account" => "nullable|string|max:500",
            "owner" => "nullable|string|max:500",
        ]);

        if($request->contact_change){
            $verifyNumber = VerifyNumber::where('contact', $request->contact_change)
                ->where('verified', true)->first();

            if(!$verifyNumber)
                return redirect()->back()->with("error", "인증된 전화번호만 사용할 수 있습니다.");

            auth()->user()->update(["contact" => $request->contact_change]);

            $verifyNumber->delete();
        }


        if($request->name)
            auth()->user()->update(["name" => $request->name]);

        if($request->password)
            auth()->user()->update(["password" => Hash::make($request->password)]);

        if($request->bank)
            auth()->user()->update(["bank" => $request->bank]);

        if($request->owner)
            auth()->user()->update(["owner" => $request->owner]);

        if($request->account)
            auth()->user()->update(["account" => $request->account]);

        if(isset($request->agree_ad))
            auth()->user()->update(["agree_ad" => $request->boolean("agree_ad")]);

        return redirect()->back()->with("success", "성공적으로 처리되었습니다.");
    }

    public function loginForm()
    {
        return Inertia::render("Users/Login");
    }

    public function edit(Request $request)
    {
        // $categories = Category::paginate(30);

        return Inertia::render("Users/Edit", [
            // "categories" => CategoryResource::collection($categories)
        ]);
    }

    public function remove()
    {
        return Inertia::render("Users/Remove");
    }

    public function destroy(Request $request)
    {
        $request->validate([
            "reason" => "required|string|max:50000"
        ]);

        auth()->user()->update(["reason_leave_out" => $request->reason]);

        auth()->user()->delete();

        return redirect("/")->with("success", "성공적으로 탈퇴되었습니다.");
    }

    public function logout()
    {
        Auth::logout();

        return redirect("/");
    }
}
