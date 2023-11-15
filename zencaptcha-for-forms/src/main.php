<?php
class ZENCAPTCHA_MAIN
{
    public static $instance;

    public static $version=ZENCAPTCHA_VERSION;
    public static $zencaptcha_sitekey = "zencaptcha_sitekey";
    public static $zencaptcha_secret_key = "zencaptcha_secret_key";
    public static $zencaptcha_widget_lang = "zencaptcha_widget_lang";
    public static $skipcaptcha = false;


    public static function translate_zen($key)
    {
        $preferred_lang_code = 'en'; //move this up (?)
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $user_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $preferred_lang = strtok($user_langs[0], ';');
            $preferred_lang_code = substr($preferred_lang, 0, 2);
        }

        $translations = [
            'Use a valid email address.' => [
                'en' => 'Use a valid email address.',
                'de' => 'Verwenden Sie eine gültige E-Mail-Adresse.',
                'fr' => 'Utilisez une adresse e-mail valide.',
                'bg' => 'Използвайте валиден имейл адрес.',
                'ca' => 'Utilitzeu una adreça de correu electrònic vàlida.',
                'cs' => 'Použijte platnou e-mailovou adresu.',
                'da' => 'Brug en gyldig e-mailadresse.',
                'el' => 'Χρησιμοποιήστε μια έγκυρη διεύθυνση email.',
                'et' => 'Kasutage kehtivat e-posti aadressi.',
                'es' => 'Utilice una dirección de correo electrónico válida.',
                'fi' => 'KÃ¤ytÃ¤ kelvollista sÃ¤hkÃ¶postiosoitetta.',
                'hr' => 'Koristite valjanu adresu e-pošte.',
                'hu' => 'Használjon érvényes e-mail címet.',
                'it' => 'Usa un indirizzo email valido.',
                'ja' => '有効なメールアドレスを使用してください。',
                'lt' => 'Naudokite galiojantį el. pašto adresą.',
                'lv' => 'Izmantojiet derīgu e-pasta adresi.',
                'nl' => 'Gebruik een geldig e-mailadres.',
                'no' => 'Bruk en gyldig e-postadresse.',
                'pl' => 'Użyj prawidłowego adresu e-mail.',
                'pt' => 'Utilize um endereço de e-mail válido.',
                'ro' => 'Utilizați o adresă de email validă.',
                'ru' => 'Используйте действительный адрес электронной почты.',
                'sk' => 'Použite platnú e-mailovú adresu.',
                'sl' => 'Uporabite veljaven e-poštni naslov.',
                'sr' => 'Koristite ispravnu adresu e-pošte.',
                'sv' => 'Använd en giltig e-postadress.',
                'uk' => 'Використовуйте дійсну адресу електронної пошти.',
                'vi' => 'Sử dụng một địa chỉ email hợp lệ.',
                'zh' => '使用有效的电子邮件地址.'
            ],

            '<strong>Error.</strong> Your login data contains errors.' => [
                'en' => '<strong>Error.</strong> Your login data contains errors.',
                'de' => '<strong>Fehler.</strong> Ihre Anmeldedaten enthalten Fehler.',
                'es' => '<strong>Error.</strong> Tus datos de inicio de sesión contienen errores.',
                'fr' => '<strong>Erreur.</strong> Vos données de connexion contiennent des erreurs.',
                'it' => '<strong>Errore.</strong> I tuoi dati di accesso contengono errori.',
                'nl' => '<strong>Fout.</strong> Uw inloggegevens bevatten fouten.',
                'pl' => '<strong>Błąd.</strong> Twoje dane logowania zawierają błędy.',
                'pt' => '<strong>Erro.</strong> Os seus dados de login contêm erros.',
                'ro' => '<strong>Eroare.</strong> Datele tale de autentificare conțin erori.',
                'sv' => '<strong>Fel.</strong> Dina inloggningsuppgifter innehåller fel.',
                'bg' => '<strong>Грешка.</strong> Вашите данни за вход съдържат грешки.',
                'ca' => '<strong>Error.</strong> Les vostres dades d\'inici de sessió contenen errors.',
                'cs' => '<strong>Chyba.</strong> Vaše přihlašovací údaje obsahují chyby.',
                'da' => '<strong>Fejl.</strong> Dine loginoplysninger indeholder fejl.',
                'el' => '<strong>Σφάλμα.</strong> Τα στοιχεία σύνδεσής σας περιέχουν σφάλματα.',
                'et' => '<strong>Viga.</strong> Teie sisselogimisandmed sisaldavad vigu.',
                'hr' => '<strong>Greška.</strong> Vaši podaci za prijavu sadrže pogreške.',
                'hu' => '<strong>Hiba.</strong> Bejelentkezési adatai hibákat tartalmaznak.',
                'ja' => '<strong>エラー。</strong> ログインデータにエラーがあります。',
                'lt' => '<strong>Klaida.</strong> Jūsų prisijungimo duomenyse yra klaidų.',
                'lv' => '<strong>Kļūda.</strong> Jūsu pieteikšanās dati satur kļūdas.',
                'no' => '<strong>Feil.</strong> Innloggingsdataene dine inneholder feil.',
                'ru' => '<strong>Ошибка.</strong> В ваших данных для входа есть ошибки.',
                'sk' => '<strong>Chyba.</strong> Vaše prihlasovacie údaje obsahujú chyby.',
                'sl' => '<strong>Napaka.</strong> Vaši podatki za prijavo vsebujejo napake.',
                'sr' => '<strong>Грешка.</strong> Ваши подаци за пријаву садрже грешке.',
                'uk' => '<strong>Помилка.</strong> Ваші дані для входу містять помилки.',
                'vi' => '<strong>Lỗi.</strong> Dữ liệu đăng nhập của bạn chứa lỗi.',
                'zh' => '<strong>错误。</strong> 您的登录数据存在错误。'
            ],
            
            'Please confirm that you are human. <strong>Try again.</strong>' => [
                'en' => 'Please confirm that you are human. <strong>Try again.</strong>',
                'de' => 'Bitte bestätigen Sie, dass Sie ein Mensch sind. <strong>Versuchen Sie es erneut.</strong>',
                'es' => 'Por favor, confirma que eres humano. <strong>Inténtalo de nuevo.</strong>',
                'fr' => 'Veuillez confirmer que vous êtes humain. <strong>Essayez à nouveau.</strong>',
                'it' => 'Si prega di confermare di essere umano. <strong>Riprova.</strong>',
                'nl' => 'Bevestig alstublieft dat u een mens bent. <strong>Probeer opnieuw.</strong>',
                'pl' => 'Proszę potwierdź, że jesteś człowiekiem. <strong>Spróbuj ponownie.</strong>',
                'pt' => 'Por favor, confirme que é humano. <strong>Tente novamente.</strong>',
                'ro' => 'Vă rugăm să confirmați că sunteți uman. <strong>Încercați din nou.</strong>',
                'sv' => 'Bekräfta att du är mänsklig. <strong>Försök igen.</strong>',
                'bg' => 'Моля, потвърдете, че сте човек. <strong>Опитайте отново.</strong>',
                'ca' => 'Si us plau, confirmeu que sou humà. <strong>Proveu-ho de nou.</strong>',
                'cs' => 'Prosím, potvrďte, že jste člověk. <strong>Zkuste to znovu.</strong>',
                'da' => 'Bekræft venligst, at du er et menneske. <strong>Prøv igen.</strong>',
                'el' => 'Παρακαλώ επιβεβαιώστε ότι είστε άνθρωπος. <strong>Δοκιμάστε ξανά.</strong>',
                'et' => 'Palun kinnitage, et olete inimene. <strong>Proovige uuesti.</strong>',
                'hr' => 'Molimo potvrdite da ste čovjek. <strong>Pokušajte ponovno.</strong>',
                'hu' => 'Kérjük, erősítse meg, hogy ember vagy. <strong>Próbálja újra.</strong>',
                'ja' => '人間であることを確認してください。 <strong>もう一度やり直してください。</strong>',
                'lt' => 'Prašome patvirtinti, kad esate žmogus. <strong>Bandykite dar kartą.</strong>',
                'lv' => 'Lūdzu, apstipriniet, ka esat cilvēks. <strong>Mēģiniet vēlreiz.</strong>',
                'no' => 'Vennligst bekreft at du er et menneske. <strong>Prøv igjen.</strong>',
                'ru' => 'Пожалуйста, подтвердите, что вы человек. <strong>Попробуйте еще раз.</strong>',
                'sk' => 'Prosím, potvrďte, že ste človek. <strong>Skúste to znova.</strong>',
                'sl' => 'Prosim, potrdite, da ste človek. <strong>Poskusite znova.</strong>',
                'sr' => 'Молимо потврдите да сте човек. <strong>Покушајте поново.</strong>',
                'uk' => 'Будь ласка, підтвердіть, що ви людина. <strong>Спробуйте ще раз.</strong>',
                'vi' => 'Vui lòng xác nhận rằng bạn là người. <strong>Thử lại.</strong>',
                'zh' => '请确认您是人类。 <strong>再试一次。</strong>'
            ],
            
            'Please confirm that you are human. Try again.' => [
                'en' => 'Please confirm that you are human. Try again.',
                'de' => 'Bitte bestätigen Sie, dass Sie ein Mensch sind. Versuchen Sie es erneut.',
                'es' => 'Por favor, confirma que eres humano. Inténtalo de nuevo.',
                'fr' => 'Veuillez confirmer que vous êtes humain. Essayez à nouveau.',
                'it' => 'Si prega di confermare di essere umano. Riprova.',
                'nl' => 'Bevestig alstublieft dat u een mens bent. Probeer opnieuw.',
                'pl' => 'Proszę potwierdź, że jesteś człowiekiem. Spróbuj ponownie.',
                'pt' => 'Por favor, confirme que é humano. Tente novamente.',
                'ro' => 'Vă rugăm să confirmați că sunteți uman. Încercați din nou.',
                'sv' => 'Bekräfta att du är mänsklig. Försök igen.',
                'bg' => 'Моля, потвърдете, че сте човек. Опитайте отново.',
                'ca' => 'Si us plau, confirmeu que sou humà. Proveu-ho de nou.',
                'cs' => 'Prosím, potvrďte, že jste člověk. Zkuste to znovu.',
                'da' => 'Bekræft venligst, at du er et menneske. Prøv igen.',
                'el' => 'Παρακαλώ επιβεβαιώστε ότι είστε άνθρωπος. Δοκιμάστε ξανά.',
                'et' => 'Palun kinnitage, et olete inimene. Proovige uuesti.',
                'hr' => 'Molimo potvrdite da ste čovjek. Pokušajte ponovno.',
                'hu' => 'Kérjük, erősítse meg, hogy ember vagy. Próbálja újra.',
                'ja' => '人間であることを確認してください。 もう一度やり直してください。',
                'lt' => 'Prašome patvirtinti, kad esate žmogus. Bandykite dar kartą.',
                'lv' => 'Lūdzu, apstipriniet, ka esat cilvēks. Mēģiniet vēlreiz.',
                'no' => 'Vennligst bekreft at du er et menneske. Prøv igjen.',
                'ru' => 'Пожалуйста, подтвердите, что вы человек. Попробуйте еще раз.',
                'sk' => 'Prosím, potvrďte, že ste človek. Skúste to znova.',
                'sl' => 'Prosim, potrdite, da ste človek. Poskusite znova.',
                'sr' => 'Молимо потврдите да сте човек. Покушајте поново.',
                'uk' => 'Будь ласка, підтвердіть, що ви людина. Спробуйте ще раз.',
                'vi' => 'Vui lòng xác nhận rằng bạn là người. Thử lại.',
                'zh' => '请确认您是人类。 再试一次。'
            ],
            
            'Robot check failed, please retry.' => [
                'en' => 'Robot check failed, please retry.',
                'de' => 'Roboterprüfung fehlgeschlagen, bitte versuchen Sie es erneut.',
                'es' => 'La verificación de robot falló, por favor inténtelo de nuevo.',
                'fr' => 'Vérification du robot échouée, veuillez réessayer.',
                'it' => 'Controllo del robot fallito, si prega di riprovare.',
                'nl' => 'Robotcontrole mislukt, probeer het opnieuw.',
                'pl' => 'Weryfikacja robota nie powiodła się, spróbuj ponownie.',
                'pt' => 'Verificação de robô falhou, por favor tente novamente.',
                'ro' => 'Verificarea robotului a eșuat, vă rugăm să încercați din nou.',
                'sv' => 'Robotkontrollen misslyckades, försök igen.',
                'bg' => 'Проверката на робота неуспешна, моля опитайте отново.',
                'ca' => 'La comprovació del robot ha fallat, si us plau torneu a intentar-ho.',
                'cs' => 'Kontrola robota selhala, zkuste to znovu.',
                'da' => 'Robotkontrollen mislykkedes, prøv venligst igen.',
                'el' => 'Αποτυχία ελέγχου ρομπότ, παρακαλώ δοκιμάστε ξανά.',
                'et' => 'Roboti kontroll nurjus, palun proovige uuesti.',
                'hr' => 'Provjera robota nije uspjela, molimo pokušajte ponovno.',
                'hu' => 'A robot ellenőrzése sikertelen, kérjük, próbálja újra.',
                'ja' => 'ロボットのチェックに失敗しました、もう一度やり直してください。',
                'lt' => 'Robotas nepavyko patikrinti, prašome bandyti dar kartą.',
                'lv' => 'Robota pārbaude neizdevās, lūdzu, mēģiniet vēlreiz.',
                'no' => 'Robotkontrollen mislyktes, prøv igjen.',
                'ru' => 'Проверка робота не удалась, пожалуйста, повторите попытку.',
                'sk' => 'Kontrola robota zlyhala, skúste to znovu.',
                'sl' => 'Preverjanje robota ni uspelo, poskusite znova.',
                'sr' => 'Provera robota nije uspela, molimo pokušajte ponovo.',
                'uk' => 'Перевірка робота не вдалася, будь ласка, спробуйте ще раз.',
                'vi' => 'Kiểm tra robot không thành công, vui lòng thử lại.',
                'zh' => '机器人检查失败，请重试。'
            ],
            
            'Confirm the robot check again.' => [
                'en' => 'Confirm the robot check again.',
                'de' => 'Bestätigen Sie die Roboterprüfung erneut.',
                'es' => 'Confirma la verificación del robot de nuevo.',
                'fr' => 'Confirmez la vérification du robot à nouveau.',
                'it' => 'Conferma il controllo del robot di nuovo.',
                'nl' => 'Bevestig de robotcontrole opnieuw.',
                'pl' => 'Potwierdź ponownie weryfikację robota.',
                'pt' => 'Confirme a verificação do robô novamente.',
                'ro' => 'Confirmați verificarea robotului din nou.',
                'sv' => 'Bekräfta robotkontrollen igen.',
                'bg' => 'Потвърдете проверката на робота отново.',
                'ca' => 'Confirmeu la comprovació del robot de nou.',
                'cs' => 'Potvrďte kontrolu robota znovu.',
                'da' => 'Bekræft robotkontrollen igen.',
                'el' => 'Επιβεβαιώστε ξανά τον έλεγχο του ρομπότ.',
                'et' => 'Kinnitage roboti kontroll uuesti.',
                'hr' => 'Ponovno potvrdite provjeru robota.',
                'hu' => 'Erősítse meg újra a robot ellenőrzését.',
                'ja' => 'もう一度ロボットチェックを確認してください。',
                'lt' => 'Pakartotinai patvirtinkite robotų patikrinimą.',
                'lv' => 'Vēlreiz apstipriniet robotu pārbaudi.',
                'no' => 'Bekreft robotkontrollen på nytt.',
                'ru' => 'Подтвердите проверку робота снова.',
                'sk' => 'Znova potvrďte kontrolu robota.',
                'sl' => 'Ponovno potrdite preverjanje robota.',
                'sr' => 'Ponovo potvrdite proveru robota.',
                'uk' => 'Підтвердіть перевірку робота знову.',
                'vi' => 'Xác nhận lại việc kiểm tra robot.',
                'zh' => '再次确认机器人检查。'
            ],
            
            '<b>Your access has been blocked.</b>' => [
                'en' => '<b>Your access has been blocked.</b>',
                'de' => '<b>Ihr Zugang wurde blockiert.</b>',
                'es' => '<b>Su acceso ha sido bloqueado.</b>',
                'fr' => '<b>Votre accès a été bloqué.</b>',
                'it' => '<b>Il tuo accesso è stato bloccato.</b>',
                'nl' => '<b>Uw toegang is geblokkeerd.</b>',
                'pl' => '<b>Twój dostęp został zablokowany.</b>',
                'pt' => '<b>Seu acesso foi bloqueado.</b>',
                'ro' => '<b>Accesul tău a fost blocat.</b>',
                'sv' => '<b>Din åtkomst har blockerats.</b>',
                'bg' => '<b>Вашият достъп е блокиран.</b>',
                'ca' => '<b>El vostre accés ha estat bloquejat.</b>',
                'cs' => '<b>Váš přístup byl zablokován.</b>',
                'da' => '<b>Din adgang er blevet blokeret.</b>',
                'el' => '<b>Ο προσωπικός σας κωδικός πρόσβασης έχει μπλοκαριστεί.</b>',
                'et' => '<b>Teie juurdepääs on blokeeritud.</b>',
                'hr' => '<b>Vaš pristup je blokiran.</b>',
                'hu' => '<b>A hozzáférése blokkolva lett.</b>',
                'ja' => '<b>アクセスがブロックされました。</b>',
                'lt' => '<b>Jūsų prieiga užblokuota.</b>',
                'lv' => '<b>Jums ir bloķēts piekļuve.</b>',
                'no' => '<b>Din tilgang er blokkert.</b>',
                'ru' => '<b>Ваш доступ заблокирован.</b>',
                'sk' => '<b>Váš prístup bol zablokovaný.</b>',
                'sl' => '<b>Vaš dostop je bil blokiran.</b>',
                'sr' => '<b>Ваш приступ је блокиран.</b>',
                'uk' => '<b>Ваш доступ заблоковано.</b>',
                'vi' => '<b>Quyền truy cập của bạn đã bị chặn.</b>',
                'zh' => '<b>您的访问已被阻止。</b>'
            ],
            
            'Your access has been blocked.' => [
                'en' => 'Your access has been blocked.',
                'de' => 'Ihr Zugang wurde blockiert.',
                'es' => 'Tu acceso ha sido bloqueado.',
                'fr' => 'Votre accès a été bloqué.',
                'it' => 'Il tuo accesso è stato bloccato.',
                'nl' => 'Uw toegang is geblokkeerd.',
                'pl' => 'Twój dostęp został zablokowany.',
                'pt' => 'Seu acesso foi bloqueado.',
                'ro' => 'Accesul tău a fost blocat.',
                'sv' => 'Din åtkomst har blockerats.',
                'bg' => 'Вашият достъп е блокиран.',
                'ca' => 'El vostre accés ha estat bloquejat.',
                'cs' => 'Váš přístup byl zablokován.',
                'da' => 'Din adgang er blevet blokeret.',
                'el' => 'Ο προσωπικός σας κωδικός πρόσβασης έχει μπλοκαριστεί.',
                'et' => 'Teie juurdepääs on blokeeritud.',
                'hr' => 'Vaš pristup je blokiran.',
                'hu' => 'A hozzáférése blokkolva lett.',
                'ja' => 'アクセスがブロックされました。',
                'lt' => 'Jūsų prieiga užblokuota.',
                'lv' => 'Jums ir bloķēts piekļuve.',
                'no' => 'Din tilgang er blokkert.',
                'ru' => 'Ваш доступ заблокирован.',
                'sk' => 'Váš prístup bol zablokovaný.',
                'sl' => 'Vaš dostop je bil blokiran.',
                'sr' => 'Ваш приступ је блокиран.',
                'uk' => 'Ваш доступ заблоковано.',
                'vi' => 'Quyền truy cập của bạn đã bị chặn.',
                'zh' => '您的访问已被阻止。'
            ],
            
            '<b>Error:</b> Please fill in the required fields.' => [
                'en' => '<b>Error:</b> Please fill in the required fields.',
                'de' => '<b>Fehler:</b> Bitte füllen Sie die erforderlichen Felder aus.',
                'es' => '<b>Error:</b> Por favor complete los campos obligatorios.',
                'fr' => '<b>Erreur :</b> Veuillez remplir les champs obligatoires.',
                'it' => '<b>Errore:</b> Si prega di compilare i campi obbligatori.',
                'nl' => '<b>Fout:</b> Vul de verplichte velden in.',
                'pl' => '<b>Błąd:</b> Proszę wypełnić wymagane pola.',
                'pt' => '<b>Erro:</b> Por favor, preencha os campos obrigatórios.',
                'ro' => '<b>Eroare:</b> Vă rugăm să completați câmpurile obligatorii.',
                'sv' => '<b>Fel:</b> Vänligen fyll i de obligatoriska fälten.',
                'bg' => '<b>Грешка:</b> Моля, попълнете задължителните полета.',
                'ca' => '<b>Error:</b> Si us plau, ompliu els camps obligatoris.',
                'cs' => '<b>Chyba:</b> Prosím, vyplňte povinná pole.',
                'da' => '<b>Fejl:</b> Udfyld venligst de påkrævede felter.',
                'el' => '<b>Σφάλμα:</b> Συμπληρώστε τα απαιτούμενα πεδία.',
                'et' => '<b>Viga:</b> Palun täitke nõutud väljad.',
                'hr' => '<b>Greška:</b> Ispunite obavezna polja.',
                'hu' => '<b>Hiba:</b> Kérjük, töltse ki a kötelező mezőket.',
                'ja' => '<b>エラー:</b> 必須フィールドに記入してください。',
                'lt' => '<b>Klaida:</b> Prašome užpildyti privalomus laukus.',
                'lv' => '<b>Kļūda:</b> Lūdzu, aizpildiet obligātos laukus.',
                'no' => '<b>Feil:</b> Vennligst fyll inn de påkrevde feltene.',
                'ru' => '<b>Ошибка:</b> Пожалуйста, заполните обязательные поля.',
                'sk' => '<b>Chyba:</b> Vyplňte prosím povinné pole.',
                'sl' => '<b>Napaka:</b> Izpolnite zahtevana polja.',
                'sr' => '<b>Грешка:</b> Молимо вас да попуните обавезна поља.',
                'uk' => '<b>Помилка:</b> Будь ласка, заповніть обов\'язкові поля.',
                'vi' => '<b>Lỗi:</b> Vui lòng điền vào các trường bắt buộc.',
                'zh' => '<b>错误：</b> 请填写必填字段。'
            ],

            'Error: Please fill in the required fields.' => [
                'en' => 'Error: Please fill in the required fields.',
                'de' => 'Fehler: Bitte füllen Sie die erforderlichen Felder aus.',
                'es' => 'Error: Por favor complete los campos obligatorios.',
                'fr' => 'Erreur : Veuillez remplir les champs obligatoires.',
                'it' => 'Errore: Si prega di compilare i campi obbligatori.',
                'nl' => 'Fout: Vul de verplichte velden in.',
                'pl' => 'Błąd: Proszę wypełnić wymagane pola.',
                'pt' => 'Erro: Por favor, preencha os campos obrigatórios.',
                'ro' => 'Eroare: Vă rugăm să completați câmpurile obligatorii.',
                'sv' => 'Fel: Vänligen fyll i de obligatoriska fälten.',
                'bg' => '<b>Грешка:</b> Моля, попълнете задължителните полета.',
                'ca' => '<b>Error:</b> Si us plau, ompliu els camps obligatoris.',
                'cs' => '<b>Chyba:</b> Prosím, vyplňte povinná pole.',
                'da' => '<b>Fejl:</b> Udfyld venligst de påkrævede felter.',
                'el' => '<b>Σφάλμα:</b> Συμπληρώστε τα απαιτούμενα πεδία.',
                'et' => '<b>Viga:</b> Palun täitke nõutud väljad.',
                'hr' => '<b>Greška:</b> Ispunite obavezna polja.',
                'hu' => '<b>Hiba:</b> Kérjük, töltse ki a kötelező mezőket.',
                'ja' => '<b>エラー:</b> 必須フィールドに記入してください。',
                'lt' => '<b>Klaida:</b> Prašome užpildyti privalomus laukus.',
                'lv' => '<b>Kļūda:</b> Lūdzu, aizpildiet obligātos laukus.',
                'no' => '<b>Feil:</b> Vennligst fyll inn de påkrevde feltene.',
                'ru' => '<b>Ошибка:</b> Пожалуйста, заполните обязательные поля.',
                'sk' => '<b>Chyba:</b> Vyplňte prosím povinné pole.',
                'sl' => '<b>Napaka:</b> Izpolnite zahtevana polja.',
                'sr' => '<b>Грешка:</b> Молимо вас да попуните обавезна поља.',
                'uk' => '<b>Помилка:</b> Будь ласка, заповніть обов\'язкові поля.',
                'vi' => '<b>Lỗi:</b> Vui lòng điền vào các trường bắt buộc.',
                'zh' => '<b>错误：</b> 请填写必填字段。'
            ],
        ];


