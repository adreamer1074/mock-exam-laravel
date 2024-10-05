DB
 ### 1. **Users Table**
   - **id**: `INT (Primary Key)`
   - **name**: `VARCHAR`
   - **email**: `VARCHAR (Unique)`
   - **password**: `VARCHAR`
   - **role**: `ENUM('admin', 'standard', 'editor', etc.)` (User roles for access control)
   - **favorites**: `TEXT` (Serialized or JSON list of favorite exam IDs)
   - **created_at**: `TIMESTAMP`
   - **updated_at**: `TIMESTAMP`
   - **deleted_at**: `TIMESTAMP` (For logical deletion)

### 2. **Todo_List Table**
   - **id**: `INT (Primary Key)`
   - **user_id**: `INT (Foreign Key -> Users.id)`
   - **title**: `VARCHAR`
   - **description**: `TEXT`
   - **due_date**: `TIMESTAMP`
   - **notes**: `TEXT`
   - **created_at**: `TIMESTAMP`
   - **updated_at**: `TIMESTAMP`

### 3. **Exams Table**
   - **id**: `INT (Primary Key)`
   - **user_id**: `INT (Foreign Key -> Users.id)`
   - **name**: `VARCHAR (Unique per user)`
   - **category_id**: `INT (Foreign Key -> Exam_Categories.id)`
   - **is_public**: `BOOLEAN` (Exam visibility setting)
   - **created_at**: `TIMESTAMP`
   - **updated_at**: `TIMESTAMP`
   - **deleted_at**: `TIMESTAMP` (For logical deletion)

### 4. **Exam_Categories Table**
   - **id**: `INT (Primary Key)`
   - **name**: `VARCHAR` (e.g., Math, Science, etc.)
   - **description**: `TEXT`
   - **created_at**: `TIMESTAMP`
   - **updated_at**: `TIMESTAMP`

### 5. **Exam_Questions Table**
   - **id**: `INT (Primary Key)`
   - **exam_id**: `INT (Foreign Key -> Exams.id)`
   - **question_text**: `TEXT`
   - **explanation**: `TEXT` (Explanation for question review)
   - **created_at**: `TIMESTAMP`
   - **updated_at**: `TIMESTAMP`

### 6. **Question_Options Table**
   - **id**: `INT (Primary Key)`
   - **question_id**: `INT (Foreign Key -> Exam_Questions.id)`
   - **option_text**: `TEXT`
   - **is_correct**: `BOOLEAN`
   - **created_at**: `TIMESTAMP`
   - **updated_at**: `TIMESTAMP`

### 7. **Exam_Results Table**
   - **id**: `INT (Primary Key)`
   - **user_id**: `INT (Foreign Key -> Users.id)`
   - **exam_id**: `INT (Foreign Key -> Exams.id)`
   - **score**: `DECIMAL(5,2)`
   - **completed_at**: `TIMESTAMP`
   - **created_at**: `TIMESTAMP`
   - **updated_at**: `TIMESTAMP`

### 8. **Favorites Table**
   - **id**: `INT (Primary Key)`
   - **user_id**: `INT (Foreign Key -> Users.id)`
   - **exam_id**: `INT (Foreign Key -> Exams.id)`
   - **created_at**: `TIMESTAMP`

## 8 . ANSWERS
user_id: どのユーザーが回答したのかを記録します。
exam_id: どの試験の問題に対する回答かを保存します。
question_id: どの質問に対して回答したのかを追跡します。
option_id: ユーザーが選択した選択肢（オプション）を保存します。
is_correct: この回答が正解かどうかを示すフラグ。