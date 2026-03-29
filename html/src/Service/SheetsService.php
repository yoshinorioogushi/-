<?php
declare(strict_types=1);

namespace App\Service;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class SheetsService
{
    private Client $client;
    private Sheets $service;
    private string $keyFilePath;

    public function __construct()
    {
        $this->keyFilePath = CONFIG . 'sheets-key.json';
        $this->initializeClient();
    }

    private function initializeClient(): void
    {
        $this->client = new Client();
        $this->client->setApplicationName('Daily Report Aggregator');
        $this->client->setAuthConfig($this->keyFilePath);
        $this->client->addScope(Sheets::SPREADSHEETS);
        $this->service = new Sheets($this->client);
    }

    /**
     * スプレッドシートのデータを読み込む
     * @param string $spreadsheetId スプレッドシートID
     * @param string $range 読み込み範囲（例："大串佳紀!B:C"）
     * @return array [[date, achievement], ...]
     */
    public function readData(string $spreadsheetId, string $range): array
    {
        try {
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
            return $values ?? [];
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to read sheets: " . $e->getMessage());
        }
    }

    /**
     * スプレッドシートにデータを書き込む
     * @param string $spreadsheetId スプレッドシートID
     * @param string $range 書き込み範囲
     * @param array $values 書き込むデータ
     */
    public function writeData(string $spreadsheetId, string $range, array $values): void
    {
        try {
            $valueRange = new ValueRange(['values' => $values]);
            $options = ['valueInputOption' => 'USER_ENTERED'];
            $this->service->spreadsheets_values->update($spreadsheetId, $range, $valueRange, $options);
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to write sheets: " . $e->getMessage());
        }
    }

    /**
     * 月別にデータを集計
     * @param array $data [[date, achievement], ...]
     * @return array [['2026年2月', [achievements...]], ['2026年3月', [achievements...]]]
     */
    public function aggregateByMonth(array $data): array
    {
        $aggregated = [];

        foreach ($data as $row) {
            if (empty($row[0]) || empty($row[1])) {
                continue; // 空行をスキップ
            }

            $dateStr = $row[0];
            $achievement = $row[1];

            // 日付をパース（例："2026/2/1", "2026-02-01" など）
            $date = $this->parseDate($dateStr);
            if (!$date) {
                continue;
            }

            $monthKey = $date->format('Y年n月'); // "2026年2月" 形式

            if (!isset($aggregated[$monthKey])) {
                $aggregated[$monthKey] = [];
            }

            // 成果内容を整理：「■本日(◯/◯)までの成果」セクションを抽出
            $achievements = $this->extractAchievements($achievement);
            $aggregated[$monthKey] = array_merge($aggregated[$monthKey], $achievements);
        }

        // 月ごとに重複を削除してソート
        foreach ($aggregated as $month => &$items) {
            $items = array_values(array_unique($items));
            sort($items);
        }

        // 月をソート（昇順）
        uksort($aggregated, function ($a, $b) {
            return strtotime("1 $a") - strtotime("1 $b");
        });

        return $aggregated;
    }

    /**
     * 日付文字列をパース
     */
    private function parseDate(string $dateStr): ?\DateTime
    {
        // 複数の日付形式に対応
        $formats = [
            'Y/n/j',      // 2026/2/1
            'Y-m-d',      // 2026-02-01
            'Y年n月j日',  // 2026年2月1日
        ];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateStr);
            if ($date !== false) {
                return $date;
            }
        }

        return null;
    }

    /**
     * 「■本日までの成果」セクションから成果内容を抽出
     */
    private function extractAchievements(string $content): array
    {
        $achievements = [];

        // 「■本日」から「■」の次の行または最後までを抽出
        if (preg_match('/■本日.*?(.*?)(?=■|$)/s', $content, $matches)) {
            $section = $matches[1];

            // 「・」で始まる行を抽出
            if (preg_match_all('/・([^\n]+)/u', $section, $matches)) {
                $achievements = array_map('trim', $matches[1]);
            }
        }

        return $achievements;
    }

    /**
     * 月表形式でデータを整形
     * @param array $aggregated 月別集計データ
     * @return array [[month, count, achievements], ...]
     */
    public function formatForSheet(array $aggregated): array
    {
        $result = [['月', '件数', '成果']]; // ヘッダー

        foreach ($aggregated as $month => $achievements) {
            $result[] = [
                $month,
                count($achievements),
                implode("\n", $achievements),
            ];
        }

        return $result;
    }
}
