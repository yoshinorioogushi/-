<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Note> $notes
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

    .notes-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .notes-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 20px;
    }

    .notes-header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: bold;
        color: #333;
    }

    .btn-new {
        background: #333;
        color: white;
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.3s ease;
    }

    .btn-new:hover {
        background: #555;
    }

    .notes-grid {
        display: grid;
        gap: 20px;
    }

    .note-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 24px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .note-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #999;
    }

    .note-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin: 0 0 12px 0;
        line-height: 1.6;
        word-break: break-word;
    }

    .note-preview {
        font-size: 14px;
        color: #666;
        margin: 0 0 16px 0;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        word-break: break-word;
    }

    .note-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #999;
        border-top: 1px solid #f0f0f0;
        padding-top: 12px;
    }

    .note-date {
        display: flex;
        gap: 16px;
    }

    .note-actions {
        display: flex;
        gap: 8px;
    }

    .btn-small {
        padding: 4px 12px;
        font-size: 12px;
        border-radius: 3px;
        text-decoration: none;
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

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state p {
        font-size: 16px;
        margin: 0 0 20px 0;
    }

    .paginator {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .paginator a, .paginator span {
        padding: 8px 12px;
        border-radius: 3px;
        text-decoration: none;
        font-size: 12px;
        color: #333;
    }

    .paginator a {
        background: #f0f0f0;
        transition: background 0.2s ease;
    }

    .paginator a:hover {
        background: #e0e0e0;
    }

    .paginator .active {
        background: #333;
        color: white;
    }
</style>

<div class="notes-container">
    <div class="notes-header">
        <h1>📝 メモ帳</h1>
        <?= $this->Html->link('新規作成', ['action' => 'add'], ['class' => 'btn-new']) ?>
    </div>

    <?php if (empty($notes)): ?>
        <div class="empty-state">
            <p>まだメモがありません</p>
            <?= $this->Html->link('最初のメモを作成', ['action' => 'add'], ['class' => 'btn-new']) ?>
        </div>
    <?php else: ?>
        <div class="notes-grid">
            <?php foreach ($notes as $note): ?>
                <?= $this->Html->link(
                    sprintf(
                        '<div class="note-card">
                            <h3 class="note-title">%s %s</h3>
                            <p class="note-preview">%s</p>
                            <div class="note-meta">
                                <div class="note-date">
                                    <span>作成: %s</span>
                                    <span>更新: %s</span>
                                </div>
                            </div>
                        </div>',
                        getIconForTitle($note->title),
                        h($note->title),
                        h(substr(strip_tags($note->content), 0, 100)),
                        $note->created->i18nFormat('yyyy年MM月dd日'),
                        $note->modified->i18nFormat('yyyy年MM月dd日 HH:mm')
                    ),
                    ['action' => 'view', $note->id],
                    [
                        'escape' => false,
                        'class' => 'note-link'
                    ]
                ) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>