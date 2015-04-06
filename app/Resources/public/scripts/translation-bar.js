window.addEventListener("load", function() {
    // Based on the tabzilla Infobar.transalation (https://github.com/freaktechnik/bedrock/blob/master/bedrock/tabzilla/templates/tabzilla/tabzilla.js#L381)
    var transbar = new window.Tabzilla.infobar('transbar', 'Translation Bar');

    var userLangs = navigator.languages;
    var pageLang = document.documentElement.lang;

    if (transbar.disabled || !userLangs || !pageLang) {
        return false;
    }

    // Normalize the user language in the form of ab
    var normalize = function (lang) {
        return lang.replace(/^(\w+)(?:-\w+)?$/, function (m, p1) {
            return p1.toLowerCase();
        });
    };

    // Normalize every language for easier comparison
    userLangs = $.map(userLangs, function (lang) { return normalize(lang); });
    pageLang = normalize(pageLang);

    // If the page language is the user's primary language, there is nothing
    // to do here
    if (pageLang === userLangs[0]) {
        return false;
    }

    // Get the available translation from the page
    var availableLangs = [];
    var $links = $('link[hreflang]');

    if ($links.length) {
        $links.each(function () {
            availableLangs[normalize(this.hreflang)] = this;
        });
    }

    // Compare the user's accept languages against the page's current
    // language and other available languages to find the best language
    $.each(userLangs, function(index, userLang) {
        if (pageLang === userLang) {
            offeredLang = 'self';
            return false; // Break the loop
        }

        if (userLang in availableLangs) {
            offeredLang = userLang;
            return false; // Break the loop
        }
    });

    // If the page language is one of the user's secondary languages or no
    // other language can be found in the translations, there is nothing to do
    if(offeredLang == 'self' || !offeredLang) {
        return false;
    }

    // Do not show Chrome's built-in Translation Bar    
    $('head').append('<meta name="google" value="notranslate">');

    transbar.onaccept = {
        trackAction: '',
        trackLabel: offeredLang,
        callback: function () {
            var element = availableLangs[offeredLang];

            location.href = element.href;
        }
    };

    // Fetch the localized strings and show the Translation Bar
    $.ajax({ url: 'https://mozorg.cdn.mozilla.net/' + offeredLang + '/tabzilla/transbar.jsonp',
             cache: true, crossDomain: true, dataType: 'jsonp',
             jsonpCallback: "_", success: function (str) {
        transbar.show(str).attr({
            'lang': offeredLang,
            'dir': ($.inArray(offeredLang, ['he', 'ar', 'fa', 'ur']) > -1) ? 'rtl' : 'ltr'
        });
    }});
});
