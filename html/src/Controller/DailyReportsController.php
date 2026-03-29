<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\SheetsService;

class DailyReportsController extends AppController
{
    // Google Sheets のID
    private const SOURCE_SHEET_ID = '1mvrUyoeyCMaDnHphK_mvNFiABlcUZKkq7tDcJB3VSbU';
    private const OUTPUT_SHEET_ID = '17rmSiUV_uwiCt7LoJyZpEAC6wGNT4DIdmbqXjf2YrWg';

    /**
     * 日報データを読み込んで月別集計する
     */
    public function aggregate()
    {
        try {
            $sheetsService = new SheetsService();

            // ステップ1: 「大串佳紀」タブから B列（日付）と C列（成果）を読み込み
            $sourceData = $sheetsService->readData(
                self::SOURCE_SHEET_ID,
                '大串佳紀!B:C'
            );

            if (empty($sourceData)) {
                $this->Flash->error('日報ログからデータが取得できませんでした');
                return $this->redirect(['action' => 'index']);
            }

            // ステップ2: 月別に集計
            $aggregated = $sheetsService->aggregateByMonth($sourceData);

            // ステップ3: 月表形式に整形
            $formattedData = $sheetsService->formatForSheet($aggregated);

            // ステップ4: 新規スプシに書き込み
            $sheetsService->writeData(
                self::OUTPUT_SHEET_ID,
                'Sheet1!A1',
                $formattedData
            );

            $this->Flash->success('月別実績をスプシに出力しました！');
            $this->set(compact('aggregated', 'formattedData'));
        } catch (\Exception $e) {
            $this->Flash->error('エラーが発生しました: ' . $e->getMessage());
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * インデックス画面
     */
    public function index()
    {
        $this->set([
            'sourceSheetUrl' => 'https://docs.google.com/spreadsheets/d/' . self::SOURCE_SHEET_ID,
            'outputSheetUrl' => 'https://docs.google.com/spreadsheets/d/' . self::OUTPUT_SHEET_ID,
        ]);
    }
}
