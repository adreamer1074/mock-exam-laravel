<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExamCreateRequest;
use App\Models\Exam; // Examモデルを使用
use App\Models\ExamCategory; // ExamCategoryモデルを使用
use App\Models\ExamResult; // ExamResultモデルを使用
use App\Models\ExamAnswer; // ExamAnswerモデルを使用
use App\Models\ExamQuestion; // ExamQuestionモデルを使用
use App\Models\QuestionOption; // QuestionOptionモデルを使用

use Illuminate\Support\Facades\Auth; // 認証用のファサードを使用
use App\Models\User; // Userモデルを使用
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Transaction使用



class ExamController extends Controller
{
    /**
     * Show the home page with categories.
     * ホームページを表示し、試験カテゴリを取得する
     */
    public function home()
    {
        // すべての試験カテゴリを取得
        $categories = ExamCategory::all();
        // カテゴリをビューに渡す
        return view('home', compact('categories'));
    }

    /**
     * Show popular exams based on views.
     * 人気の試験を表示
     */
    public function popularExams()
    {
        // 人気の試験を取得（公開、削除されていないもの）
        $popularExams = Exam::select('exams.id', 'exams.name', 'users.name as user_name', 'categories.name as category_name', 'exams.description', 'exams.views', 'exams.created_at')
            ->join('users', 'users.id', '=', 'exams.user_id') // ユーザー情報を結合
            ->join('categories', 'categories.id', '=', 'exams.category_id') // カテゴリ情報を結合
            ->where('exams.is_public', '=', 1) // 公開の試験のみ
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1) // 削除されていない試験
                    ->orWhereNull('del_flg');
            })
            ->orderBy('exams.views', 'desc') // ビュー数で降順ソート
            ->limit(10) // 上位10件取得
            ->get();

        // 人気の試験をビューに渡す
        return view('popular-exams', compact('popularExams'));
    }

    /**
     * Show all exams.
     * すべての試験を表示
     */
    public function allExams()
    {
        // すべての試験を取得（公開、削除されていないもの）
        $allExams = Exam::select('exams.id', 'exams.name', 'users.name as user_name', 'categories.name as category_name', 'exams.description', 'exams.created_at')
            ->join('users', 'users.id', '=', 'exams.user_id') // ユーザー情報を結合
            ->join('categories', 'categories.id', '=', 'exams.category_id') // カテゴリ情報を結合
            ->where('exams.is_public', '=', 1) // 公開の試験のみ
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1) // 削除されていない試験
                    ->orWhereNull('del_flg');
            })
            ->orderBy('exams.created_at', 'desc') // 作成日で降順ソート
            ->get();

        // すべての試験をビューに渡す
        return view('all-exams', compact('allExams'));
    }

    /**
     * Show exams by category.
     * カテゴリ別に試験を表示
     */
    public function showByCategory($id)
    {
        // 特定のカテゴリに属する試験を取得
        $exams = Exam::where('category_id', $id)
            ->where('is_public', 1) // 公開の試験のみ
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1) // 削除されていない試験
                    ->orWhereNull('del_flg');
            })
            ->with('user') // ユーザー情報も一緒に取得
            ->orderBy('created_at', 'desc') // 作成日で降順ソート
            ->get();

        // カテゴリの詳細を取得
        $category = ExamCategory::find($id);

        // 試験とカテゴリをビューに渡す
        return view('exams.category', compact('exams', 'category'));
    }

    /**
     * Show all public exams that are not deleted.
     * 公開されていて削除されていない試験を表示
     */
    public function index()
    {
        // 公開の試験を取得（削除されていないもの）
        $exams = Exam::where('is_public', 1)
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1) // 削除されていない試験
                    ->orWhereNull('del_flg');
            })
            ->with('category', 'user') // リレーションを事前にロード
            ->orderBy('created_at', 'desc') // 作成日で降順ソート
            ->get();

        // 試験をビューに渡す
        return view('exams.index', compact('exams'));
    }

    /**
     * Display the specified exam.
     * 特定の試験を表示
     */
    public function show($id)
    {
        // 試験とその関連データを取得
        $exam = Exam::with(['questions.options', 'user', 'category']) // 質問と選択肢、ユーザー、カテゴリを取得
            ->where(function ($query) {
                $query->where('del_flg', '!=', 1) // 削除されていない試験
                    ->orWhereNull('del_flg');
            })
            ->where('is_public', 1) // 公開の試験のみ
            ->findOrFail($id); // 試験が見つからない場合は404エラー

        // 試験データをビューに渡す
        return view('exams.show', compact('exam'));
    }

    /**
     * Submit the exam result.
     * 試験結果を提出する
     */
    public function submitResult(Request $request, $examId)
    {
        // ユーザーのIDを取得
        $userId = auth()->id();

        // ユーザーがこの試験に対して持っている最新の結果IDを取得
        $latestResultId = ExamResult::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->max('exam_result_id'); // 最大のexam_result_idを取得

        // 最新のexam_result_idがない場合は1に設定
        $newResultId = $latestResultId ? $latestResultId + 1 : 1;

        // ユーザーが選択した答えを保存
        foreach ($request->input('answers') as $questionId => $optionIds) {
            // ラジオボタンの場合は単一の値（文字列）なので配列に変換
            if (!is_array($optionIds)) {
                $optionIds = [$optionIds];
            }

            foreach ($optionIds as $optionId) {
                // ユーザーの回答を保存
                ExamAnswer::create([
                    'user_id' => $userId,
                    'exam_id' => $examId,
                    'question_id' => $questionId,
                    'option_id' => $optionId,
                    'exam_result_id' => $newResultId // 同じexam_result_idを設定
                ]);
            }
        }

        // ユーザーの回答を取得
        $userAnswers = ExamAnswer::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->where('exam_result_id', $newResultId) // 最新のexam_result_idでフィルタ
            ->get();

        // スコアを計算
        $score = $this->calculateScore($userAnswers, $examId, $latestResultId);

        // 結果を保存
        ExamResult::create([
            'user_id' => $userId,
            'exam_id' => $examId,
            'score' => $score,
            'exam_result_id' => $newResultId, // 新しいexam_result_idを設定
            'completed_at' => now() // 提出日時を記録
        ]);

        // 結果ページへリダイレクト
        return redirect()->route('exam.result', $examId)->withInput($request->all());
    }

    /**
     * Calculate the score based on user answers.
     * ユーザーの回答に基づいて点数を計算
     */
    private function calculateScore($userAnswers, $examId, $latestResultId)
    {
        // 試験IDから試験モデルを取得
        $exam = Exam::with('questions.options')->findOrFail($examId);

        $score = 0; // スコア初期化

        foreach ($exam->questions as $question) {
            // 正しい回答を取得
            $correctAnswers = $question->options->where('is_correct', true)->pluck('id')->toArray();

            // ユーザーの回答があるか確認
            if (isset($userAnswers[$question->id])) {
                // 最新のexam_result_idに関連する回答を取得
                $selectedAnswers = $userAnswers[$question->id]
                    ->where('exam_result_id', $latestResultId) // exam_result_idを確認
                    ->pluck('option_id')
                    ->toArray();

                // 正しい回答と選択した回答を比較
                if (array_diff($correctAnswers, $selectedAnswers) === [] && array_diff($selectedAnswers, $correctAnswers) === []) {
                    $score++; // 正解ならスコアを加算
                }
            }
        }

        return $score; // 計算したスコアを返す
    }

    /**
     * Show result for a specific exam.
     * 特定の試験の結果を表示
     */
    public function showResult($examId)
    {
        // ユーザーのIDを取得
        $userId = auth()->id();

        // 最新の試験結果を取得（最新の提出）
        $latestResult = ExamResult::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->latest('exam_result_id') // 最新のexam_result_idでソート
            ->first();

        // 結果がない場合は404エラーを返す
        if (!$latestResult) {
            abort(404, 'No exam results found.');
        }

        // 最新のユーザーの回答を取得
        $userAnswers = ExamAnswer::where('user_id', $userId)
            ->where('exam_id', $examId)
            ->where('exam_result_id', $latestResult->exam_result_id) // 最新の結果に関連する回答を取得
            ->get()
            ->groupBy('question_id'); // 質問IDでグループ化

        // 試験データを取得
        $exam = Exam::with('questions.options')->findOrFail($examId);

        // 結果ページを表示
        return view('exams.result', [
            'exam' => $exam,
            'userAnswers' => $userAnswers,
            'score' => $latestResult->score,
            'submittedAt' => $latestResult->completed_at // 提出日時を表示
        ]);
    }

    /**
     * Show the create form for new exams.
     * 新しい試験作成用のフォームを表示
     */
    public function create()
    {
        $categories = ExamCategory::all(); // すべてのカテゴリを取得

        // 試験作成フォームをビューに渡す
        return view('exams.create', compact('categories'));
    }

    /**
     * Store a newly created exam.
     * 新しく作成した試験を保存
     */
    public function store(ExamCreateRequest $request)
    {

        try {
            // リクエストデータを検証
            // $validated = $request->validate([
            //     'category_id' => 'required|exists:categories,id', // 存在するカテゴリID
            //     'name' => 'required|string|max:255', // 名前は必須、255文字以内
            //     'is_public' => 'boolean', // 公開フラグ（真偽値）
            //     'questions.*.text' => 'required|string', // 各質問のテキストは必須
            //     'questions.*.options.*' => 'required|string', // 各質問の選択肢は必須
            //     'questions.*.correct' => 'nullable|array', // 正しい回答は配列
            //     'questions.*.question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 質問画像は任意
            //     'questions.*.explanation_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 説明画像は任意
            // ]);

            $validated = $request->all();

            // 試験を作成
            $exam = Exam::create([
                'user_id' => Auth::id(), // 作成者のユーザーIDを設定
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description'=>$validated['description'],
                'is_public' => $validated['is_public'] ?? true, // デフォルトで公開
            ]);

            // 質問をループして保存
            foreach ($request->questions as $questionData) {
                // 画像のアップロード処理
                $questionImageUrl = null;
                $explanationImageUrl = null;
                $explanationData = null;

                // question_image が存在すれば S3 にアップロード
                if (isset($questionData['question_image'])) {
                    $questionImageUrl = $questionData['question_image']->store('questions', 's3');
                    $questionImageUrl = Storage::disk('s3')->url($questionImageUrl); // S3のURLを取得
                }

                // explanation_image が存在すれば S3 にアップロード
                if (isset($questionData['explanation_image'])) {
                    $explanationImageUrl = $questionData['explanation_image']->store('explanations', 's3');
                    $explanationImageUrl = Storage::disk('s3')->url($explanationImageUrl); // S3のURLを取得
                }

                // 質問を保存
                $question = ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_text' => $questionData['text'],
                    'question_image' => $questionImageUrl, // 質問画像URLを保存
                    'explanation_image' => $explanationImageUrl, // 説明画像URLを保存
                    'explanation' => $explanationData['text']??null,

                ]);

                $correctAnswers = $questionData['correct'] ?? [];

                // 選択肢をループして保存
                foreach ($questionData['options'] as $index => $option) {
                    $question->options()->create([
                        'option_text' => $option,
                        'is_correct' => in_array($index, $correctAnswers), // 正解かどうかをチェック
                    ]);
                }
            }
            // 試験作成完了メッセージと共に試験一覧ページへリダイレクト
            flash()->success('Exam created successfully.');
            return redirect()->route('exams.index');
            // Transaction(Commit)
            DB::commit();
        } catch (\Throwable $th) {
            // Transaction(Rollback)
            DB::rollBack();
            flash()->error('Something Was Wrong!.');

            \Log::debug(print_r($th->getMessage(), true));
            throw $th;
        }
    }

    /**
     * Show the edit form for an exam.
     * 試験の編集フォームを表示
     */
    public function edit($id)
    {
        // ユーザーの試験のみを取得（所有者確認）
        $exam = Exam::where('user_id', Auth::id())->findOrFail($id);
        return view('exams.edit', compact('exam')); // 編集フォームを表示
    }

    /**
     * Update an existing exam.
     * 既存の試験を更新
     */
    public function update(Request $request, $id)
    {
        // リクエストデータを検証
        $request->validate([
            'name' => 'required|string|max:255', // 名前は必須
            'category_id' => 'required|exists:categories,id', // 存在するカテゴリID
            'description' => 'nullable|string', // 説明は任意
            'is_public' => 'boolean', // 公開フラグ
        ]);

        // ユーザーの試験のみを取得（所有者確認）
        $exam = Exam::where('user_id', Auth::id())->findOrFail($id);
        $exam->name = $request->name; // 名前を更新
        $exam->category_id = $request->category_id; // カテゴリIDを更新
        $exam->description = $request->description; // 説明を更新
        $exam->is_public = $request->is_public ?? 0; // 公開フラグを更新
        $exam->save(); // 更新を保存

        // 試験編集ページへリダイレクト
        return redirect()->route('exams.edit', $exam->id)->with('success', 'Exam updated successfully.');
    }

    /**
     * Delete an exam.
     * 試験を削除
     */
    public function destroy($id)
    {
        // ユーザーの試験のみを取得（所有者確認）
        $exam = Exam::where('user_id', Auth::id())->findOrFail($id);
        $exam->del_flg = 1; // 削除フラグを立てる
        $exam->save(); // 更新を保存

        // 試験作成ページへリダイレクト
        return redirect()->route('exams.create')->with('success', 'Exam deleted successfully.');
    }
}
