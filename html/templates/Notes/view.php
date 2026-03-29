<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Note $note
 */

// タイトルに基づいてアイコンを判定する関数
function getIconForTitle($title) {
    $title = strtolower($title);
    
    // キーワードマッピング
    $iconMap = [
        // 買い物関連
        '買い物' => '🛒',
        'shopping' => '🛒',
        
        // リスト関連
        'リスト' => '📋',
        'list' => '📋',
        'todo' => '✅',
        'タスク' => '✅',
        'task' => '✅',
        
        // プログラミング関連
        'cakephp' => '🍰',
        'php' => '🐘',
        'javascript' => '⚙️',
        'python' => '🐍',
        'code' => '💻',
        'debug' => '🐛',
        'api' => '🔌',
        'database' => '🗄️',
        'sql' => '🗄️',
        
        // ドキュメント関連
        'メモ' => '📝',
        'note' => '📝',
        'ドキュメント' => '📄',
        'document' => '📄',
        'テンプレート' => '📋',
        'template' => '📋',
        
        // 日記・記録関連
        '日記' => '📔',
        'diary' => '📔',
        'journal' => '📔',
        '記録' => '📌',
        'log' => '📌',
        '記事' => '📰',
        'article' => '📰',
        
        // 計画・スケジュール関連
        '計画' => '📅',
        'plan' => '📅',
        'schedule' => '📅',
        'カレンダー' => '📆',
        'calendar' => '📆',
        '目標' => '🎯',
        'goal' => '🎯',
        
        // 会議・コミュニケーション関連
        '会議' => '👥',
        'meeting' => '👥',
        '電話' => '☎️',
        'call' => '☎️',
        'チャット' => '💬',
        'chat' => '💬',
        
        // 学習関連
        '学習' => '📚',
        'learning' => '📚',
        '勉強' => '🎓',
        'study' => '🎓',
        'tutorial' => '📖',
        'チュートリアル' => '📖',
        
        // その他
        'テスト' => '✅',
        'test' => '✅',
        '完了' => '✔️',
        'done' => '✔️',
        'important' => '⭐',
        '重要' => '⭐',
        'idea' => '💡',
        'アイデア' => '💡',
        'bug' => '🐛',
        'feature' => '✨',
        '日報' => '📊',
        'report' => '📊',
    ];
    
    foreach ($iconMap as $keyword => $icon) {
        if (strpos($title, $keyword) !== false) {
            return $icon;
        }
    }
    
    // デフォルトアイコン
    return '📄';
}
?>

<style>
    body {
        background-color: #f9f9f9;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .note-view-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .note-header {
        margin-bottom: 40px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 20px;
    }

    .note-view-title {
        font-size: 36px;
        font-weight: bold;
        color: #333;
        margin: 0 0 20px 0;
        line-height: 1.4;
        word-break: break-word;
    }

    .note-view-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        color: #999;
    }

    .note-view-dates {
        display: flex;
        gap: 24px;
    }

    .note-body {
        background: white;
        border-radius: 4px;
        padding: 40px;
        margin-bottom: 40px;
        font-size: 16px;
        line-height: 1.8;
        color: #333;
        word-break: break-word;
    }

    .note-body p {
        margin: 0 0 24px 0;
    }

    .note-body p:last-child {
        margin-bottom: 0;
    }

    .note-body h2 {
        font-size: 24px;
        font-weight: bold;
        margin: 32px 0 16px 0;
    }

    .note-body h3 {
        font-size: 20px;
        font-weight: bold;
        margin: 28px 0 12px 0;
    }

    .note-body blockquote {
        border-left: 4px solid #ddd;
        padding: 0 0 0 20px;
        margin: 24px 0;
        color: #666;
        font-style: italic;
    }

    .note-body ul, .note-body ol {
        margin: 24px 0;
        padding-left: 24px;
    }

    .note-body li {
        margin: 8px 0;
    }

    .note-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        padding: 24px 0;
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
        margin: 0 40px 40px 40px;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: #f0f0f0;
        color: #333;
    }

    .btn-edit:hover {
        background: #e0e0e0;
    }

    .btn-delete {
        background: #fce4ec;
        color: #c2185b;
    }

    .btn-delete:hover {
        background: #f8bbd0;
    }

    .btn-back {
        background: #e8e8e8;
        color: #333;
    }

    .btn-back:hover {
        background: #d8d8d8;
    }

    .note-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        font-size: 12px;
        color: #999;
    }

    .note-footer a {
        color: #333;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 3px;
        background: #f0f0f0;
        transition: background 0.2s ease;
    }

    .note-footer a:hover {
        background: #e0e0e0;
    }
</style>

<div class="note-view-container">
    <div class="note-header">
        <h1 class="note-view-title"><?= getIconForTitle($note->title) ?> <?= h($note->title) ?></h1>
        <div class="note-view-meta">
            <div class="note-view-dates">
                <span>📅 作成: <?= $note->created->i18nFormat('yyyy年MM月dd日 HH:mm') ?></span>
                <span>✏️ 更新: <?= $note->modified->i18nFormat('yyyy年MM月dd日 HH:mm') ?></span>
            </div>
        </div>
    </div>

    <div class="note-body">
        <?= $this->Text->autoParagraph(h($note->content)); ?>
    </div>

    <div class="note-actions">
        <?= $this->Html->link('編集', ['action' => 'edit', $note->id], ['class' => 'btn-action btn-edit']) ?>
        <?= $this->Form->postLink('削除', ['action' => 'delete', $note->id], [
            'confirm' => 'このメモを削除してもよろしいですか？',
            'class' => 'btn-action btn-delete'
        ]) ?>
        <?= $this->Html->link('← 一覧に戻る', ['action' => 'index'], ['class' => 'btn-action btn-back']) ?>
    </div>

    <div class="note-footer">
        <span>ID: <?= h($note->id) ?></span>
        <div>
            <?= $this->Html->link('新規作成', ['action' => 'add']) ?>
        </div>
    </div>
</div>