const fs = require('fs');
const { chromium } = require('C:/Users/gjabragais/.agents/skills/playwright/node_modules/playwright');

(async () => {
    const products = JSON.parse(fs.readFileSync('daizen-catalog/products.json', 'utf8')).filter((product) => product.category !== 'Store assets');
    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage({ viewport: { width: 1400, height: 1000 } });
    for (let offset = 0; offset < products.length; offset += 40) {
        const batch = products.slice(offset, offset + 40);
        const cards = batch.map((product) => `<figure><img src="http://127.0.0.1:4173/daizen-catalog/${product.image}"><figcaption>${product.id} | ${product.name}<br>${product.image}</figcaption></figure>`).join('');
        await page.setContent(`<style>body{margin:0;background:#eee;font:12px Arial}.sheet{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;padding:12px}figure{margin:0;padding:6px;background:#fff;border:1px solid #ccc}img{width:100%;height:220px;object-fit:contain;background:#fafafa}figcaption{height:34px;overflow:hidden;margin-top:5px}</style><div class="sheet">${cards}</div>`);
        await page.screenshot({ path: `D:/daizen-hardware-production/.contact-${offset + 1}.png`, fullPage: true });
    }
    await browser.close();
})();
