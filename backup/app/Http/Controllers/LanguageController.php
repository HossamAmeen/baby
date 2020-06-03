<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Lang;

class LanguageController extends Controller {

    //
    public function index($lang) {
        $langs = ['en', 'ar'];
        if (in_array($lang, $langs)) {
            \Session::set('lang', $lang);
            return redirect()->back();
        }
    }

}
