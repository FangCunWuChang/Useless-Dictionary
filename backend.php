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
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
            break;
        case 'modifyWord':
            $word_id = $_POST['id'];
            $word = $_POST['word'];
            $translation = $_POST['translation'];
            $difficulty = $_POST['difficulty'];
            if ($englishHelper->updateWord($word_id, $word, $translation, $difficulty)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
            break;
        case 'deleteWord':
            $word_id = $_POST['id'];
            if ($englishHelper->deleteWord($word_id)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
            break;
        case 'addSentence':
            $word_id = $_POST['word_id'];
            $sentence = $_POST['sentence'];
            if ($englishHelper->addSentence($word_id, $sentence)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
            break;
        case 'modifySentence':
            $sentence_id = $_POST['id'];
            $sentence = $_POST['sentence'];
            if ($englishHelper->updateSentence($sentence_id, $sentence)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
            break;
        case 'deleteSentence':
            $sentence_id = $_POST['id'];
            if ($englishHelper->deleteSentence($sentence_id)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
            break;
        case 'generateTest':
            $difficulty = $_POST['difficulty'];
            $test = $englishHelper->generateTest($difficulty);
            echo json_encode($test);
            break;
        case 'backupData':
            $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.db';
            if (copy('english_helper.db', $backup_file)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
            break;
        case 'restoreData':
            if ($_FILES['restore_file']['error'] == UPLOAD_ERR_OK) {
                if (is_uploaded_file($_FILES['restore_file']['tmp_name'])) {
                    $restore_file = $_FILES['restore_file']['tmp_name'];
                    if (copy($restore_file, 'english_helper.db')) {
                        echo json_encode(true);
                    } else {
                        echo json_encode(false);
                    }
                } else {
                    echo json_encode(false);
                }
            } else {
                echo json_encode(false);
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
