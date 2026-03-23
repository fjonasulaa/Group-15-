document.addEventListener("DOMContentLoaded", function () {
    const shareBtn = document.getElementById('share-btn');
    const sharePopover = document.getElementById('share-popover');
    const wineName = document.getElementById('share-wine-title')?.dataset.name || document.title;
    const url = window.location.href;

    if (!shareBtn || !sharePopover) return;
    shareBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        sharePopover.classList.toggle('open');
    });

    // close when clicking outside
    document.addEventListener('click', function(e) {
        if (!shareBtn.contains(e.target) && !sharePopover.contains(e.target)) {
            sharePopover.classList.remove('open');
        }
    });

    // whatsapp link
    document.getElementById('share-whatsapp').href =
        'https://wa.me/?text=' + encodeURIComponent(wineName + ' - ' + url);

    // twitter link
    document.getElementById('share-twitter').href =
        'https://twitter.com/intent/tweet?text=' + encodeURIComponent(wineName) + '&url=' + encodeURIComponent(url);

    // Email
    document.getElementById('share-email').href =
        'mailto:?subject=' + encodeURIComponent(wineName) + '&body=' + encodeURIComponent(url);

    // copy link
    document.getElementById('share-copy').addEventListener('click', function(e) {
        e.stopPropagation();
        navigator.clipboard.writeText(url).then(function() {
            const label = document.getElementById('share-copy-label');

            // says copied after you copy a link for 2 seconds
            label.textContent = 'Copied!';
            setTimeout(() => label.textContent = 'Copy link', 2000);
        });
    });
});