        if (array_key_exists($key, $translations)) {
            $translation = $translations[$key];
            if (isset($translation[$preferred_lang_code])) {
                return $translation[$preferred_lang_code];
            } else {
                return $translation['en'];
            }
        } else {
            return $key;
        }


    }

    public function init()
    {
        ZENCAPTCHA_MAIN::$instance = $this;

        add_action('plugins_loaded', array($this, 'loadTextDomain'));

        register_activation_hook(ZENCAPTCHA_MAIN_FILE, array($this, 'activate_zencaptcha_plugin'));
        register_uninstall_hook(ZENCAPTCHA_MAIN_FILE, array($this, 'uninstall_zencaptcha_plugin'));
        register_deactivation_hook(ZENCAPTCHA_MAIN_FILE, array($this, 'deactivation_zencaptcha_plugin'));

        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_init', array($this, 'settings'));

        add_action( 'admin_notices', array($this, 'zencaptcha_admin_notice_no_keys'), 999 );

        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));

        add_filter('plugin_action_links_'.TEXT_DOMAIN.'/'.ZENCAPTCHA_MAIN_PLUGIN_PHP, array($this, 'zencaptcha_plugin_action_links'), 10, 2);

    }

    public static function zencaptcha_login_data_errors_Alert(){
        return self::translate_zen('<strong>Error.</strong> Your login data contains errors.');
    }

    public static function zencaptcha_failed_try_again_Alert() {
        return self::translate_zen('Please confirm that you are human. <strong>Try again.</strong>');
    }
    public static function zencaptcha_failed_try_again_Alert_no_html() {
        return self::translate_zen('Please confirm that you are human. Try again.');
    }

    public static function zencaptcha_check_failed_Alert() {
        return self::translate_zen('Robot check failed, please retry.');
    }
    public static function zencaptcha_check_failed_Alert_no_html() {
        return self::translate_zen('Robot check failed, please retry.');
    }

    public static function zencaptcha_check_again_Alert(){
        return self::translate_zen('Confirm the robot check again.');
    }
    public static function zencaptcha_check_again_Alert_no_html(){
        return self::translate_zen('Confirm the robot check again.');
    }
    
    public static function zencaptcha_use_valid_email_Alert() {
        return self::translate_zen('Use a valid email address.');
    }
    public static function zencaptcha_use_valid_email_Alert_no_html() {
        return self::translate_zen('Use a valid email address.');
    }

    public static function zencaptcha_no_access(){
        return self::translate_zen('<b>Your access has been blocked.</b>');
    }
    public static function zencaptcha_no_access_no_html(){
        return self::translate_zen('Your access has been blocked.');
    }

    public static function zencaptcha_fill_in_all_fields_Alert(){
        return self::translate_zen('<b>Error:</b> Please fill in the required fields.');
    }
    public static function zencaptcha_fill_in_all_fields_Alert_no_html(){
        return self::translate_zen('Error: Please fill in the required fields.');
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style('zencaptcha-admin-css', zencap_plugin_url('assets/css/admin-styles.css'), array(), ZENCAPTCHA_CSS_VERSION);
    }
    


    public function loadTextDomain()
    {
        load_plugin_textdomain(
			'zencaptcha-for-forms',
			false,
			dirname( plugin_basename( ZENCAPTCHA_FILE ) ) . '/languages/'
		);
    }

    public function set_country_blacklist_default(){
        $russia = "RU";
        $afghanistan = "AF";
        update_option('country_blacklist', $russia ."\n".$afghanistan, false);
    }

    public function set_email_blacklist_default(){
        $trashmail1 = "10minutemail.com";
        $trashmail2 = "trashmail.com";
        update_option('email_blacklist', $trashmail1 ."\n".$trashmail2, false);
    }

    public function activate_zencaptcha_plugin()
    {

        $sitekey = get_option(ZENCAPTCHA_MAIN::$zencaptcha_sitekey);
        $secretkey = get_option(ZENCAPTCHA_MAIN::$zencaptcha_secret_key);
        $zencaptcha_widget_lang = get_option(ZENCAPTCHA_MAIN::$zencaptcha_widget_lang);

        if (!$sitekey || !$this->isValidSiteKey($sitekey)) {
            update_option(ZENCAPTCHA_MAIN::$zencaptcha_sitekey, '');
        }

        if (!$secretkey || !$this->isValidSecretKey($secretkey)) {
            update_option(ZENCAPTCHA_MAIN::$zencaptcha_secret_key, '');
        }

        if (!$zencaptcha_widget_lang) {
            update_option(ZENCAPTCHA_MAIN::$zencaptcha_widget_lang, 'auto');
        }


        /*TODO custom email blacklist: if (!get_option('email_blacklist')) {
            ZENCAPTCHA_MAIN::$instance->set_email_blacklist_default();
        }*/

        if (!get_option('country_blacklist')) {
            ZENCAPTCHA_MAIN::$instance->set_country_blacklist_default();
        }

    }

    public static function deactivation_zencaptcha_plugin(){
        if (get_option(ZENCAPTCHA_MAIN::$zencaptcha_sitekey)) {
            //delete_option(ZENCAPTCHA_MAIN::$zencaptcha_sitekey);
        } 
    }

    public static function uninstall_zencaptcha_plugin()
    {
        delete_option(ZENCAPTCHA_MAIN::$zencaptcha_sitekey);
        delete_option(ZENCAPTCHA_MAIN::$zencaptcha_secret_key);
        delete_option(ZENCAPTCHA_MAIN::$zencaptcha_widget_lang);

        delete_option('zen-wp-login');
        delete_option('zen-wp-register');
        delete_option('zen-wp-resetpass');
        delete_option('zen-wp-comments-guests');
        delete_option('zen-wp-comments-loggedin');

        delete_option('zen-woo-login');
        delete_option('zen-woo-register');
        delete_option('zen-woo-resetpass');
        delete_option('zen-woo-checkout');

        delete_option('zen-elementor-activate');

        delete_option('zen-wpforms-activate');

        delete_option('zen-cf7-activate');
        delete_option('zen-gravity-activate');
        //delete_option('zen-ninja-activate');
        delete_option('zen-mailchimp-activate');

        delete_option('zen-divilogin-activate');
        delete_option('zen-divicomment-activate');
        delete_option('zen-diviemailoptin-activate');
        delete_option('zen-divicontact-activate');

        delete_option('zen-htmlforms-activate');

        delete_option('zen-avada-activate');
        delete_option('zen-forminator-activate');
        delete_option('zen-fluentform-activate');

        delete_option('zen-profilebuilderlogin-activate');
        delete_option('zen-profilebuilderregister-activate');
        delete_option('zen-profilebuilderresetpass-activate');

        delete_option('zen-ultimatememberlogin-activate');
        delete_option('zen-ultimatememberregister-activate');
        delete_option('zen-ultimatememberresetpass-activate');

        delete_option('zen-wp-verifyemail');
        delete_option('zen-woo-verifyemail');
        delete_option('zen-elementor-verifyemail');
        delete_option('zen-wpforms-verifyemail');
        delete_option('zen-cf7-verifyemail');
        delete_option('zen-gravity-verifyemail');
        //delete_option('zen-ninja-verifyemail');
        delete_option('zen-mailchimp-verifyemail');
        delete_option('zen-divi-verifyemail');
        delete_option('zen-avada-verifyemail');
        delete_option('zen-htmlforms-verifyemail');
        delete_option('zen-forminator-verifyemail');
        delete_option('zen-fluentform-verifyemail');
        delete_option('zen-profilebuilder-verifyemail');
        delete_option('zen-ultimatemember-verifyemail');

    }

    public function menu()
    {
        add_options_page(
            __('Zencaptcha Settings', 'zencaptcha-for-forms'),
            'Zencaptcha',
            'manage_options',
            'zencaptcha-admin',
            array($this, 'settingsPage')
        );
    }

    public function zencaptcha_plugin_action_links($links)
    {
        $url = esc_url( add_query_arg(
            'page',
            'zencaptcha-admin',
            get_admin_url() . 'options-general.php'
        ) );
        $settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
        
        array_push(
            $links,
            $settings_link
        );
        return $links;
    }

    public function settingsPage()
    {
        include plugin_dir_path(__FILE__) . '/settings.php';
    }

    public function settings()
    {
        register_setting('zencaptcha-settings-group', ZENCAPTCHA_MAIN::$zencaptcha_sitekey, array($this, 'validate_SiteKey'));
        register_setting('zencaptcha-settings-group', ZENCAPTCHA_MAIN::$zencaptcha_secret_key, array($this, 'validate_SecretKey'));
        register_setting('zencaptcha-settings-group', ZENCAPTCHA_MAIN::$zencaptcha_widget_lang);

        register_setting('zencaptcha-settings-group', 'zen-wp-login');
        register_setting('zencaptcha-settings-group', 'zen-wp-register');
        register_setting('zencaptcha-settings-group', 'zen-wp-resetpass');
        register_setting('zencaptcha-settings-group', 'zen-wp-comments-guests');
        register_setting('zencaptcha-settings-group', 'zen-wp-comments-loggedin');
        
        register_setting('zencaptcha-settings-group', 'zen-woo-login');
        register_setting('zencaptcha-settings-group', 'zen-woo-register');
        register_setting('zencaptcha-settings-group', 'zen-woo-resetpass');
        register_setting('zencaptcha-settings-group', 'zen-woo-checkout');

        register_setting('zencaptcha-settings-group', 'zen-elementor-activate');

        register_setting('zencaptcha-settings-group', 'zen-wpforms-activate');

        register_setting('zencaptcha-settings-group', 'zen-cf7-activate');
        register_setting('zencaptcha-settings-group', 'zen-gravity-activate');
        //register_setting('zencaptcha-settings-group', 'zen-ninja-activate');
        register_setting('zencaptcha-settings-group', 'zen-mailchimp-activate');
        register_setting('zencaptcha-settings-group', 'zen-divilogin-activate');
        register_setting('zencaptcha-settings-group', 'zen-divicontact-activate');
        register_setting('zencaptcha-settings-group', 'zen-divicomment-activate');
        register_setting('zencaptcha-settings-group', 'zen-diviemailoptin-activate');

        register_setting('zencaptcha-settings-group', 'zen-avada-activate');
        register_setting('zencaptcha-settings-group', 'zen-htmlforms-activate');
        register_setting('zencaptcha-settings-group', 'zen-forminator-activate');
        register_setting('zencaptcha-settings-group', 'zen-fluentform-activate');
        register_setting('zencaptcha-settings-group', 'zen-profilebuilderlogin-activate');
        register_setting('zencaptcha-settings-group', 'zen-profilebuilderregister-activate');
        register_setting('zencaptcha-settings-group', 'zen-profilebuilderresetpass-activate');
        register_setting('zencaptcha-settings-group', 'zen-ultimatememberlogin-activate');
        register_setting('zencaptcha-settings-group', 'zen-ultimatememberregister-activate');
        register_setting('zencaptcha-settings-group', 'zen-ultimatememberresetpass-activate');

        register_setting('zencaptcha-settings-group', 'zen-wp-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-woo-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-elementor-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-wpforms-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-cf7-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-gravity-verifyemail');
        //register_setting('zencaptcha-settings-group', 'zen-ninja-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-mailchimp-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-divi-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-avada-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-htmlforms-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-forminator-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-fluentform-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-profilebuilder-verifyemail');
        register_setting('zencaptcha-settings-group', 'zen-ultimatemember-verifyemail');

        register_setting('zencaptcha-settings-group', 'widgetstarts');

        //TODO: custom email blacklist register_setting('zencaptcha-settings-group', 'email_blacklist', array($this, 'cleanList'));
        register_setting('zencaptcha-settings-group', 'country_blacklist', array($this, 'cleanListUppercase'));

        if (get_option('widgetstarts') === false) {
            update_option('widgetstarts', 'none');
        }

        /*TODO: custom email blacklistif (get_option('email_blacklist') === false) {
            ZENCAPTCHA_MAIN::$instance->set_email_blacklist_default();
        }*/
        if (get_option('country_blacklist') === false) {
            ZENCAPTCHA_MAIN::$instance->set_country_blacklist_default();
        }
    }

    public function zencaptcha_admin_notice_no_keys() {
        if (!ZENCAPTCHA_MAIN::$instance->are_keys_set()) {

        $url = esc_url( add_query_arg(
            'page',
            'zencaptcha-admin',
            get_admin_url() . 'options-general.php'
        ) );

        ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <?php
                    printf(
                        __(
                            '<b>Zencaptcha is not configured yet! (Sitekey and secret key is missing)</b> Visit the <a href="%1$s">Zencaptcha WordPress settings page</a> and enter a valid Sitekey and Secret Key that you get from <a href="%2$s">Zencaptcha.com (Account)</a> to complete the setup.',
                             TEXT_DOMAIN
                        ),
                        $url,
                        esc_url(ZENCAPTCHA_REGISTRATION_URL)
                    );
                ?>
            </p>
        </div>
        <?php
        }
    }

    public function are_keys_set() {
        return !empty($this->get_site_key()) && !empty($this->get_secret_key());
    }

    public function get_site_key() {
        return trim(get_option(ZENCAPTCHA_MAIN::$zencaptcha_sitekey));
    }

    public function get_secret_key() {
        return trim(get_option(ZENCAPTCHA_MAIN::$zencaptcha_secret_key));
    }


    public function validate_SiteKey($sitekey)
    {
        if (!$sitekey || !$this->isValidSiteKey($sitekey)) {
            return '';
        }

        return $sitekey;
    }

    public function validate_SecretKey($secretkey)
    {
        if (!$secretkey || !$this->isValidSecretKey($secretkey)) {
            return '';
        }

        return $secretkey;
    }

    public function isValidSiteKey($sitekey)
    {
        return strlen($sitekey) >= 34 && strlen($sitekey) <= 38;
    }

    public function isValidSecretKey($secretkey)
    {
        return strlen($secretkey) >= 60 && strlen($secretkey) <= 80;
    }


    public function widget_lang() {
        $lang = get_option(ZENCAPTCHA_MAIN::$zencaptcha_widget_lang);
        $lang = empty($lang) ? "auto" : $lang;
        if ( $lang != "auto" ) {
            if ( ! array_key_exists($lang, ZENCAPTCHA_WIDGET_LANGUAGES) ) {
                $lang = "auto";
            }
        }
        return $lang;
    }


    public function cleanListUppercase($list)
    {
        $cleanList = array_unique(array_filter(array_map('trim', explode("\n", $list))));
        $cleanList = array_map('strtoupper', $cleanList);
        natcasesort($cleanList);
        return implode("\n", $cleanList);
    }

    public function cleanList($list)
    {
        $cleanList = array_unique(array_filter(array_map('trim', explode("\n", $list))));
        natcasesort($cleanList);
        return implode("\n", $cleanList);
    }

    protected function flatten($array)
    {
        $result = '';

        foreach ($array as $value) {
            if (is_array($value)) {
                $result .= $this->flatten($value);
            } elseif (is_scalar($value)) {
                $result .= $value;
            }
        }

        return $result;
    }

    public function shouldBeBlocked($domain)
    {
    }
}

