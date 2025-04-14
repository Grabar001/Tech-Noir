import './scss/home/home.scss';

function scrollCategories(direction) {
	const container = document.querySelector('.category-container .scroll-wrapper');
	const scrollAmount = 220;

	if (direction === 'left') {
		container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
	} else {
		container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
	}
}

function scrollPromos(direction) {
	const wrapper = document.querySelector('.promo-scroll-wrapper');
	const scrollAmount = 300;
	if (direction === 'left') {
		wrapper.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
	} else {
		wrapper.scrollBy({ left: scrollAmount, behavior: 'smooth' });
	}
}