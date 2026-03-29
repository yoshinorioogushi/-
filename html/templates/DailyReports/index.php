<?php
/**
 * @var \App\View\AppView $this
 */
?>

<style>
    .daily-report-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        background: #f9f9f9;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px 5px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
    }
    .btn-primary {
        background: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background: #0056b3;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background: #545b62;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    th {
        background: #f0f0f0;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    .section-title {
        font-size: 18px;
        font-weight: bold;
        margin: 30px 0 15px 0;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }
</style>

<div class="daily-report-container">
    <h1>📊 日報月別集計ツール</h1>

    <div class="card">
        <h2>データ処理</h2>
        <p>「日報ログ」の「大串佳紀」タブから成果データを読み込んで、月別に集計します。</p>
        
        <div class="section-title">処理を実行</div>
        <?= $this->Form->create(null, ['url' => ['controller' => 'DailyReports', 'action' => 'aggregate'], 'method' => 'post']) ?>
            <button type="submit" class="btn btn-primary">月別集計を実行</button>
        <?= $this->Form->end() ?>

        <div class="section-title">スプレッドシートへのリンク</div>
        <a href="<?= $sourceSheetUrl ?>" target="_blank" class="btn btn-secondary">📋 日報ログを開く</a>
        <a href="<?= $outputSheetUrl ?>" target="_blank" class="btn btn-secondary">📊 月表スプシを開く</a>
    </div>

    <?php if (!empty($formattedData)): ?>
    <div class="card">
        <div class="section-title">集計結果プレビュー</div>
        <table>
            <?php foreach ($formattedData as $i => $row): ?>
                <?php if ($i === 0): ?>
                    <thead>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <th><?= h($cell) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                <?php else: ?>
                    <tr>
                        <td><?= h($row[0]) ?></td>
                        <td style="text-align: center;"><?= h($row[1]) ?></td>
                        <td>
                            <ul style="margin: 0; padding-left: 20px;">
                                <?php foreach (explode("\n", $row[2]) as $item): ?>
                                    <?php if (!empty($item)): ?>
                                        <li><?= h($item) ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>