$added_LostPass_filter=false;
if ( !defined(ZENCAPTCHA_MAIN::$instance)) {
    $zencaptchaPlugin = new ZENCAPTCHA_MAIN();
    $zencaptchaPlugin->init();
}


require plugin_dir_path( __FILE__ ) . 'functions.php';


add_filter( 'script_loader_tag', 'zencaptcha_async_defer', 10, 3 );
wp_enqueue_script('zencaptcha-widget', zencap_plugin_url('src/frontend/widget.js'), array(), ZENCAPTCHA_WIDGET_VERSION, true);

add_shortcode('zencaptcha', 'zencap_shortcode');


if(get_option('zen-wp-register')){
    require plugin_dir_path( __FILE__ ) . '/plugins/wordpress/wp-register.php';
}

if(get_option('zen-wp-login')){
    require plugin_dir_path( __FILE__ ) . '/plugins/wordpress/wp-login.php';
}

if(get_option('zen-wp-resetpass')){
    $added_LostPass_filter = true;
    add_filter( 'lostpassword_post', 'zen_wp_lostpass_post', 30, 3 );
    require plugin_dir_path( __FILE__ ) . '/plugins/wordpress/wp-resetpass.php';
}

if(get_option('zen-wp-comments-guests')){
    require plugin_dir_path( __FILE__ ) . '/plugins/wordpress/wp-comments-guests.php';
}

