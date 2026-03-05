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

    // Bouton desktop
    const toggleSearch  = document.getElementById('toggle-search');
    const searchLabel   = document.getElementById('search-label');
    if (toggleSearch) {
        toggleSearch.addEventListener('click', () => toggleSearchBar(searchLabel));
    }

    // Bouton mobile
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
        sidebarLabel.textContent = isOpen ? 'Masquer ▴' : 'Afficher ▾';
    });
}
});