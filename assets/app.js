import './stimulus_bootstrap.js';
import './styles/app.scss';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('DOMContentLoaded', () => {

    // ===== BURGER MENU =====
    const burger   = document.getElementById('burger-btn');
    const navInner = document.querySelector('.header-nav-inner');

    if (burger && navInner) {
        burger.addEventListener('click', () => {
            navInner.classList.toggle('open');
        });
    }

    // ===== SOUS-MENUS MOBILE AU CLIC =====
    const submenus = document.querySelectorAll('.has-submenu > a');
    submenus.forEach(link => {
        link.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                const submenu = link.nextElementSibling;
                if (submenu) submenu.classList.toggle('open');
            }
        });
    });

    // ===== TOGGLE BARRE DE RECHERCHE =====
    const headerSearch = document.getElementById('header-search');
    const searchInput  = document.getElementById('search-input');

    function toggleSearchBar(labelEl) {
        if (!headerSearch) return;
        const isVisible = headerSearch.style.display === 'flex';
        headerSearch.style.display = isVisible ? 'none' : 'flex';
        if (labelEl) labelEl.textContent = isVisible ? 'Recherche' : 'Masquer';
        if (!isVisible && searchInput) searchInput.focus();
    }

    const toggleSearch  = document.getElementById('toggle-search');
    const searchLabel   = document.getElementById('search-label');
    if (toggleSearch) {
        toggleSearch.addEventListener('click', () => toggleSearchBar(searchLabel));
    }

    const toggleSearchMobile = document.getElementById('toggle-search-mobile');
    const searchLabelMobile  = document.getElementById('search-label-mobile');
    if (toggleSearchMobile) {
        toggleSearchMobile.addEventListener('click', () => toggleSearchBar(searchLabelMobile));
    }

    // ===== TOGGLE SIDEBAR CATÉGORIES MOBILE =====
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarMenu   = document.getElementById('sidebar-menu');
    const sidebarLabel  = document.getElementById('sidebar-toggle-label');

    if (sidebarToggle && sidebarMenu) {
        sidebarToggle.addEventListener('click', () => {
            const isOpen = sidebarMenu.classList.toggle('open');
            if (sidebarLabel) sidebarLabel.textContent = isOpen ? 'Masquer ▴' : 'Afficher ▾';
        });
    }

    // ===== SIDEBAR FILTRES MOBILE (page coupons) =====
    const btnFiltreMobile = document.getElementById('btn-filtre-mobile');
    const sidebarFiltres  = document.getElementById('articles-sidebar');
    const overlay         = document.getElementById('sidebar-overlay');
    const btnClose        = document.getElementById('sidebar-close');
    const badge           = document.getElementById('filtre-mobile-badge');
    const formFiltres     = document.getElementById('form-filtres');
    const sidebarSearch   = document.getElementById('sidebar-search-input');

    if (btnFiltreMobile && sidebarFiltres) {

        function openSidebar() {
            sidebarFiltres.classList.add('is-open');
            if (overlay) overlay.classList.add('is-active');
            btnFiltreMobile.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebarFiltres.classList.remove('is-open');
            if (overlay) overlay.classList.remove('is-active');
            btnFiltreMobile.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        function updateBadge() {
            if (!formFiltres || !badge) return;
            const count = formFiltres.querySelectorAll('input[type="checkbox"]:checked').length
                        + (sidebarSearch && sidebarSearch.value.trim() ? 1 : 0);
            badge.textContent    = count || '';
            badge.style.display  = count > 0 ? 'flex' : 'none';
        }

        btnFiltreMobile.addEventListener('click', openSidebar);
        if (btnClose) btnClose.addEventListener('click', closeSidebar);
        if (overlay)  overlay.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeSidebar();
        });

        if (formFiltres) {
            formFiltres.querySelectorAll('input').forEach(input => {
                input.addEventListener('change', updateBadge);
                input.addEventListener('input',  updateBadge);
            });
        }

        updateBadge();
    }

    // ===== FILTRES AJAX (page coupons) =====
    const formAjax = document.getElementById('form-filtres');
    const main     = document.getElementById('articles-main');

    if (formAjax && main) {

        const resetWrap   = document.getElementById('sidebar-reset-wrap');
        const searchInput = document.getElementById('sidebar-search-input');
        const baseUrl     = formAjax.getAttribute('action');
        let debounceTimer = null;

        function setLoading(on) {
            main.style.opacity       = on ? '0.4' : '1';
            main.style.pointerEvents = on ? 'none' : '';
        }

        function buildUrl() {
            const params = new URLSearchParams();
            formAjax.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
                params.append(cb.name, cb.value);
            });
            const q = searchInput ? searchInput.value.trim() : '';
            if (q) params.set('search', q);
            return params.toString() ? baseUrl + '?' + params.toString() : baseUrl;
        }

        function updateResetBtn() {
            if (!resetWrap) return;
            const hasChecked = formAjax.querySelectorAll('input:checked').length > 0;
            const hasSearch  = searchInput && searchInput.value.trim().length > 0;
            resetWrap.style.display = (hasChecked || hasSearch) ? 'block' : 'none';
        }

        function fetchGrille() {
            setLoading(true);
            fetch(buildUrl(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res  => res.text())
                .then(html => { main.innerHTML = html; setLoading(false); })
                .catch(()  => setLoading(false));
        }

        formAjax.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', () => {
                updateResetBtn();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchGrille, 200);
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                updateResetBtn();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchGrille, 400);
            });
        }

        // ===== ACCORDION SIDEBAR =====
        document.querySelectorAll('.sidebar-accordion-toggle').forEach(btn => {
            const content = document.getElementById(btn.getAttribute('aria-controls'));
            const icon    = btn.querySelector('.sidebar-accordion-icon');
            if (!content) return;

            btn.addEventListener('click', () => {
                const isOpen = btn.getAttribute('aria-expanded') === 'true';
                if (isOpen) {
                    content.setAttribute('hidden', '');
                    btn.setAttribute('aria-expanded', 'false');
                    if (icon) icon.textContent = '▾';
                } else {
                    content.removeAttribute('hidden');
                    btn.setAttribute('aria-expanded', 'true');
                    if (icon) icon.textContent = '▴';
                }
            });
        });

        updateResetBtn();
    }
});