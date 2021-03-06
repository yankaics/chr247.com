<?php

namespace App\Http\Controllers;

use App\Lib\Logger;
use Exception;
use Illuminate\Http\Request;
use Mail;
use Validator;

class WebsiteController extends Controller {

    public function getAboutUsPage() {
        return view("website.aboutUs");
    }

    public function getFeaturesPage() {
        return view("website.features");
    }

    public function getPrivacyPolicyPage() {
        return view("website.privacyPolicy");
    }

    public function getContactUs() {
        return view("website.contactUs");
    }

    /**
     * Called when a user is submitting a message through the contact us page of the web site.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postContactUs(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:50',
            'contact' => 'required|min:2|max:50',
            'email' => 'required|email|min:2|max:50',
            'message' => 'required|min:2|max:500'
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }
        $data = [
            'name' => $request->name,
            'contact' => $request->contact,
            'email' => $request->email,
            'message' => $request->message
        ];

        $emails = ["imesha@highflyer.lk", "support@chr247.com"];
        try {
            Mail::send('emails.contactUs', ['msg' => $data], function ($m) use ($emails) {
                $m->to($emails)->subject('chr247.com - New Contact Us Message');
            });
        } catch (Exception $e) {
            Logger::error("Error when sending contact us message : " . $e->getMessage(), Mail::failures());
        }
        return back()->with('success', "Your message submitted successfully. We will contact you soon.");
    }
}
