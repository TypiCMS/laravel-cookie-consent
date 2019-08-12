@if($cookieConsentConfig['enabled'] && ! $alreadyConsentedWithCookies && ! $rejectedCookies)

    @include('cookieConsent::dialogContents')

    <script>

        window.laravelCookieConsent = (function () {

            const COOKIE_DOMAIN = '{{ config('session.domain') ?? request()->getHost() }}';

            function consentWithCookies() {
                setCookie('{{ $cookieConsentConfig['cookie_name'] }}', 1, {{ $cookieConsentConfig['cookie_lifetime'] }});
                document.location.reload();
            }

            function rejectCookies() {
                setCookie('{{ $cookieConsentConfig['cookie_name'] }}', 0, {{ $cookieConsentConfig['cookie_lifetime'] }});
                hideCookieDialog();
            }

            function cookieExists(name) {
                var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                if (match === null) {
                    return false;
                }
                return true;
            }

            function hideCookieDialog() {
                const dialogs = document.getElementsByClassName('js-cookie-consent');

                for (let i = 0; i < dialogs.length; ++i) {
                    dialogs[i].style.display = 'none';
                }
            }

            function setCookie(name, value, expirationInDays) {
                const date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value
                    + ';expires=' + date.toUTCString()
                    + ';domain=' + COOKIE_DOMAIN
                    + ';path=/{{ config('session.secure') ? ';secure' : null }}';
            }

            if (cookieExists('{{ $cookieConsentConfig['cookie_name'] }}')) {
                hideCookieDialog();
            }

            const consentButtons = document.getElementsByClassName('js-cookie-consent-agree');

            for (let i = 0; i < consentButtons.length; ++i) {
                consentButtons[i].addEventListener('click', consentWithCookies);
            }

            const rejectButtons = document.getElementsByClassName('js-cookie-consent-agree');

            for (let i = 0; i < rejectButtons.length; ++i) {
                rejectButtons[i].addEventListener('click', rejectCookies);
            }

            return {
                consentWithCookies: consentWithCookies,
                rejectCookies: rejectCookies,
                hideCookieDialog: hideCookieDialog
            };
        })();
    </script>

@endif
