import './bootstrap';

const search = document.querySelector('#product-search');
const category = document.querySelector('#category-filter');
const sort = document.querySelector('#sort-products');
const grid = document.querySelector('#product-grid');
const count = document.querySelector('#result-count');
const empty = document.querySelector('#empty-state');

if (search && category && sort && grid) {
	const cards = [...grid.querySelectorAll('.product-card')];
	const applyFilters = () => {
		const query = search.value.trim().toLowerCase();
		const selectedCategory = category.value;
		const visible = cards.filter((card) => {
			const matchesQuery = card.dataset.name.includes(query) || card.textContent.toLowerCase().includes(query);
			const matchesCategory = selectedCategory === 'all' || card.dataset.category === selectedCategory;
			card.hidden = !(matchesQuery && matchesCategory);
			return !card.hidden;
		});

		if (sort.value === 'price-low') visible.sort((a, b) => Number(a.dataset.price) - Number(b.dataset.price));
		if (sort.value === 'stock') visible.sort((a, b) => Number(b.dataset.stock) - Number(a.dataset.stock));
		visible.forEach((card) => grid.appendChild(card));
		count.textContent = `${visible.length} ${visible.length === 1 ? 'product' : 'products'}`;
		empty.hidden = visible.length > 0;
	};

	[search, category, sort].forEach((control) => control.addEventListener('input', applyFilters));
}
