<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\ExamResult;
use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use App\Models\QuestionOption;

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

    public function popularExams()
    {
        $popularExams = Exam::select('exams.id', 'exams.name', 'users.name as user_name', 'exam_categories.name as category_name', 'exams.description', 'exams.views', 'exams.created_at')
            ->join('users', 'users.id', '=', 'exams.user_id')
            ->join('exam_categories', 'exam_categories.id', '=', 'exams.category_id')
            ->where('exams.is_public', '=', 1)
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1)
                    ->orWhereNull('del_flg');
            })
            ->orderBy('exams.views', 'desc')
            ->limit(10)
            ->get();

        return view('popular-exams', compact('popularExams'));
    }

    public function allExams()
    {
        $allExams = Exam::select('exams.id', 'exams.name', 'users.name as user_name', 'exam_categories.name as category_name', 'exams.description', 'exams.created_at')
            ->join('users', 'users.id', '=', 'exams.user_id')
            ->join('exam_categories', 'exam_categories.id', '=', 'exams.category_id')
            ->where('exams.is_public', '=', 1)
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1)
                    ->orWhereNull('del_flg');
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
            ->with('category', 'user') // リレーションを事前ロード
            ->orderBy('created_at', 'desc')
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

    public function submitResult(Request $request, $examId)
    {
        // ユーザーのIDを取得
        $userId = auth()->id();
        // ユーザーと試験に対する最新のexam_result_idを取得
        $latestResultId = ExamResult::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->max('exam_result_id'); // 最大のexam_result_idを取得

        // もし最新のexam_result_idがnullなら1にセット
        $newResultId = $latestResultId ? $latestResultId + 1 : 1;

        // ユーザーが選択した答えをループして保存
        foreach ($request->input('answers') as $questionId => $optionIds) {
            // ラジオボタンの場合は単一の値（文字列）なので配列に変換
            if (!is_array($optionIds)) {
                $optionIds = [$optionIds];
            }

            foreach ($optionIds as $optionId) {
                ExamAnswer::create([
                    'user_id' => $userId,
                    'exam_id' => $examId,
                    'question_id' => $questionId,
                    'option_id' => $optionId,
                    'exam_result_id' => $newResultId // 同じexam_result_idをセット
                ]);
            }
        }
        // ユーザーの回答を取得
        $userAnswers = ExamAnswer::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->where('exam_result_id', $newResultId) // 最新のexam_result_idで絞り込み
            ->get();

        // スコアを計算
        $score = $this->calculateScore($userAnswers, $examId,$latestResultId);


        // 結果を保存する（ExamResultモデルを使用）
        ExamResult::create([
            'user_id' => $userId,
            'exam_id' => $examId,
            'score' => $score,
            'exam_result_id' => $newResultId, // 新しいexam_result_idをセット
            'completed_at' => now()
        ]);

        // 結果ページへリダイレクト
        return redirect()->route('exam.result', $examId)->withInput($request->all());
    }



    /**
     * 点数計算
     */
    private function calculateScore($userAnswers, $examId, $latestResultId)
    {
        // Exam IDからExamモデルを取得
        $exam = Exam::with('questions.options')->findOrFail($examId);
    
        $score = 0;
    
        foreach ($exam->questions as $question) {
            // 正しい回答を取得
            $correctAnswers = $question->options->where('is_correct', true)->pluck('id')->toArray();
    
            // ユーザーの回答があるか確認
            if (isset($userAnswers[$question->id])) {
                // 最新の exam_result_id に関連する回答のみを取得
                $selectedAnswers = $userAnswers[$question->id]
                    ->where('exam_result_id', $latestResultId) // ここで exam_result_id を確認
                    ->pluck('option_id')
                    ->toArray();
    
                // 正しい回答と選択した回答を比較
                if (array_diff($correctAnswers, $selectedAnswers) === [] && array_diff($selectedAnswers, $correctAnswers) === []) {
                    $score++;
                }
            }
        }
    
        return $score;
    }
    



    /**
     * Show result for a specific exam.
     */
    public function showResult($examId)
    {
        // ユーザーのIDを取得
        $userId = auth()->id();

        // 最新のExamResultのIDを取得（最新の提出）
        $latestResult = ExamResult::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->latest('exam_result_id') // 最新のexam_result_idでソート
            ->first();

        if (!$latestResult) {
            // 結果がない場合は404
            abort(404, 'No exam results found.');
        }

        // 最新のユーザーの回答を取得
        $userAnswers = ExamAnswer::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->where('exam_result_id', $latestResult->exam_result_id) // 最新の結果に関連する回答を取得
            ->get()
            ->groupBy('question_id');

        // 試験データを取得
        $exam = Exam::with('questions.options')->findOrFail($examId);

        // 結果ページを表示
        // 結果ページを表示
        return view('exams.result', [
            'exam' => $exam,
            'userAnswers' => $userAnswers,
            'score' => $latestResult->score,
            'submittedAt' => $latestResult->completed_at
        ]);
    }


    // Show the create form
    public function create()
    {
        $categories = ExamCategory::all(); // すべてのカテゴリを取得

        return view('exams.create', compact('categories')); // exams/create.blade.phpビューを返す
    }

    // Store a newly created exam
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'category_id' => 'required|exists:exam_categories,id',
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
            'questions.*.text' => 'required|string',
            'questions.*.options.*' => 'required|string',
            'questions.*.correct' => 'nullable|array',
        ]);

        // Create the exam
        $exam = Exam::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'is_public' => $validated['is_public'] ?? true,
        ]);

        // Loop through questions and save them
        foreach ($request->questions as $questionData) {
            $question = ExamQuestion::create([
                'exam_id' => $exam->id,
                'question_text' => $questionData['text'],
            ]);
            $correctAnswers = $questionData['correct'] ?? [];
            // Loop through options
            foreach ($questionData['options'] as $index => $option) {
                $question->options()->create([
                    'option_text' => $option,
                    'is_correct' => in_array($index, $correctAnswers), // 正解をチェック
                ]);
            }
        }

        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
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
