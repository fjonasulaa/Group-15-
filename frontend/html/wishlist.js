document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("wishlist-sidebar");
    const sidebarItems = document.getElementById("wishlist-items");
    const pageItems = document.getElementById("wishlist-page-items");
    const countElement = document.getElementById("wishlist-count");

    /* -----------------------------
       STORAGE
    ----------------------------- */

    function getWishlist() {
        return JSON.parse(localStorage.getItem("wishlist")) || [];
    }

    function saveWishlist(items) {
        localStorage.setItem("wishlist", JSON.stringify(items));
    }

    /* -----------------------------
       ADD / REMOVE
    ----------------------------- */

    function addToWishlist(item) {
    let wishlist = getWishlist();

    let existing = wishlist.find(w => w.id === item.id);

    if (existing) {
        existing.quantity += item.quantity;
    } else {
        wishlist.push(item);
    }

    saveWishlist(wishlist);
    renderWishlist();
    openSidebar();
}
    }

    function removeFromWishlist(id) {
        let wishlist = getWishlist().filter(item => item.id !== id);
        saveWishlist(wishlist);
        renderWishlist();
    }

    /* -----------------------------
       BASKET (frontend only)
    ----------------------------- */

    function addToBasket(id) {
        let wishlist = getWishlist();
        let item = wishlist.find(i => i.id === id);
        if (!item) return;

        let basket = JSON.parse(localStorage.getItem("basket")) || [];
        basket.push(item);
        localStorage.setItem("basket", JSON.stringify(basket));

        removeFromWishlist(id);
        showToast("Added to basket");
    }

    /* -----------------------------
       RENDER
    ----------------------------- */

    function renderWishlist() {
        const wishlist = getWishlist();

        // Update counter
        if (countElement) {
            countElement.textContent = wishlist.length;
        }

        // SIDEBAR
        if (sidebarItems) {
            sidebarItems.innerHTML = "";

            if (wishlist.length === 0) {
                sidebarItems.innerHTML = "<p>Your wishlist is empty.</p>";
            } else {
                wishlist.forEach(item => {
                    sidebarItems.innerHTML += `
                        <div class="wishlist-item">
                            <img src="${item.image}" width="60">
                            <div>
                                <strong>${item.name}</strong>
                                <p>£${item.price}</p>
                                <button onclick="Wishlist.addToBasket('${item.id}')">Add to Basket</button>
                                <button onclick="Wishlist.remove('${item.id}')">Remove</button>
                            </div>
                        </div>
                    `;
                });
            }
        }

        // FULL PAGE
        if (pageItems) {
            pageItems.innerHTML = "";

            if (wishlist.length === 0) {
                pageItems.innerHTML = "<p>Your wishlist is empty.</p>";
            } else {
                wishlist.forEach(item => {
                    pageItems.innerHTML += `
                        <div class="wishlist-row">
                            <img src="${item.image}">
                            <div>
                                <div class="wishlist-info-title">${item.name}</div>
                                <p>£${item.price}</p>
                                <button onclick="Wishlist.addToBasket('${item.id}')">Add to Basket</button>
                                <br>
                                <a href="#" onclick="Wishlist.remove('${item.id}')" class="remove-link">Remove</a>
                            </div>
                        </div>
                    `;
                });
            }
        }
    }

    /* -----------------------------
       SIDEBAR CONTROL
    ----------------------------- */

    function openSidebar() {
        if (sidebar) sidebar.classList.add("active");
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove("active");
    }

    const toggleBtn = document.getElementById("wishlist-toggle");
    const closeBtn = document.getElementById("close-wishlist");

    if (toggleBtn) toggleBtn.addEventListener("click", openSidebar);
    if (closeBtn) closeBtn.addEventListener("click", closeSidebar);

    /* -----------------------------
       TOAST MESSAGE
    ----------------------------- */

    function showToast(message) {
        const toast = document.createElement("div");
        toast.className = "wishlist-toast";
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add("show"), 50);
        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    /* -----------------------------
       PUBLIC API
    ----------------------------- */

    window.Wishlist = {
        add: addToWishlist,
        remove: removeFromWishlist,
        addToBasket: addToBasket
    };

    renderWishlist();
});