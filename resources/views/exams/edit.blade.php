<form action="{{ route('questions.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="question_text">Question Text</label>
        <input type="text" name="question_text" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="question_image">Question Image (optional)</label>
        <input type="file" name="question_image" class="form-control">
    </div>

    <div class="form-group">
        <label for="explanation">Explanation</label>
        <textarea name="explanation" class="form-control"></textarea>
    </div>

    <div class="form-group">
        <label for="explanation_image">Explanation Image (optional)</label>
        <input type="file" name="explanation_image" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
