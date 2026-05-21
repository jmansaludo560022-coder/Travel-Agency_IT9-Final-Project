<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with('employee')->orderBy('display_order')->paginate(15);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Faq::class);

        $request->validate([
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['boolean'],
        ]);

        DB::transaction(function () use ($request) {
            Faq::create([
                'employee_id' => auth()->user()->employee->id,
                'question' => $request->question,
                'answer' => $request->answer,
                'category' => $request->category,
                'display_order' => $request->display_order ?? 0,
                'is_published' => $request->boolean('is_published'),
            ]);
        });

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function show($id)
    {
        $faq = Faq::with('employee')->findOrFail($id);
        return view('admin.faqs.show', compact('faq'));
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $this->authorize('update', $faq);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $this->authorize('update', $faq);

        $request->validate([
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['boolean'],
        ]);

        DB::transaction(function () use ($request, $faq) {
            $faq->update([
                'question' => $request->question,
                'answer' => $request->answer,
                'category' => $request->category,
                'display_order' => $request->display_order ?? 0,
                'is_published' => $request->boolean('is_published'),
            ]);
        });

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $this->authorize('delete', $faq);

        DB::transaction(fn() => $faq->delete());

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $this->authorize('create', Faq::class);

        $request->validate([
            'faq_ids' => ['required', 'array'],
            'faq_ids.*' => ['integer', 'exists:faqs,id'],
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->faq_ids as $order => $id) {
                Faq::where('id', $id)->update(['display_order' => $order]);
            }
        });

        return response()->json(['message' => 'FAQs reordered successfully.']);
    }
}
