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

    function sendRequest(url, formData) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(formData)
        }).then(response => response.json());
    }

    function showAlert(title, text, success) {
        Swal.fire(title, text, success ? 'success' : 'error');
    }

    function showResultAlert(title, success) {
        showAlert(title, title + (success ? '成功' : '失败') + '！', success);
    }

    function displaySearchResults(data) {
        const resultsDiv = document.getElementById('search-result');
        resultsDiv.innerHTML = '';
        if (data.length > 0) {
            const table = document.createElement('table');
            const header = table.createTHead();
            const headerRow = header.insertRow(0);
            ['ID', '单词', '翻译', '难度', 'SID', '例句'].forEach(text => {
                const cell = document.createElement('th');
                cell.innerText = text;
                headerRow.appendChild(cell);
            });
    
            const body = table.createTBody();
            data.forEach(item => {
                const row = body.insertRow();
                [item.id, item.word, item.translation, item.difficulty, item.sid, item.sentence].forEach(text => {
                    const cell = row.insertCell();
                    cell.innerText = text;
                });
            });
            resultsDiv.appendChild(table);
        } else {
            Swal.fire('搜索单词', '未找到结果', 'info');
        }
    }

    function displayTestResults(data) {
        const resultsDiv = document.getElementById('test-result');
        resultsDiv.innerHTML = '';
        if (data.length > 0) {
            const table = document.createElement('table');
            const header = table.createTHead();
            const headerRow = header.insertRow(0);
            ['ID', '单词', '翻译', '难度'].forEach(text => {
                const cell = document.createElement('th');
                cell.innerText = text;
                headerRow.appendChild(cell);
            });
    
            const body = table.createTBody();
            data.forEach(item => {
                const row = body.insertRow();
                [item.id, item.word, item.translation, item.difficulty].forEach(text => {
                    const cell = row.insertCell();
                    cell.innerText = text;
                });
            });
            resultsDiv.appendChild(table);
        } else {
            Swal.fire('生成测试', '未找到结果', 'info');
        }
    }

    document.getElementById('word-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'addWord');
        sendRequest('backend.php', formData)
            .then(data => showResultAlert('添加单词', data));
    });

    document.getElementById('update-word-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'updateWord');
        sendRequest('backend.php', formData)
            .then(data => showResultAlert('修改单词', data));
    });

    document.getElementById('delete-word-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'deleteWord');
        sendRequest('backend.php', formData)
            .then(data => showResultAlert('删除单词', data));
    });

    document.getElementById('sentence-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'addSentence');
        sendRequest('backend.php', formData)
            .then(data => showResultAlert('添加例句', data));
    });

    document.getElementById('update-sentence-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'updateSentence');
        sendRequest('backend.php', formData)
            .then(data => showResultAlert('修改例句', data));
    });

    document.getElementById('delete-sentence-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'deleteSentence');
        sendRequest('backend.php', formData)
            .then(data => showResultAlert('删除例句', data));
    });

    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('backend.php?action=searchWord&search_word=' + formData.get('search_word'))
            .then(response => response.json())
            .then(data => displaySearchResults(data));
    });

    document.getElementById('translate-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('backend.php?action=getTranslation&text=' + formData.get('translate_text'))
            .then(response => response.json())
            .then(data => document.getElementById('translate-result').innerText = data);
    });

    document.getElementById('test-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'generateTest');
        sendRequest('backend.php', formData)
            .then(data => displayTestResults(data));
    });

    document.getElementById('backup-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'backupData');
        sendRequest('backend.php', formData)
          .then(data => showResultAlert('备份', data));
    });

    document.getElementById('restore-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'restoreData');
        sendRequest('backend.php', formData)
          .then(data => showResultAlert('还原', data));
    });
});
