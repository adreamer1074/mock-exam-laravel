<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\ExamResult;
use App\Models\ExamAnswer;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1)
                    ->orWhereNull('del_flg'); // del_flgがnullまたは!= 1のレコードを取得
            })
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
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1)
                    ->orWhereNull('del_flg'); // del_flgがnullまたは!= 1のレコードを取得
            })
            ->orderBy('exams.created_at', 'desc')
            ->get();

        return view('all-exams', compact('allExams'));
    }

    public function showByCategory($id)
    {
        $exams = Exam::where('category_id', $id)
            ->where('is_public', 1)
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1)
                    ->orWhereNull('del_flg'); // del_flgがnullまたは!= 1のレコードを取得
            })
            ->with('user') // Assuming you want to show user details too
            ->orderBy('created_at', 'desc')
            ->get();

        $category = ExamCategory::find($id); // Get category details

        return view('exams.category', compact('exams', 'category'));
    }


    /**
     * Show all public exams that are not deleted.
     */
    public function index()
    {
        $exams = Exam::where('is_public', 1)
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1)
                    ->orWhereNull('del_flg');
            })
            ->orderBy('created_at', 'desc')
            ->with('category', 'user')
            ->get();

        return view('exams.index', compact('exams'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $exam = Exam::with(['questions.options', 'user', 'category']) //複数テーブルから関連付け
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1)
                    ->orWhereNull('del_flg');
            })
            ->where('is_public', 1)
            ->findOrFail($id);

        return view('exams.show', compact('exam'));
    }


    public function submit(Request $request, $examId)
    {
        // ユーザーのIDを取得
        $userId = auth()->id();
    
        // ユーザーが選択した答えをループして保存
        foreach ($request->input('answers') as $questionId => $optionIds) {
            foreach ($optionIds as $optionId) {
                ExamAnswer::create([
                    'user_id' => $userId,
                    'exam_id' => $examId,
                    'question_id' => $questionId,
                    'option_id' => $optionId,
                ]);
            }
        }
    
        // ユーザーの回答を取得
        $userAnswers = ExamAnswer::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->get();
    
        // スコアを計算
        $score = $this->calculateScore($userAnswers, $examId);
    
        // 結果を保存する（ExamResultモデルを使用）
        ExamResult::create([
            'user_id' => $userId,
            'exam_id' => $examId,
            'score' => $score,
            'completed_at' => now(),
        ]);
    
        // 結果ページへリダイレクト
        return redirect()->route('exam.results', $examId);
    }

    public function submitResult(Request $request, $examId)
{
    $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'array', // 各質問の選択肢は配列
    ]);

    // 結果を表示するためにリダイレクト
    return redirect()->route('exam.result', $examId)->withInput($request->all());
}
    


    /**
     * Show result for a specific exam.
     * 問題を見直す
     */
    public function showResult(Request $request, $examId)
    {
        // ユーザーが選択した回答
        $answers = $request->input('answers', []);
    
        // 試験を取得
        $exam = Exam::with(['questions.options'])->findOrFail($examId);
    
        // スコアを計算
        $score = $this->calculateScore($answers, $exam);
    
        // 提出日時を取得
        $submittedAt = now();
    
        return view('exams.result', compact('exam', 'answers', 'score', 'submittedAt'));
    }
    
    
    private function calculateScore($answers, $exam)
    {
        $score = 0;
        foreach ($exam->questions as $question) {
            // 正しい回答を取得
            $correctAnswers = $question->options->where('is_correct', true)->pluck('id')->toArray();
            // ユーザーの選択肢が正しいか確認
            if (isset($answers[$question->id]) && array_diff($correctAnswers, $answers[$question->id]) === []) {
                $score++;
            }
        }
        return $score;
    }
    
    // Show the create form
    public function create()
    {
        $categories = ExamCategory::all(); // すべてのカテゴリを取得

        return view('exams.create', compact('categories'));// exams/create.blade.phpビューを返す
    }

    // Store a newly created exam
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:exam_categories,id',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean', // バリデーションを追加

        ]);

        $exam = new Exam();
        $exam->user_id = Auth::id(); // 現在ログイン中のユーザーIDを取得
        $exam->name = $request->name;
        $exam->category_id = $request->category_id;
        $exam->description = $request->description;
        $exam->is_public = $request->is_public ?? 0;    
        $exam->save();

        return redirect()->route('exams.create')->with('success', 'Exam created successfully.');
    }

    // Show the edit form
    public function edit($id)
    {
        $exam = Exam::where('user_id', Auth::id())->findOrFail($id); // Ensure it's the user's exam
        return view('exams.edit', compact('exam'));
    }

    // Update an existing exam
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:exam_categories,id',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $exam = Exam::where('user_id', Auth::id())->findOrFail($id);
        $exam->name = $request->name;
        $exam->category_id = $request->category_id;
        $exam->description = $request->description;
        $exam->is_public = $request->is_public ?? 0;
        $exam->save();

        return redirect()->route('exams.edit', $exam->id)->with('success', 'Exam updated successfully.');
    }

    // Delete an exam
    public function destroy($id)
    {
        $exam = Exam::where('user_id', Auth::id())->findOrFail($id);
        $exam->del_flg = 1; // Mark as deleted
        $exam->save();

        return redirect()->route('exams.create')->with('success', 'Exam deleted successfully.');
    }
}