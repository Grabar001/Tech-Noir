import './scss/catalog/catalog.scss';
import './toggleView.js'; 

document.addEventListener('DOMContentLoaded', () => {
	const container = document.getElementById('product-container');
	const gridBtn = document.querySelector('.toggle-btn.grid');
	const listBtn = document.querySelector('.toggle-btn.list');

	if (!container || !gridBtn || !listBtn) return;

	const toggleView = (mode) => {
		container.classList.remove('products-grid', 'products-list');
		container.classList.add(mode);

		gridBtn.classList.toggle('active', mode === 'products-grid');
		listBtn.classList.toggle('active', mode === 'products-list');

		// Анимация плавного появления
		container.classList.add('fade-in');
		setTimeout(() => container.classList.remove('fade-in'), 300);
	};

	gridBtn.addEventListener('click', (e) => {
		e.preventDefault();
		toggleView('products-grid');
	});

	listBtn.addEventListener('click', (e) => {
		e.preventDefault();
		toggleView('products-list');
	});
});