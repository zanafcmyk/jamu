<?php
// Halaman tutorial resep; JavaScript menghitung ulang bahan dari jumlah botol yang dipilih.
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Resep dan tutorial';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero recipe-hero"><p class="eyebrow">RACIK SENDIRI DI RUMAH</p><h1>Tutorial membuat jamu.</h1><p>Pilih varian dan tulis berapa botol ingin dibuat. Takaran bahan akan menyesuaikan, lalu ikuti langkahnya satu per satu.</p></section>
<section class="recipe-book-section">
    <div class="book-heading"><p class="eyebrow">BUKU RESEP JAMU</p><h2>Catatan racikan tradisional.</h2><p>Ringkasan bahan dan cara membuat dua varian jamu rumahan.</p></div>
    <div class="book-spread">
        <article class="book-page"><span class="book-mark">❋</span><div class="book-copy"><span class="recipe-number">RESEP 01 · BERAS KENCUR</span><h3>Rasa lembut, aroma rempah.</h3><p class="book-ingredients"><b>Bahan:</b> 100 g beras, 80 g kencur, 20 g jahe, 100 g gula merah, 1 sdm asam jawa, dan 1 liter air (± 4 botol).</p><ol><li>Cuci dan rendam beras 2–3 jam, lalu tiriskan.</li><li>Rebus air dengan kencur, jahe, gula merah, dan asam jawa hingga harum.</li><li>Blender beras dengan sebagian rebusan, campurkan, lalu saring.</li><li>Tuang ke botol bersih dan sajikan.</li></ol></div></article>
        <article class="book-page"><span class="book-mark">❋</span><div class="book-copy"><span class="recipe-number">RESEP 02 · KUNIR ASEM</span><h3>Segar dengan asam jawa.</h3><p class="book-ingredients"><b>Bahan:</b> 120 g kunyit, 40 g asam jawa, 120 g gula merah, ¼ sdt garam, dan 1 liter air (± 4 botol).</p><ol><li>Cuci kunyit, kupas bila perlu, lalu parut atau blender.</li><li>Campur kunyit, asam jawa, gula merah, garam, dan air.</li><li>Rebus sambil diaduk hingga mendidih dan gula larut.</li><li>Dinginkan, saring, lalu tuang ke botol bersih.</li></ol></div></article>
    </div>
</section>
<section class="tutorial-intro"><p class="eyebrow">INGIN TAKARAN YANG DISESUAIKAN?</p><h2>Gunakan tutorial interaktif.</h2><p>Masukkan jumlah botol yang ingin dibuat untuk menghitung bahan secara otomatis dan mengikuti langkah pembuatan satu per satu.</p></section>
<section class="recipe-wizard-wrap">
    <div class="recipe-setup">
        <div class="setup-heading"><span class="setup-icon">♨</span><div><p class="eyebrow">MULAI DARI SINI</p><h2>Siapkan racikanmu</h2></div></div>
        <form id="recipe-form" class="recipe-form">
            <label>Pilih varian jamu<select id="recipe-variant"><option value="beras">Beras Kencur</option><option value="kunir">Kunir Asem</option></select></label>
            <label>Berapa botol ingin dibuat?<span class="quantity-input"><input id="recipe-quantity" type="number" min="1" max="100" value="4" required><small>botol · sekitar 250 ml per botol</small></span></label>
            <button class="button" type="submit">Lihat takaran & mulai <span>→</span></button>
            <p class="recipe-hint">Takaran dasar resep ini untuk 4 botol. Jumlah bahan akan dihitung otomatis.</p>
        </form>
    </div>
    <div id="recipe-tutorial" class="recipe-tutorial" hidden>
        <div class="tutorial-top"><div><p class="eyebrow">PERSIAPAN BAHAN</p><h2 id="tutorial-title">Beras Kencur</h2><p id="tutorial-yield" class="yield-note"></p></div><button type="button" class="text-button" id="change-recipe">Ubah jumlah / varian</button></div>
        <div class="ingredient-panel"><h3>Bahan yang dibutuhkan</h3><ul id="ingredient-list"></ul></div>
        <div class="step-panel"><div class="step-top"><span id="step-count">Langkah 1 dari 1</span><div class="progress-track"><span id="step-progress"></span></div></div><div class="step-content"><span class="step-number" id="step-number">01</span><div><h3 id="step-title"></h3><p id="step-description"></p></div></div><div class="step-controls"><button type="button" class="step-button secondary" id="previous-step">← Sebelumnya</button><button type="button" class="step-button" id="next-step">Langkah berikutnya →</button></div></div>
    </div>
