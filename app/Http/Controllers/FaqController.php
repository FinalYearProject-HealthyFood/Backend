<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::all();
        return response()->json($faqs);
    }

    public function all(Request $request)
    {
        $faqs = Faq::where('question', 'like', '%' . $request->search . '%')
            ->orWhere('answer', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return response()->json($faqs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        return response()->json($faq);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $faq = Faq::findOrFail($id);
        return response()->json($faq);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        return response()->json($faq);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return response()->json($faq);
    }
}
