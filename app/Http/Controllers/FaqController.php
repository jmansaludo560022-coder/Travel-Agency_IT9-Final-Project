<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_published', true)
            ->orderBy('display_order', 'asc')
            ->get()
            ->groupBy('category');

        return view('faqs.index', compact('faqs'));
    }
}