if(get_option('zen-wpforms-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/wpforms/wpforms.php';
}

if(get_option('zen-cf7-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/cf7/cf7-forms.php';
}

if(get_option('zen-fluentform-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/fluentforms/fluent.php';
}

if(get_option('zen-forminator-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/forminatorforms/forminator.php';
}

if(get_option('zen-mailchimp-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/mailchimp/mc4wp.php';
}

if(get_option('zen-htmlforms-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/htmlforms/hf.php';
}

if(get_option('zen-divilogin-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/divi/login.php';
}
if(get_option('zen-divicontact-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/divi/contact.php';
}
if(get_option('zen-diviemailoptin-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/divi/email.php';
}

if(get_option('zen-ultimatememberlogin-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/ultimatemembers/login.php';
}

if(get_option('zen-ultimatememberregister-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/ultimatemembers/register.php';
}

if(get_option('zen-ultimatememberresetpass-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/ultimatemembers/resetpass.php';
}

if(get_option('zen-profilebuilderlogin-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/profilebuilder/login.php';
}

if(get_option('zen-profilebuilderregister-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/profilebuilder/register.php';
}

if(get_option('zen-profilebuilderresetpass-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/profilebuilder/resetpass.php';
}

if(get_option('zen-elementor-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/elementor/pro.php';
}

if(get_option('zen-avada-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/avada/avada.php';
}

/*if(get_option('zen-ninja-activate')){
    require plugin_dir_path( __FILE__ ) . '/plugins/ninjaforms/ninja.php';
}*/

if(get_option('zen-woo-login')){
    require plugin_dir_path( __FILE__ ) . '/plugins/woocommerce/login.php';
}
if(get_option('zen-woo-register')){
    require plugin_dir_path( __FILE__ ) . '/plugins/woocommerce/register.php';
}
if(get_option('zen-woo-resetpass')){
    if(!$added_LostPass_filter){
        add_filter( 'lostpassword_post', 'zen_wp_lostpass_post', 30, 3 );
    }   
    require plugin_dir_path( __FILE__ ) . '/plugins/woocommerce/resetpass.php';
}
if(get_option('zen-woo-checkout')){
    require plugin_dir_path( __FILE__ ) . '/plugins/woocommerce/checkout.php';
}

if(get_option('zen-gravity-activate')){
    //use another widget for this
    require plugin_dir_path( __FILE__ ) . '/plugins/gravityforms/gravity.php';
}