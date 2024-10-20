<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //form nameの属性で指定 
            'category_id' => 'required|exists:exam_categories,id', // 存在するカテゴリID
            'name' => 'required|string|max:255', // 名前は必須、255文字以内
            'is_public' => 'boolean', // 公開フラグ（真偽値）
            'questions.*.text' => 'required|string', // 各質問のテキストは必須
            'questions.*.options.*' => 'required|string', // 各質問の選択肢は必須
            'questions.*.correct' => 'nullable|array', // 正しい回答は配列
            'questions.*.question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 質問画像は任意
            'questions.*.explanation_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 説明画像は任意
        ];
    }

    //表示プロパーティ名を設定
    public function attributes()
    {
        return [
            'category_id' => 'カテゴリ',
            'name' => '試験名',
            'is_public' => '共有範囲',
            'questions.*.text' => '問題内容',
            'questions.*.options.*' => '選択肢',
        ];
    }
}
