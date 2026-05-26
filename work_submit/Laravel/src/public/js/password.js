// login.js の中身
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    // 要素が正しく存在する場合のみイベントを設定する（エラー防止）
    if (passwordInput && passwordField && toggleIcon) {
        passwordInput.addEventListener('click', function () {
            // inputのtype属性がpasswordならtextに、textならpasswordに切り替える
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // アイコンの形（目の斜線あり・なし）を切り替える
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    }
});
// --- 2つ目（確認用）のパスワード用 【専用のコードを追加】 ---
const confirmField = document.getElementById('confirmPassword');
const confirmButton = document.getElementById('confirmToggleBtn');
const confirmIcon = document.getElementById('confirmToggleIcon'); // アイコンにも別のIDを振る

confirmButton.addEventListener('click', function () {
    if (confirmField.type === 'password') {
        confirmField.type = 'text';
        confirmIcon.className = 'bi bi-eye';
    } else {
        confirmField.type = 'password';
        confirmIcon.className = 'bi bi-eye-slash';
    }
});