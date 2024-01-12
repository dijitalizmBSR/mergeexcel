<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

header('Content-Type: application/json');

$allHeaders = [];
$totalRowCount = 0;

if (isset($_FILES['files'])) {
    foreach ($_FILES['files']['tmp_name'] as $index => $file) {
        try {
            // Dosya tipini kontrol edin ve uygun okuyucuyu yaratın
            $fileType = IOFactory::identify($file);
            $reader = IOFactory::createReaderForFile($file);

            if ($fileType == 'Csv') {
                // CSV dosyası için okuyucu ayarları
                $reader->setDelimiter(",");
                $reader->setEnclosure('"');
                $reader->setSheetIndex(0);
            }

            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file);
            $worksheet = $spreadsheet->getActiveSheet();

            // İlk satırı (sütun başlıklarını) al
            $headers = $worksheet->rangeToArray('A1:' . $worksheet->getHighestColumn() . '1', NULL, TRUE, FALSE)[0];
            $allHeaders[] = $headers;

            // Toplam satır sayısını hesapla
            $rowCount = $worksheet->getHighestRow();
            $totalRowCount += $rowCount - 1; // Başlık satırını çıkar

        } catch (ReaderException $e) {
            echo json_encode(['error' => "Dosya okunurken bir hata oluştu: " . $e->getMessage()]);
            exit;
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            echo json_encode(['error' => "Bir hata oluştu: " . $e->getMessage()]);
            exit;
        }
    }

    // Sütun başlıklarının eşleşip eşleşmediğini kontrol et
    $mismatch = false;
    $baseHeaders = $allHeaders[0];
    foreach ($allHeaders as $headers) {
        if ($headers !== $baseHeaders) {
            $mismatch = true;
            break;
        }
    }

// Yanıtı oluştur
$response = [
    'columns_mismatch' => $mismatch, // Değişiklik: mismatch anahtarını kullan
    'totalRows' => $totalRowCount,
    'headers' => $baseHeaders
];
echo json_encode($response);
exit; // Bu satır burada kalabilir


?>
