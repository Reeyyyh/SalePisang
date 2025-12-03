describe('Manajemen Produk Seller', () => {
    const sellerEmail = 'Seller@gmail.com';
    const sellerPassword = 'Seller#1234';

    before(() => {
        cy.exec('php artisan product:delete-all', { failOnNonZeroExit: false })
    });

    beforeEach(() => {
        cy.session('seller-login', () => {
            cy.visit('/login')
            cy.get('input[name=email]', { timeout: 10000 }).type(sellerEmail)
            cy.get('input[name=password]').type(sellerPassword)
            cy.get('button[type=submit]').click()

            cy.url({ timeout: 10000 }).should('include', '/seller-dashboard')
        })
    });

    it('TC-PROD-01: Menambah produk baru dengan data valid', () => {

        cy.visit('/seller-dashboard/products/create');

        cy.wait(500);

        cy.get('input[name=product_name]').type('Pisang Cavendish Premium');
        cy.get('input[name=price]').type('25000');
        cy.get('input[name=stock]').type('100');
        cy.get('textarea[name=description]').type('Pisang berkualitas ekspor, segar dan manis.');
        cy.get('select[name=category_id]').select('Kategori 1');
        cy.get('input[name=status][value=available]').check();

        cy.get('button[type=submit]').contains(/Simpan|Save Product/i).click();
        cy.contains('Produk berhasil ditambahkan').should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-02: Produk tidak disimpan jika kategori belum dipilih', () => {

        cy.visit('/seller-dashboard/products/create');
        cy.wait(500);

        cy.get('input[name=product_name]').type('Pisang Cavendish Premium');
        cy.get('input[name=price]').type('25000');
        cy.get('input[name=stock]').type('100');
        cy.get('textarea[name=description]').type('Pisang berkualitas ekspor.');
        cy.get('input[name=status][value=available]').check();

        cy.get('button[type=submit]').contains(/Simpan|Save Product/i).click();

        cy.get('#category_id')
            .parent()
            .find('p.text-red-500')
            .should('contain.text', 'The category id field is required');
        cy.wait(1000);
    });

    it('TC-PROD-03: Validasi input wajib', () => {
        cy.visit('/seller-dashboard/products/create');
        cy.wait(500);

        cy.get('input[name=price]').type('25000');
        cy.get('input[name=stock]').type('100');
        cy.get('textarea[name=description]').type('Deskripsi contoh');
        cy.get('select[name=category_id]').select('Kategori 1');
        cy.get('input[name=status][value=available]').check();

        cy.get('button[type=submit]').contains(/Simpan|Save Product/i).click();
        cy.contains(/Please fill out this field|The product name field is required/).should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-04: Menolak harga atau stok tidak valid', () => {
        cy.visit('/seller-dashboard/products/create');
        cy.wait(500);

        cy.get('input[name=product_name]').type('Produk Test');
        cy.get('input[name=price]').type('-1000');
        cy.get('input[name=stock]').type('-5');
        cy.get('textarea[name=description]').type('Desc');
        cy.get('select[name=category_id]').select('Kategori 1');
        cy.get('input[name=status][value=available]').check();

        cy.get('button[type=submit]').contains(/Simpan|Save Product/i).click();
        cy.get('input[name=price]:invalid').should('exist')
        cy.get('input[name=stock]:invalid').should('exist')
        cy.wait(1000);
    });

    it('TC-PROD-05: Upload gambar opsional', () => {
        cy.visit('/seller-dashboard/products/create');
        cy.wait(500);

        cy.get('input[name=product_name]').type('Produk Tanpa Gambar');
        cy.get('input[name=price]').type('5000');
        cy.get('input[name=stock]').type('10');
        cy.get('textarea[name=description]').type('Deskripsi tanpa gambar');
        cy.get('select[name=category_id]').select('Kategori 1');
        cy.get('input[name=status][value=available]').check();

        cy.get('button[type=submit]').contains(/Simpan|Save Product/i).click();
        cy.contains('Produk berhasil ditambahkan').should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-06: Produk tampil di dashboard seller', () => {
        cy.visit('/seller-dashboard/products');
        cy.contains('Pisang Cavendish Premium').should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-07: Memperbarui data produk', () => {
        cy.visit('/seller-dashboard/products');
        cy.contains('Edit').first().click();
        cy.wait(500);

        cy.get('input[name=product_name]').clear().type('Pisang Cavendish Update');
        cy.get('input[name=price]').clear().type('30000');
        cy.get('button[type=submit]').contains(/Simpan|Update Product/i).click();

        cy.contains('Produk berhasil diperbarui').should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-08: Validasi tetap berjalan saat update produk', () => {
        cy.visit('/seller-dashboard/products');
        cy.contains('Edit').first().click();
        cy.wait(500);

        cy.get('input[name=product_name]').clear();
        cy.get('button[type=submit]').contains(/Simpan|Update Product/i).click();
        cy.contains(/Please fill out this field|The product name field is required/).should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-09: Menghapus produk', () => {
        cy.visit('/seller-dashboard/products');
        cy.contains('Delete').first().click();
        cy.on('window:confirm', () => true);

        cy.contains(/Produk berhasil dihapus/).should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-10: Flash message muncul setelah CRUD', () => {
        cy.log('Flash message telah muncul pada operasi Create, Update, dan Delete dalam test case sebelumnya.');
        cy.wait(500);
    });

    it('TC-PROD-11: Menolak nama produk > 120 karakter', () => {
        cy.visit('/seller-dashboard/products/create');
        cy.wait(500);

        const longName = 'A'.repeat(300);
        cy.get('input[name=product_name]').type(longName);
        cy.get('input[name=price]').type('25000');
        cy.get('input[name=stock]').type('100');
        cy.get('textarea[name=description]').type('Deskripsi panjang');
        cy.get('select[name=category_id]').select('Kategori 1');
        cy.get('input[name=status][value=available]').check();

        cy.get('button[type=submit]').contains(/Simpan|Save Product/i).click();
        cy.contains(/The product name field must not be greater than 120 characters./).should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-12: Menolak harga terlalu besar', () => {
        cy.visit('/seller-dashboard/products/create');
        cy.wait(500);

        cy.get('input[name=product_name]').type('Produk Test');
        cy.get('input[name=price]').type('999999999999999');
        cy.get('input[name=stock]').type('100');
        cy.get('textarea[name=description]').type('Desc');
        cy.get('select[name=category_id]').select('Kategori 1');
        cy.get('input[name=status][value=available]').check();

        cy.get('button[type=submit]').contains(/Simpan|Save Product/i).click();
        cy.contains(/The price field must not be greater than 10000000./).should('exist');
        cy.wait(1000);
    });

    it('TC-PROD-13: Menolak stok terlalu besar', () => {
        cy.visit('/seller-dashboard/products/create');
        cy.wait(500);

        cy.get('input[name=product_name]').type('Produk Test');
        cy.get('input[name=price]').type('5000');
        cy.get('input[name=stock]').type('99999');
        cy.get('textarea[name=description]').type('Desc');
        cy.get('select[name=category_id]').select('Kategori 1');
        cy.get('input[name=status][value=available]').check();

        cy.get('button[type=submit]').contains(/Simpan|Save Product/i).click();
        cy.contains(/The stock field must not be greater than 9999./).should('exist');
        cy.wait(1000);
    });
});
