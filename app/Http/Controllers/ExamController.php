<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamCategory;


class ExamController extends Controller
{

    /**
     * Show the home page with categories.
     */
    public function home()
    {
        // Get all categories
        $categories = ExamCategory::all();
        return view('home', compact('categories'));
    }

    /**
     * Show popular exams based on the views count (limit 10).
     */
    public function popularExams()
    {
        $popularExams = Exam::select('exams.name', 'users.name as user_name', 'exam_categories.name as category_name', 'exams.description', 'exams.views', 'exams.created_at')
            ->join('users', 'users.id', '=', 'exams.user_id')
            ->join('exam_categories', 'exam_categories.id', '=', 'exams.category_id')
            ->where('exams.is_public', '=', 1)
            ->orderBy('exams.views', 'desc')
            ->limit(10)
            ->get();

            return view('popular-exams', compact('popularExams'));
        }

    /**
     * Show all public exams.
     */
    public function allExams()
    {
        $allExams = Exam::select('exams.name', 'users.name as user_name', 'exam_categories.name as category_name', 'exams.description', 'exams.created_at')
            ->join('users', 'users.id', '=', 'exams.user_id')
            ->join('exam_categories', 'exam_categories.id', '=', 'exams.category_id')
            ->where('exams.is_public', '=', 1)
            ->orderBy('exams.created_at', 'desc')
            ->get();

            return view('all-exams', compact('allExams'));
        }

    public function showByCategory($id)
    {
    $exams = Exam::where('category_id', $id)
        ->where('is_public', 1)
        ->with('user') // Assuming you want to show user details too
        ->orderBy('created_at', 'desc')
        ->get();

    $category = ExamCategory::find($id); // Get category details

    return view('exams.category', compact('exams', 'category'));
}




    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
