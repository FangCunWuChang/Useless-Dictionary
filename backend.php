<?php
require 'EnglishHelper.php';

$englishHelper = new EnglishHelper();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'addWord':
            $word = $_POST['word'];
            $translation = $_POST['translation'];
            $difficulty = $_POST['difficulty'];
            if ($englishHelper->addWord($word, $translation, $difficulty)) {
                echo "单词添加成功";
            } else {
                echo "单词添加失败";
            }
            break;
        case 'modifyWord':
            $word_id = $_POST['id'];
            $word = $_POST['word'];
            $translation = $_POST['translation'];
            $difficulty = $_POST['difficulty'];
            if ($englishHelper->updateWord($word_id, $word, $translation, $difficulty)) {
                echo "单词修改成功";
            } else {
                echo "单词修改失败";
            }
            break;
        case 'deleteWord':
            $word_id = $_POST['id'];
            if ($englishHelper->deleteWord($word_id)) {
                echo "单词删除成功";
            } else {
                echo "单词删除失败";
            }
            break;
        case 'addSentence':
            $word_id = $_POST['word_id'];
            $sentence = $_POST['sentence'];
            if ($englishHelper->addSentence($word_id, $sentence)) {
                echo "例句添加成功";
            } else {
                echo "例句添加失败";
            }
            break;
        case 'modifySentence':
            $sentence_id = $_POST['id'];
            $sentence = $_POST['sentence'];
            if ($englishHelper->updateSentence($sentence_id, $sentence)) {
                echo "例句修改成功";
            } else {
                echo "例句修改失败";
            }
            break;
        case 'deleteSentence':
            $sentence_id = $_POST['id'];
            if ($englishHelper->deleteSentence($sentence_id)) {
                echo "例句删除成功";
            } else {
                echo "例句删除失败";
            }
            break;
        case 'generateTest':
            $difficulty = $_POST['difficulty'];
            $test = $englishHelper->generateTest($difficulty);
            echo json_encode($test);
            break;
        case 'backupData':
            $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.db';
            if ($englishHelper->backup($backup_file)) {
                echo "数据备份成功";
            } else {
                echo "数据备份失败";
            }
            break;
        case 'restoreData':
            if ($_FILES['restore_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['restore_file']['tmp_name'])) {
                $restore_file = $_FILES['restore_file']['tmp_name'];
                if ($englishHelper->restore($restore_file)) {
                    echo "数据恢复成功";
                } else {
                    echo "数据恢复失败";
                }
            } else {
                echo "数据恢复失败";
            }
            break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'searchWord':
            $search_word = $_GET['search_word'];
            $words = $englishHelper->searchWord($search_word);
            echo json_encode($words);
            break;
        case 'getTranslation':
            $search_text = $_GET['text'];
            $translations = $englishHelper->getTranslation($search_text);
            echo json_encode($translations);
            break;
    }
}
?>
