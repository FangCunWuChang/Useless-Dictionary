<?php
require 'EnglishHelper.php';

$englishHelper = new EnglishHelper();

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'] ?? '';
    
        switch ($action) {
            case 'addWord':
                $word = $_POST['word'];
                $translation = $_POST['translation'];
                $difficulty = $_POST['difficulty'];
                if ($englishHelper->addWord($word, $translation, $difficulty)) {
                    echo json_encode(['status' => true, 'message' => 'Word added! word: ' . $word . ' translation: ' . $translation . ' difficulty: ' . $difficulty]);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Can\'t add word! word: ' . $word . ' translation: ' . $translation . ' difficulty: ' . $difficulty]);
                }
                break;
            case 'modifyWord':
                $word_id = $_POST['id'];
                $word = $_POST['word'];
                $translation = $_POST['translation'];
                $difficulty = $_POST['difficulty'];
                if ($englishHelper->updateWord($word_id, $word, $translation, $difficulty)) {
                    echo json_encode(['status' => true, 'message' => 'Word changed! word id: ' . $word_id . ' word: ' . $word . ' translation: ' . $translation . ' difficulty: ' . $difficulty]);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Can\'t change word! word id: ' . $word_id . ' word: ' . $word . ' translation: ' . $translation . ' difficulty: ' . $difficulty]);
                }
                break;
            case 'deleteWord':
                $word_id = $_POST['id'];
                if ($englishHelper->deleteWord($word_id)) {
                    echo json_encode(['status' => true, 'message' => 'Word deleted! word id: ' . $word_id]);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Can\'t delete word! word id: ' . $word_id]);
                }
                break;
            case 'addSentence':
                $word_id = $_POST['word_id'];
                $sentence = $_POST['sentence'];
                if ($englishHelper->addSentence($word_id, $sentence)) {
                    echo json_encode(['status' => true, 'message' => 'Sentence added! word id: ' . $word_id . ' sentence: ' . $sentence]);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Can\'t add sentence! word id: ' . $word_id . ' sentence: ' . $sentence]);
                }
                break;
            case 'modifySentence':
                $sentence_id = $_POST['id'];
                $sentence = $_POST['sentence'];
                if ($englishHelper->updateSentence($sentence_id, $sentence)) {
                    echo json_encode(['status' => true, 'message' => 'Sentence changed! sentence id: ' . $sentence_id . ' sentence: ' . $sentence]);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Can\'t change sentence! sentence id: ' . $sentence_id . ' sentence: ' . $sentence]);
                }
                break;
            case 'deleteSentence':
                $sentence_id = $_POST['id'];
                if ($englishHelper->deleteSentence($sentence_id)) {
                    echo json_encode(['status' => true, 'message' => 'Sentence deleted! sentence id: ' . $sentence_id]);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Can\'t delete sentence! sentence id: ' . $sentence_id]);
                }
                break;
            case 'generateTest':
                $difficulty = $_POST['difficulty'];
                $test = $englishHelper->generateTest($difficulty);
                echo json_encode(['status' => true, 'data' => $test]);
                break;
            case 'backupData':
                $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.db';
                if (copy('english_helper.db', $backup_file)) {
                    echo json_encode(['status' => true, 'message' => 'File backuped! file: ' . $backup_file]);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Can\'t backup file! file: ' . $backup_file]);
                }
                break;
            case 'restoreData':
                if ($_FILES['restore_file']['error'] == UPLOAD_ERR_OK) {
                    if (is_uploaded_file($_FILES['restore_file']['tmp_name'])) {
                        $restore_file = $_FILES['restore_file']['tmp_name'];
                        if (copy($restore_file, 'english_helper.db')) {
                            echo json_encode(['status' => true, 'message' => 'File restored! file: ' . $_FILES['restore_file']['name']]);
                        } else {
                            echo json_encode(['status' => false, 'message' => 'Can\'t copy file!']);
                        }
                    } else {
                        echo json_encode(['status' => false, 'message' => 'File temp not exists!']);
                    }
                } else {
                    echo json_encode(['status' => false, 'message' => 'File upload error: '. $_FILES['restore_file']['error']]);
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
                echo json_encode(['status' => true, 'data' => $words]);
                break;
            case 'getTranslation':
                $search_text = $_GET['text'];
                $translations = $englishHelper->getTranslation($search_text);
                echo json_encode(['status' => true, 'data' => $translations]);
                break;
        }
    }
}
catch (Exception $e) {
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
?>
