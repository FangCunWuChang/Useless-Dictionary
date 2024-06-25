<?php
class EnglishHelper {
    private $pdo;

    public function __construct($dbFile = 'english_helper.db') {
        $this->pdo = new PDO('sqlite:' . $dbFile);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS `words` (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            word TEXT NOT NULL,
            translation TEXT,
            difficulty INTEGER)");

        $this->pdo->exec(
            "CREATE TABLE IF NOT EXISTS `sentences` (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            word_id INTEGER,
            sentence TEXT,
            FOREIGN KEY(word_id) REFERENCES words(id))");

        $this->pdo->exec(
            "CREATE VIEW IF NOT EXISTS `get_word_view` AS
            SELECT words.id, words.word, words.translation, words.difficulty, sentences.id AS sid, sentences.sentence 
            FROM words LEFT JOIN sentences ON words.id = sentences.word_id");
    }

    public function addWord($word, $translation, $difficulty) {
        $stmt = $this->pdo->prepare("INSERT INTO words (word, translation, difficulty) VALUES (:word, :translation, :difficulty)");
        return $stmt->execute([':word' => $word, ':translation' => $translation, ':difficulty' => $difficulty]);
    }

    public function updateWord($id, $word, $translation, $difficulty) {
        $stmt = $this->pdo->prepare("UPDATE words SET word = :word, translation = :translation, difficulty = :difficulty WHERE id = :id");
        return $stmt->execute([':word' => $word, ':translation' => $translation, ':difficulty' => $difficulty, ':id' => $id]);
    }

    public function deleteWord($id) {
        $stmt = $this->pdo->prepare("DELETE FROM words WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function addSentence($wordId, $sentence) {
        $stmt = $this->pdo->prepare("INSERT INTO sentences (word_id, sentence) VALUES (:word_id, :sentence)");
        return $stmt->execute([':word_id' => $wordId, ':sentence' => $sentence]);
    }

    public function updateSentence($id, $sentence) {
        $stmt = $this->pdo->prepare("UPDATE sentences SET sentence = :sentence WHERE id = :id");
        return $stmt->execute([':sentence' => $sentence, ':id' => $id]);
    }

    public function deleteSentence($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sentences WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getWord($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM get_word_view WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchWord($word) {
        $stmt = $this->pdo->prepare("SELECT * FROM get_word_view WHERE word LIKE :word");
        $stmt->execute([':word' => '%' . $word . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSentences($wordId) {
        $stmt = $this->pdo->prepare("SELECT * FROM sentences WHERE word_id = :word_id");
        $stmt->execute([':word_id' => $wordId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateTest($difficulty, $limit = 10) {
        $stmt = $this->pdo->prepare("SELECT * FROM words WHERE difficulty = :difficulty ORDER BY RANDOM() LIMIT :limit");
        $stmt->bindParam(':difficulty', $difficulty, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTranslation($text) {
        return $this->mockTranslate($text);
    }

    private function mockTranslate($text) {
        return "Translated: " . $text;
    }

    public function backup($backupFile) {
        return copy('english_helper.db', $backupFile);
    }

    public function restore($backupFile) {
        return copy($backupFile, 'english_helper.db');
    }
}
?>
