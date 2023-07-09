<?php

namespace App\Http\Controllers\Api;

use App\Enums\KakaoTemplate;
use App\Models\Kakao;
use App\Models\User;
use App\Models\VerifyNumber;
use App\Models\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class VerifyNumberController extends ApiController
{
    public function store(Request $request)
    {
        $request->validate([
            "contact" => "required|max:255",
        ]);

        $number = random_int(100000,999999);

        $verifyNumber = VerifyNumber::updateOrCreate([
            "contact" => $request->contact
        ],[
            "contact" => $request->contact,
            "number" => $number,
            "verified" => false
        ]);

        $kakao = new Kakao();

        try{
            $kakao->send($request->contact, ["number" => $number], KakaoTemplate::VERIFY_NUMBER);
        }catch(\Exception $exception){
            return redirect()->back()->with("error", "잘못된 전화번호 형식입니다.");
        }

        // return $this->respondSuccessfully(null, __("response.verifyNumber")["send mail"]);
        return redirect()->back()->with("success", "인증번호가 전송되었습니다.");
    }


    public function update(Request $request)
    {
        $request->validate([
            "contact" => "required|max:255",
            "number" => "required|max:255",
        ]);

        $verifyNumber = VerifyNumber::where('contact', $request->contact)
            ->where('number', $request->number)->first();

        if(!$verifyNumber)
            return redirect()->back()->with("error", "인증번호가 일치하지 않습니다.");

        $verifyNumber->update([
            "verified" => true
        ]);

        if(auth()->user()) {
            auth()->user()->update(["contact" => $verifyNumber->contact]);

            $verifyNumber->delete();
        }

        // return $this->respondSuccessfully($verifyNumber, __("response.verifyNumber")["verified"]);
        return redirect()->back()->with("success", "성공적으로 인증되었습니다.");
    }
}
