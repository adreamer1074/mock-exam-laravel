AWS ECS FargateへのLaravelアプリケーションデプロイ手順と注意点（まとめ）

1. デプロイの全体像
AWS ECS Fargate を利用したLaravelアプリケーションのデプロイは、主に以下の要素で構成されます。

- VPCとネットワークインフラ: アプリケーションの基盤となるネットワーク環境。
- データベース: RDSを利用。
- コンテナイメージ: LaravelアプリケーションとNginxをDockerイメージとして構築し、ECRに格納。
- ECS
    - クラスター
    - タスク定義
    - サービス
- ロードバランサー (ALB): 外部からのトラフィックをECSタスクに分散。
- IAM: AWSリソースへのアクセス権限を管理。

2. デプロイ手順（Terraformベース）

- VPC とサブネットの作成:
    - パブリックサブネット (ALB用) とプライベートサブネット (ECSタスク、RDS用) を用意。
    - インターネットゲートウェイとNAT Gatewayを設定し、プライベートサブネットからのインターネットアクセスを確保。
- セキュリティグループの定義:
    - ALB用SG: インバウンドでHTTP(80)/HTTPS(443)を全許可 (0.0.0.0/0)。アウトバウンドはECSタスクSGへのHTTP(80)を許可。
    - ECSタスク用SG: インバウンドでALB用SGからのHTTP(80)を許可。アウトバウンドでRDS用SGへのMySQL(3306)を許可し、インターネット(0.0.0.0/0)へのHTTPS(443)を許可（ログ送信、SSM接続など）。
    - RDS用SG: インバウンドでECSタスク用SGからのMySQL(3306)を許可。
- RDSデータベースの作成:
    - プライベートサブネットグループに配置し、ECSタスク用SGを関連付け。
    - データベース名、ユーザー名、パスワードを設定。
3. ECRリポジトリの作成:
    - NginxとPHP-FPMのDockerイメージを格納するためのプライベートリポジトリ。
    - Dockerイメージのビルドとプッシュ:
    - LaravelアプリケーションのコードとNginx設定を含むDockerイメージをビルドし、ECRにプッシュ。
    - Nginxの設定: NginxはPHP-FPMにリクエストを転送するプロキシとして機能します。fastcgi_passの設定が重要。
    - PHP-FPMのDockerfile: 必要なPHP拡張機能のインストール、Composerの実行、ファイル権限の設定 (storage, bootstrap/cache) が重要。
- ECSクラスターの作成:
    - Fargate起動タイプでコンテナを実行する論理的なグループ。
    - IAMロールの作成と権限付与:
        - ECSタスク実行ロール
        - ポリシー: AmazonECSTaskExecutionRolePolicy をアタッチ。
        - ECS Exec用: AmazonSSMManagedInstanceCore ポリシーもアタッチ。
        - ECSタスクロール (task_role_arn):
        - アプリケーション自体がAWSサービスにアクセスするため（例: S3、SES、RDSへのIAM認証など）に必要。
        - ポリシー: AmazonRDSReadOnlyAccess (例) など、アプリケーションに必要な権限を持つポリシーをアタッチ。
    - ECSタスク定義の作成:
        - NginxコンテナとPHP-FPMコンテナの定義を含める。
        - ECSサービスの作成:
        - enable_execute_command = true をECSサービスに設定し、ECS Execを有効化。
- Terraform の実行:
4. ECS Exec と SSM 接続
5. マイグレーションの実行戦略
手動実行 (ECS Exec): 開発段階や緊急時の一時的な手段として有効。
自動化 (CodeBuild または ENTRYPOINT スクリプト): 本番運用では、デプロイ時に自動でマイグレーションが実行されるような仕組み（CI/CDパイプラインの一部）を導入するのが推奨されます。
ENTRYPOINT スクリプトは手軽だが、マイグレーションが冪等でないと問題を起こす可能性があるため注意が必要。
CodeBuildは専用の環境で実行されるため、より安全かつ堅牢。
