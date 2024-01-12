<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Formdan gelen başlık satırı sayısını al
$headerRowCount = isset($_POST['headerRowCount']) ? (int)$_POST['headerRowCount'] : 1;

$spreadsheet = new Spreadsheet();
$outputSheet = $spreadsheet->getActiveSheet();
$firstFile = true;
$headers = null;
$totalRowCount = 0;

if (isset($_FILES['files'])) {
    foreach ($_FILES['files']['tmp_name'] as $index => $file) {
        if (!is_uploaded_file($file)) {
            die("Dosya yüklenmedi: " . $_FILES['files']['name'][$index]);
        }

        $fileType = IOFactory::identify($file);
        $reader = IOFactory::createReaderForFile($file);
        if ($fileType == 'Csv') {
            $reader->setDelimiter(",");
            $reader->setEnclosure('"');
            $reader->setSheetIndex(0);
        }

        $reader->setReadDataOnly(true);
        $loadedSheet = $reader->load($file)->getActiveSheet();

        foreach ($loadedSheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            // Her satır için yeni bir rowData dizisi oluştur
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // İlk dosyanın başlıklarını işle
            if ($firstFile && $row->getRowIndex() <= $headerRowCount) {
                $headers = $rowData;
                if ($row->getRowIndex() === $headerRowCount) {
                    // Sadece son başlık satırını ekle
                    $outputSheet->fromArray($rowData, NULL, 'A' . ++$totalRowCount);
                }
                continue;
            }

            // Diğer dosyalardaki başlıkları atla
            if (!$firstFile && $row->getRowIndex() <= $headerRowCount) {
                continue;
            }

            // Veriyi sayfaya ekle
            $outputSheet->fromArray($rowData, NULL, 'A' . ++$totalRowCount);
        }
        $totalRowCountFile = 'total_rows.txt'; // Toplam satır sayısının tutulacağı dosya adı
    if(file_exists($totalRowCountFile)){
        $previousTotal = (int)file_get_contents($totalRowCountFile); // Önceki toplamı oku
        $newTotal = $previousTotal + $totalRowCount; // Yeni toplamı hesapla
        file_put_contents($totalRowCountFile, $newTotal); // Yeni toplamı dosyaya yaz
    } else {
        file_put_contents($totalRowCountFile, $totalRowCount); // Dosya yoksa, şu anki toplamı yaz
    }

        // İlk dosya işlendikten sonra bu değişkeni false yap
        $firstFile = false;
    }

    // Dosyayı kaydet ve indirme için gönder
    $writer = new Xlsx($spreadsheet);
    $filename = 'IAMZON-merged_excel_' . date('Y-m-d_H-i-s') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    ob_end_clean();
    $writer->save('php://output');
    exit;
} else {
    die("Dosya yüklenmedi.");
}


// Log dosyasının adını belirle
$logFile = 'merge_log.txt';

// Kullanıcı bilgilerini al
$userIp = $_SERVER['REMOTE_ADDR']; // Kullanıcının IP adresi
$userAgent = $_SERVER['HTTP_USER_AGENT']; // Kullanıcının tarayıcı ve işletim sistemi bilgisi
$referer = $_SERVER['HTTP_REFERER'] ?? 'Bilinmiyor'; // Referrer bilgisi
$accept = $_SERVER['HTTP_ACCEPT'] ?? 'Bilinmiyor'; // Kabul edilen içerik türleri
$acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Bilinmiyor'; // Kabul edilen diller
$dateTime = date('Y-m-d H:i:s'); // Mevcut tarih ve saat

// Birleştirme işleminin log mesajını oluştur
$logMessage = "Birleştirme yapıldı - Tarih: {$dateTime}, IP: {$userIp}, Tarayıcı: {$userAgent}, Referrer: {$referer}, Accept: {$accept}, Accept-Language: {$acceptLanguage}, Birleştirilen Dosya Sayısı: {$totalRowCount}\n";

// Log mesajını dosyaya ekle
file_put_contents($logFile, $logMessage, FILE_APPEND);
?>
