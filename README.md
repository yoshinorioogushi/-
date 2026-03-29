# PHP + MySQL + CakePHP 5 Docker環境

このプロジェクトは、Docker ComposeでPHPとMySQLの開発環境を構築し、CakePHP 5フレームワークを統合したものです。

## 必要なもの

- Docker
- Docker Compose
- VS Code

## ディレクトリ構成

```
docker-php-mysql/
├── docker-compose.yml          # Docker Compose設定
├── Dockerfile                  # PHP用Dockerfile
├── .dockerignore               # Docker除外ファイル
├── .vscode/
│   └── tasks.json             # VS Codeタスク設定
├── html/                       # CakePHP 5アプリケーション
│   ├── webroot/               # ドキュメントルート（Webアクセス用）
│   ├── src/                   # ソースコード
│   ├── templates/             # Viewテプレート
│   ├── config/                # 設定ファイル
│   ├── bin/                   # コマンドラインツール
│   └── composer.json          # Composer依存関係
└── README.md
```

## セットアップ

1. VS Codeでこのディレクトリを開く
2. ターミナルで以下のコマンドを実行、またはVS Codeのタスクから実行

### Docker環境でのCakePHP初期化

CakePHP 5は既にインストール済みです。

**データベーステーブルの作成**:
```bash
docker compose exec php bin/cake migrations migrate
```

**Cakeコマンドの実行例**:
```bash
# Modelを生成
docker compose exec php bin/cake bake model Articles

# Controllerを生成
docker compose exec php bin/cake bake controller Articles

# Templateを生成
docker compose exec php bin/cake bake template Articles
```

## VS Code内での実行方法

### 方法1: タスクから実行（推奨）

1. `Cmd + Shift + D` または `Ctrl + Shift + D` キーを押す
2. 「Docker: Start Services」を選択して実行
3. ブラウザで `http://localhost:8000` を開く

### 方法2: ターミナルコマンド

```bash
# サービスを起動
docker-compose up -d

# ログを確認
docker-compose logs -f

# サービスを停止
docker-compose down

# イメージをリビルド
docker-compose up -d --build
```

## 利用可能なタスク

VS Codeで `Cmd + Shift + B`（またはタスクを表示）から以下のタスクを実行できます：

- **Docker: Start Services** - PHPとMySQLを起動
- **Docker: Stop Services** - 全てのサービスを停止
- **Docker: View Logs** - サービスのログをリアルタイム表示
- **Docker: Rebuild Services** - イメージをリビルドして起動
- **Docker: MySQL Shell** - MySQLのインタラクティブシェルを起動
- **Docker: PHP Shell** - PHPコンテナのBashシェルを起動

## アクセス情報

| サービス | URL/ポート | ユーザー | パスワード |
|---------|-----------|---------|-----------|
| CakePHP | http://localhost:8000 | - | - |
| MySQL   | localhost:3306 | root | password |

### CakePHPへのアクセス

ブラウザで以下にアクセスするとCakePHPのウェルカムページが表示されます：

```
http://localhost:8000
```

CakePHP 5では、データベースに正常に接続できれば、ページに「データベース接続 OK」というメッセージが表示されます。

## CakePHPでのデータベース接続

CakePHP 5では、`config/app_local.php`で自動的にDocker環境のMySQL接続情報に設定されています。

環境変数で設定をオーバーライド可能です：

```bash
docker compose exec php bash -c "DB_HOST=mysql DB_USER=root DB_PASSWORD=password DB_NAME=myapp bin/cake migrations migrate"
```

### 手動接続テスト (PHP)

```php
$conn = new mysqli('mysql', 'root', 'password', 'myapp');
if ($conn->connect_error) {
    echo "接続エラー: " . $conn->connect_error;
} else {
    echo "接続成功！";
}
```

## トラブルシューティング

### ポートが既に使用されている場合

`docker-compose.yml`の「ports」セクションを編集してください：

```yaml
ports:
  - "8080:80"  # 8080に変更
```

### データベースに接続できない場合

1. MySQLコンテナが起動しているか確認

```bash
docker-compose ps
```

2. MySQLのログを確認

```bash
docker-compose logs mysql
```

3. サービスを再起動

```bash
docker-compose restart mysql
```

## ファイルの永続化

- PHPファイルは `html/` ディレクトリに配置してください
- MySQLデータは自動的にボリューム（`mysql_data`）に保存されます

## 編集とリロード

- PHPファイルを編集すると、ブラウザをリロードすれば変更が反映されます
- Dockerイメージを変更した場合は、「Docker: Rebuild Services」を実行してください
