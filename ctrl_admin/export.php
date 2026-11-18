<?php
//**************************************************
// 初期処理
//**************************************************
    session_start();

    require_once('../model/dbconnect.php');

    require_once('../model/dbfunction_admin.php');

    // Excel出力で利用する
    require_once('../vendor/autoload.php');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
//**************************************************
// ログインチェック
//**************************************************
    if(!isset($_SESSION['admin_is_login']) || $_SESSION['admin_is_login'] !== true){
        header("location: adm_login.php"); // 管理者ログイン画面に戻す
        exit();
    }
//**************************************************
// 変数取得
//**************************************************
    // ログイン中ユーザーの情報と権限フラグ
    $user_id    = isset($_SESSION['admin_user_id']) ? $_SESSION['admin_user_id'] : 0;
    $is_admin   = isset($_SESSION['admin_is_admin']) ? $_SESSION['admin_is_admin'] : 0;
    $is_teacher = isset($_SESSION['admin_is_teacher']) ? $_SESSION['admin_is_teacher'] : 0;

    // 選択されたクラスID（表示はGET、出力はPOSTで受け取る）
    $class_id = "";
    if (isset($_POST['class_id'])) {
        $class_id = $_POST['class_id'];
    } elseif (isset($_GET['class_id'])) {
        $class_id = $_GET['class_id'];
    }
    $is_export = isset($_POST['export']) && $_POST['export'] === "1";
    $arrErr = array();
//**************************************************
// データ取得
//**************************************************
    // 選択できるクラス（管理者は全クラス、教員は担当クラスのみ）
    $classes = $is_admin ? getClasses() : getClasses($user_id);
//**************************************************
// 入力確認
//**************************************************
    // 選択肢にないクラス（担当外のクラスなど）は選択されていない扱いにする
    $selected_class = null;
    foreach ($classes as $class) {
        if ((string)$class['id'] === (string)$class_id) {
            $selected_class = $class;
            break;
        }
    }
    if ($class_id !== "" && $selected_class === null) {
        $arrErr['class_id'] = "選択できないクラスです";
    }
//**************************************************
// 出力データ取得
//**************************************************
    // 選択したクラスに所属する学生の一覧
    $class_users = ($selected_class !== null) ? getClassUsers((int)$selected_class['id']) : [];

//**************************************************
// Excel出力
//**************************************************
    if ($is_export && $selected_class !== null) {
        // 1.ブックとシートを用意
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle('アカウント一覧');

        // 2.見出し行＋データ行を作ってA1セルから書き込む
        $rows = [['ID', '学籍番号', 'ユーザー名', '登録日']];

        // 学籍番号を抽出するための正規表現
        $pattern = '/^[a-z](\d{7})@st\.kanazawa-it\.ac\.jp$/i';
        foreach ($class_users as $u) {
            if (preg_match($pattern, $u['email'], $m)) {
                $studentId = $m[1];
            } else {
                $studentId = null; // 学生以外のアドレスは学籍番号なし
            }
            // 変換した値を格納
            $rows[] = [$u['id'], $studentId, $u['user_name'], $u['created_at']];
        }
        $sheet->fromArray($rows, null, 'A1');

        // [装飾]
        $last_colum = $sheet->getHighestColumn();
        $header_range = 'A1:' . $last_colum . '1';
        
        // 太字
        $sheet->getStyle($header_range)->getFont()->setBold(true);
        // 塗りつぶしの種類
        $sheet->getStyle($header_range)->getFill()->setFillType(Fill::FILL_SOLID);
        // 塗りつぶしの色
        $sheet->getStyle($header_range)->getFill()->getStartColor()->setARGB('FFDDEBF7');
        // 幅調整
        foreach (range('A', $last_colum) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 2枚目のシート（確認用）
        $sheet2 = $spreadsheet->createSheet()->setTitle('Chapter1(現在)');
        $sheet2->fromArray($rows, null, 'A1');

       // 太字
        $sheet2->getStyle($header_range)->getFont()->setBold(true);
        // 塗りつぶしの種類
        $sheet2->getStyle($header_range)->getFill()->setFillType(Fill::FILL_SOLID);
        // 塗りつぶしの色
        $sheet2->getStyle($header_range)->getFill()->getStartColor()->setARGB('FFDDEBF7');
        // 幅調整
        foreach (range('A', $last_colum) as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        // デフォルトで開くシートの設定　0は1枚目
        $spreadsheet->setActiveSheetIndex(0);
        // 3.ダウンロード用のヘッダー
        if (ob_get_length()) ob_end_clean();
        $filename = $selected_class['class_name'] . '_' . date('Ymd') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename*=UTF-8''".rawurlencode($filename));
        header('Cache-Control: max-age=0');

        // 4.ブラウザへ出力して終了
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view_admin/export.php');
?>
