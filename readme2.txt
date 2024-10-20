
DOCKER
	docker run -it -v $(pwd):/opt -w /opt laravelsail/php81-composer:latest /bin/bash
	composer create-project 'laravel/laravel:10.*' sail-example
	cd sail-example
	php artisan sail:install
	./vendor/bin/sail up
会員登録・ログイン機能の開発
	./vendor/bin/sail composer require laravel/breeze --dev)
	./vendor/bin/sail php artisan breeze:install
	- setup email(docker-compose-yml)
 データベースの準備
	*create migration
	 - php artisan make:migration create_users_table
	 - php artisan make:migration create_todo_list_table
	 - php artisan make:migration create_exam_categories_table
	 - php artisan make:migration create_exams_table
	 - php artisan make:migration create_exam_questions_table
	 - php artisan make:migration create_question_options_table 
	 - php artisan make:migration create_exam_results_table
	 - php artisan make:migration create_favorites_table
	 - php artisan make:migration create_answers_table
	 *exec migration(外部制約注意（外部制約は後で設定した方がいい)
	 - php artisan migrate (./vendor/bin/sail artisan migrate)
	 *seeder
	 - php artisan make:seeder UserSeeder
	 - php artisan make:seeder ExamSeeder
	 - php artisan make:seeder ExamCategorySeeder
	 - php artisan make:seeder QuestionSeeder
	 - php artisan make:seeder AnswerSeeder
	 - php artisan make:seeder TodoListSeeder
	 - php artisan make:seeder ExamResultSeeder
	 *model
	 - php artisan make:model Exam
	 - php artisan make:model Answer
	 - php artisan make:model User
	 - php artisan make:model ExamCategory
	 - php artisan make:model ExamResult
	 - php artisan make:model TodoList
	 *exeec seeder
	 - php artisan db:seed

	./vendor/bin/sail php artisan make:controller ExamController --resource
	
	./vendor/bin/sail php artisan make:controller AnswerController --resource
	./vendor/bin/sail php artisan make:controller UserController --resource
	./vendor/bin/sail php artisan make:controller ExamCategoryController --resource
	./vendor/bin/sail php artisan make:controller ExamResultController --resource
	./vendor/bin/sail php artisan make:controller TodoListController --resource

//s3
 ./vendor/bin/sail composer require league/flysystem-aws-s3-v3