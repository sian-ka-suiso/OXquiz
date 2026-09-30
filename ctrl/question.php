<?php
//**************************************************
// 初期処理
//**************************************************
    session_start();

    require_once('../model/dbconnect.php');

    require_once('../model/dbfunction.php');

//**************************************************
// 変数取得
//**************************************************
    $is_login = isset($_SESSION['is_login']) ? $_SESSION['is_login'] : "";
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : "";
    $is_guest = !empty($_SESSION['guest']);
    // ポートフォリオ用デモアカウントは利用規約への同意を求めない
    $is_demo_account = ($email === 'demo@email.com');
//**************************************************
// ログインチェック
//**************************************************
    if(!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true){
        header("location: index.php"); // ログイン画面に戻す
        exit();
    }
//**************************************************
// 利用規約チェック
//**************************************************
    if(!$is_guest && !$is_demo_account && !hasTosAgreed((int)$user_id)){
        header("location: tos_agree.php");
        exit();
    }
//**************************************************
// パラメータ・表示データ取得
//**************************************************
    // チャプターid
    $chapter_id = isset($_GET['chapter_id']) ? $_GET['chapter_id'] : "";
    // セクションid
    $section_id = isset($_GET['section_id']) ? $_GET['section_id'] : "";
    // クエスチョンid
    $question_id = isset($_GET['question_id']) ? $_GET['question_id'] : "";
    // クエスチョンid配列
    $question_ids = getQuestionIds($section_id);
    // 問題番号
    $question_number = isset($_GET['qn']) ? $_GET['qn'] : "";
    // チャプター名＋フォルダー名
    $chapter_names = getChapterNames($chapter_id);
    // セクション名＋フォルダー名
    $section_names = getSectionNames($section_id);
    // 選択肢数＋正答
    $questions = getQuestions($question_id);
    // 問題数カウント
    $question_count = count($question_ids);
    // 解説id（評価の高い順）
    $explanation_ids = getExplanationIds($question_id);
    // 数学ナビのリンク名とURL
    $math_navs = getNavs($question_id);
    // 正答率（一定数の回答データが集まるまでは「集計中」と表示する）
    $ACCURACY_MIN_ANSWER_COUNT = 20;
    $accuracy = getQuestionAccuracy((int)$question_id);
    if ($accuracy['answer_count'] >= $ACCURACY_MIN_ANSWER_COUNT) {
        $accuracy_text = round($accuracy['correct_count'] / $accuracy['answer_count'] * 100) . '％';
    } else {
        $accuracy_text = '集計中';
    }
    // 解説の表示フラグ
    $show_explanation = false;
    // 結果判定
    $feedback = '';
    // ブックマーク済みフラグ
    $is_bookmarked = (!$is_guest) ? isBookmarked($user_id, (int)$question_id) : false;

    if (!$is_guest){
        $userData = getUserInfo($user_id);
        if ($userData) {
            $user_name  = $userData['user_name'];
            $created_at = $userData['created_at'];
            $update_at  = $userData['update_at'];
            $is_admin   = $userData['is_admin'];
        } else {
            // ユーザーが見つからなかった場合の予備処理
            $user_name = "ログインユーザー";
            $is_admin = 0;
        }
    } else {
        $user_name  = "ゲストユーザー";
        $created_at = 0;
        $update_at  = 0;
        $is_admin   = 0;
    }

//**************************************************
// 回答送信処理
//**************************************************
    // 正解/不正解の結果表示＋解説表示
    if (isset($_POST['selected_option'])) {
        $selected_option = (int)$_POST['selected_option'];
        if ($selected_option === (int)$questions["correct_answer"]) {
            $feedback = "〇 正解！関連するリンクは下へ";
        } else {
            $feedback = "✕ 不正解...解説は下へ";
        }
        $show_explanation = true;

        $is_correct = ($selected_option === (int)$questions["correct_answer"]);
        $q_id = (int)$_POST['question_id'];

        if (!$is_guest) {
            updateQuestionStatus($user_id, $q_id, $is_correct);
            insertFirstAnswer($user_id, $q_id, $is_correct);
        }
    }

//**************************************************
// ブックマーク切替処理
//**************************************************
    // ブックマークの登録／解除（切替後の状態を $is_bookmarked に反映）
    if (isset($_POST['toggle_bookmark']) && !$is_guest) {
        $bookmark_question_id = (int)$_POST['question_id'];
        if (isBookmarked($user_id, $bookmark_question_id)) {
            deleteBookmark($user_id, $bookmark_question_id);
            $is_bookmarked = false;
        } else {
            insertBookmark($user_id, $bookmark_question_id);
            $is_bookmarked = true;
        }
    }

//**************************************************
// ファイルパスを生成
//**************************************************
    // フォルダー名
    $chapter_fname = $chapter_names["folder_name"];
    $section_fname = $section_names["folder_name"];
    $question_fname = sprintf("%05d", $question_id);

    // 問題画像
    $questionPath = "../images/".$chapter_fname."/".$section_fname."/".$question_fname."/q.png";
    $qPathmtime = filemtime($questionPath);
    $questionPath = "".$questionPath . "?v=" . $qPathmtime;

    // 選択肢画像
    // --- 選択肢の配列作成 ---
    $options = [];
    for($i=1; $i<=$questions["options"]; $i++){
        $path = "../images/".$chapter_fname."/".$section_fname."/".$question_fname."/opt".$i.".png";
        $mtime = filemtime($path);
        $options[$i] = [
            'id'   => $i,
            'path' => $path . "?v=" . $mtime
        ];
    }
    // --- 並び順の決定ロジック ---
    if (isset($_POST['option_order'])) {
        // 回答送信後：送られてきた順番（カンマ区切りの文字列）を配列に戻す
        $order = explode(',', $_POST['option_order']);
        $shuffledOptions = [];
        foreach ($order as $option_id) {
            $shuffledOptions[] = $options[$option_id];
        }
    } else {
        // 初回表示時：シャッフルして順番を確定させる
        $shuffledOptions = $options;
        shuffle($shuffledOptions);
    }

    // 解説画像
    $explanationPath = [];
    for($j=1; $j<=count($explanation_ids); $j++){
        $explanationPath[$j] = "../images/".$chapter_fname."/".$section_fname."/".$question_fname."/exp".$j.".png";
        $ePathmtime = filemtime($explanationPath[$j]);
        $explanationPath[$j] = "".$explanationPath[$j] . "?v=" . $ePathmtime;
    }
//**************************************************
// HTMLを出力
//**************************************************
    require_once('../view/question.php');

?>