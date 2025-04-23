document.addEventListener('DOMContentLoaded', function () {
    const categoryField = document.querySelector('select[name$="[categorie]"]');
    const filterContainer = document.querySelector('[data-widget="produit-filtre-container"]');

    if (!categoryField || !filterContainer) return;

    function loadFilters(categoryId) {
        fetch('/admin/ajax/load-filtres?category=' + categoryId)
            .then(response => response.text())
            .then(html => {
                filterContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur de chargement des filtres:', error);
            });
    }

    categoryField.addEventListener('change', function () {
        const selectedCategory = categoryField.value;
        if (selectedCategory) {
            loadFilters(selectedCategory);
        } else {
            filterContainer.innerHTML = '';
        }
    });

    // При редактировании, если категория уже выбрана — загрузить сразу
    if (categoryField.value) {
        loadFilters(categoryField.value);
    }
});