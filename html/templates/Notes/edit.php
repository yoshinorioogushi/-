<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Note $note
 */
?>

<style>
    body {
        background-color: #f9f9f9;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .note-form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .note-form-header {
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 20px;
    }

    .note-form-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: bold;
        color: #333;
    }

    .btn-cancel {
        padding: 10px 20px;
        background: #f0f0f0;
        color: #333;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.2s ease;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    .note-form {
        background: white;
        border-radius: 4px;
        padding: 40px;
    }

    .form-group {
        margin-bottom: 32px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: bold;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.2s ease;
        box-sizing: border-box;
    }

    .form-group input[type="text"]:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #333;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
    }

    .form-group input[type="text"] {
        font-size: 16px;
        padding: 12px;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 400px;
        line-height: 1.6;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 40px;
        border-top: 1px solid #e0e0e0;
        padding-top: 24px;
    }

    .btn-submit {
        background: #333;
        color: white;
        padding: 12px 32px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .btn-submit:hover {
        background: #555;
    }

    .btn-submit:active {
        background: #222;
    }

    .btn-delete {
        background: #fce4ec;
        color: #c2185b;
        border: none;
        padding: 12px 32px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s ease;
        text-decoration: none;
    }

    .btn-delete:hover {
        background: #f8bbd0;
    }
</style>

<div class="note-form-container">
    <div class="note-form-header">
        <h1>✏️ メモを編集</h1>
        <?= $this->Html->link('キャンセル', ['action' => 'view', $note->id], ['class' => 'btn-cancel']) ?>
    </div>

    <div class="note-form">
        <?= $this->Form->create($note) ?>
            <div class="form-group">
                <?= $this->Form->label('title', 'タイトル') ?>
                <?= $this->Form->input('title', [
                    'type' => 'text',
                    'label' => false,
                    'required' => true
                ]) ?>
            </div>

            <div class="form-group">
                <?= $this->Form->label('content', '内容') ?>
                <?= $this->Form->input('content', [
                    'type' => 'textarea',
                    'label' => false,
                    'required' => true
                ]) ?>
            </div>

            <div class="form-actions">
                <?= $this->Form->button('保存する', ['class' => 'btn-submit']) ?>
                <?= $this->Form->postLink(
                    '削除する',
                    ['action' => 'delete', $note->id],
                    [
                        'confirm' => 'このメモを削除してもよろしいですか？',
                        'class' => 'btn-delete'
                    ]
                ) ?>
                <?= $this->Html->link('キャンセル', ['action' => 'view', $note->id], ['class' => 'btn-cancel']) ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>
