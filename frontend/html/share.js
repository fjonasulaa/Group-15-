(function () {
    var btn     = document.getElementById('share-btn');
    var popover = document.getElementById('share-popover');
 
    if (!btn || !popover) return;
 
    var url   = window.location.href;
    var title = (document.getElementById('share-wine-title') || {}).dataset.name || document.title;
 
    document.getElementById('share-whatsapp').href =
        'https://wa.me/?text=' + encodeURIComponent(title + ' ' + url);
 
    document.getElementById('share-twitter').href =
        'https://twitter.com/intent/tweet?text=' + encodeURIComponent('Check out ' + title) +
        '&url=' + encodeURIComponent(url);
 
    document.getElementById('share-email').href =
        'mailto:?subject=' + encodeURIComponent(title) +
        '&body=' + encodeURIComponent('I found this wine you might like: ' + url);
 
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        popover.classList.toggle('open');
    });
 
    document.getElementById('share-copy').addEventListener('click', function () {
        navigator.clipboard.writeText(url).then(function () {
            var lbl = document.getElementById('share-copy-label');
            lbl.textContent = 'Copied!';
            setTimeout(function () { lbl.textContent = 'Copy link'; }, 2000);
        });
    });
 
    document.addEventListener('click', function (e) {
        if (!popover.contains(e.target) && e.target !== btn) {
            popover.classList.remove('open');
        }
    });
})();