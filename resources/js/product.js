// document.getElementById('filter-form').addEventListener('change', function() {
//     this.submit();
// });


// Submit form when sorting or categories change
document.getElementById('sort-price').addEventListener('change', function () {
    document.getElementById('filter-form').submit();
});

// Submit form when checkbox kategori berubah
document.querySelectorAll("#sidebar input[type=checkbox]").forEach((el) => {
    el.addEventListener("change", () => {
        document.getElementById("filter-form").submit();
    });
});


// Submit form saat enter di input price
document.querySelectorAll('input[name="price_from"], input[name="price_to"]').forEach(input => {
    input.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filter-form').submit();
        }
    });
});

