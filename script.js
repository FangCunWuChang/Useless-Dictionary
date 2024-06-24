document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });

    document.getElementById('word-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'addWord');
        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => document.getElementById('word-result').innerText = JSON.stringify(data, null, 2));
    });

    document.getElementById('update-word-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'updateWord');
        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => document.getElementById('word-result').innerText = JSON.stringify(data, null, 2));
    });

    document.getElementById('delete-word-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'deleteWord');
        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => document.getElementById('word-result').innerText = JSON.stringify(data, null, 2));
    });

    document.getElementById('sentence-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'addSentence');
        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => document.getElementById('sentence-result').innerText = JSON.stringify(data, null, 2));
    });

    document.getElementById('update-sentence-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'updateSentence');
        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => document.getElementById('sentence-result').innerText = JSON.stringify(data, null, 2));
    });

    document.getElementById('delete-sentence-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'deleteSentence');
        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => document.getElementById('sentence-result').innerText = JSON.stringify(data, null, 2));
    });

    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('backend.php?action=searchWord&search_word=' + formData.get('search_word'))
        .then(response => response.json())
        .then(data => document.getElementById('search-result').innerText = JSON.stringify(data, null, 2));
    });

    document.getElementById('translate-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('backend.php?action=searchText&search_text=' + formData.get('translate_text'))
        .then(response => response.json())
        .then(data => document.getElementById('translate-result').innerText = data);
    });

    document.getElementById('test-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'generateTest');
        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => document.getElementById('test-result').innerText = JSON.stringify(data, null, 2));
    });

    document.getElementById('backup-button').addEventListener('click', function () {
        fetch('backend.php', {
            method: 'POST',
            body: new URLSearchParams('action=backupData')
        })
        .then(response => response.text())
        .then(data => document.getElementById('backup-restore-result').innerText = data);
    });

    document.getElementById('restore-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'restoreData');
        fetch('backend.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => document.getElementById('backup-restore-result').innerText = data);
    });
});