</section>
<section class="recipe-note"><span>✳</span><p>Gunakan alat dan botol yang bersih. Simpan jamu di kulkas dan konsumsi segera. Resep ini merupakan panduan rumahan; sesuaikan rasa menurut selera.</p></section>
<script>
// Takaran bahan dasar dibuat untuk 4 botol, lalu diskalakan mengikuti jumlah pilihan.
const recipes = {
    beras: {
        name: 'Beras Kencur',
        ingredients: [
            {name:'Beras', amount:100, unit:'g'}, {name:'Kencur', amount:80, unit:'g'},
            {name:'Jahe', amount:20, unit:'g'}, {name:'Gula merah', amount:100, unit:'g'},
            {name:'Asam jawa', amount:1, unit:'sdm'}, {name:'Air matang', amount:1000, unit:'ml'}
        ],
        steps: [
            ['Cuci dan rendam beras','Cuci beras hingga bersih, lalu rendam selama 2–3 jam. Tiriskan sebelum digunakan.'],
            ['Siapkan rempah','Cuci kencur dan jahe, lalu memarkan atau iris tipis agar sarinya mudah keluar.'],
            ['Rebus bahan','Rebus air bersama kencur, jahe, gula merah, dan asam jawa hingga gula larut dan aromanya harum. Dinginkan.'],
            ['Haluskan dan saring','Blender beras dengan sebagian air rebusan. Campurkan dengan sisa rebusan, lalu saring hingga halus.'],
            ['Sajikan','Tuang ke botol bersih. Sajikan dingin atau hangat; simpan sisanya di kulkas.']
        ]
    },
    kunir: {
        name: 'Kunir Asem',
        ingredients: [
            {name:'Kunyit segar', amount:120, unit:'g'}, {name:'Asam jawa', amount:40, unit:'g'},
            {name:'Gula merah', amount:120, unit:'g'}, {name:'Garam', amount:0.25, unit:'sdt'},
            {name:'Air matang', amount:1000, unit:'ml'}
        ],
        steps: [
            ['Cuci dan haluskan kunyit','Cuci kunyit sampai bersih, kupas bila perlu, lalu parut atau blender dengan sedikit air.'],
            ['Campur bahan','Masukkan kunyit, asam jawa, gula merah, garam, dan air ke dalam panci. Aduk hingga tercampur.'],
            ['Rebus hingga harum','Didihkan dengan api sedang sambil sesekali diaduk sampai gula larut dan warna kunyit keluar.'],
            ['Saring jamu','Matikan api dan biarkan agak hangat. Saring ampas kunyit dan asam jawa.'],
            ['Sajikan','Tuang ke botol bersih. Nikmati dingin atau hangat dan simpan sisanya di kulkas.']
        ]
    }
};
const recipeForm = document.getElementById('recipe-form');
const tutorial = document.getElementById('recipe-tutorial');
let currentSteps = [];
let currentStep = 0;
function tidyNumber(number) { return Number(number.toFixed(2)).toString(); }
function showStep() {
    const step = currentSteps[currentStep];
    document.getElementById('step-count').textContent = `Langkah ${currentStep + 1} dari ${currentSteps.length}`;
    document.getElementById('step-number').textContent = String(currentStep + 1).padStart(2, '0');
    document.getElementById('step-title').textContent = step[0];
    document.getElementById('step-description').textContent = step[1];
    document.getElementById('step-progress').style.width = `${((currentStep + 1) / currentSteps.length) * 100}%`;
    document.getElementById('previous-step').disabled = currentStep === 0;
    document.getElementById('next-step').textContent = currentStep === currentSteps.length - 1 ? 'Selesai ✓' : 'Langkah berikutnya →';
}
recipeForm.addEventListener('submit', event => {
    event.preventDefault();
    const quantity = Number(document.getElementById('recipe-quantity').value);
    if (!Number.isInteger(quantity) || quantity < 1 || quantity > 100) return;
    const recipe = recipes[document.getElementById('recipe-variant').value];
    document.getElementById('tutorial-title').textContent = recipe.name;
    document.getElementById('tutorial-yield').textContent = `Takaran untuk ${quantity} botol (± ${quantity * 250} ml)`;
    const list = document.getElementById('ingredient-list');
    list.replaceChildren();
    recipe.ingredients.forEach(item => {
        const li = document.createElement('li');
        li.innerHTML = `<span>${item.name}</span><b>${tidyNumber(item.amount * quantity / 4)} ${item.unit}</b>`;
        list.appendChild(li);
    });
    currentSteps = recipe.steps;
    currentStep = 0;
    showStep();
    tutorial.hidden = false;
    tutorial.scrollIntoView({behavior:'smooth', block:'start'});
});
document.getElementById('previous-step').addEventListener('click', () => { if (currentStep > 0) { currentStep--; showStep(); } });
document.getElementById('next-step').addEventListener('click', () => {
    if (currentStep < currentSteps.length - 1) { currentStep++; showStep(); }
    else { document.getElementById('step-title').textContent = 'Tutorial selesai'; document.getElementById('step-description').textContent = 'Jamu siap dinikmati. Terima kasih sudah meracik jamu sendiri!'; }
});
document.getElementById('change-recipe').addEventListener('click', () => { tutorial.hidden = true; recipeForm.scrollIntoView({behavior:'smooth', block:'center'}); });
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
