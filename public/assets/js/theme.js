document.addEventListener("DOMContentLoaded", function() {
    /* -------------------------------------------------------------------------- */
    /*                               1. TEMEL AYARLAR                             */
    /* -------------------------------------------------------------------------- */
    const html = document.querySelector("html");
    const loader = document.getElementById("loader");
    const progressBar = document.querySelector(".progress-top-bar");
    const scrollTopBtn = document.querySelector(".scrollToTop");

    /* -------------------------------------------------------------------------- */
    /*                          2. LOADER (YÜKLEME EKRANI)                        */
    /* -------------------------------------------------------------------------- */
    // Sayfa tamamen yüklendiğinde loader'ı gizle
    window.addEventListener("load", function() {
        if (loader) {
            setTimeout(() => {
                // Loader'ı gizlemek için CSS class'ı ekle veya display none yap
                loader.classList.add("d-none");
                // HTML tagindeki loader attribute'unu güncelle (CSS uyumluluğu için)
                html.setAttribute("loader", "disable");
            }, 500); // 0.5 saniye bekle (akıcılık için)
        }
    });

    /* -------------------------------------------------------------------------- */
    /*                      3. SCROLL (PROGRESS BAR & BACK TO TOP)                */
    /* -------------------------------------------------------------------------- */
    window.addEventListener("scroll", function() {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrollPercentage = (scrollTop / scrollHeight) * 100;

        // A. Progress Bar Genişliğini Ayarla
        if (progressBar) {
            progressBar.style.width = scrollPercentage + "%";
        }

        // B. Scroll To Top Butonunu Göster/Gizle
        if (scrollTopBtn) {
            if (scrollTop > 300) {
                // Aşağı inildiyse butonu göster
                scrollTopBtn.style.display = "flex"; // Flex ile ortalamayı korur
                scrollTopBtn.style.opacity = "1";
                scrollTopBtn.style.bottom = "30px"; // Görünür konuma getir
                scrollTopBtn.style.visibility = "visible";
            } else {
                // En üstteyse gizle
                scrollTopBtn.style.opacity = "0";
                scrollTopBtn.style.bottom = "-50px"; // Aşağıya sakla
                scrollTopBtn.style.visibility = "hidden";
            }
        }
    });

    // Scroll To Top Tıklama Olayı
    if (scrollTopBtn) {
        scrollTopBtn.addEventListener("click", function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }

    /* -------------------------------------------------------------------------- */
    /*                         4. DARK / LIGHT TEMA MODU                          */
    /* -------------------------------------------------------------------------- */
    const themeToggles = document.querySelectorAll(".layout-setting, .layout-setting-doublemenu");

    // LocalStorage Kontrolü (Sayfa yenilenince tema hatırlasın)
    const savedTheme = localStorage.getItem("vyzor-theme-mode");
    if (savedTheme) {
        applyTheme(savedTheme);
    }

    // Tema Değiştirme Butonları
    themeToggles.forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            const currentTheme = html.getAttribute("data-theme-mode");
            const newTheme = currentTheme === "dark" ? "light" : "dark";
            applyTheme(newTheme);
        });
    });

    function applyTheme(theme) {
        html.setAttribute("data-theme-mode", theme);

        if (theme === "dark") {
            html.setAttribute("data-header-styles", "dark");
            html.setAttribute("data-menu-styles", "dark");
        } else {
            html.setAttribute("data-header-styles", "transparent");
            html.setAttribute("data-menu-styles", "transparent");
            html.removeAttribute("style");
        }
        localStorage.setItem("vyzor-theme-mode", theme);
    }
});
