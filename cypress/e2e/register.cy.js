describe('Register Testing', () => {

    // TC-REG-01 – Registrasi berhasil (valid data)
    it('TC-REG-01 Registrasi berhasil dengan data valid', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)

        cy.get('body').then($body => {
            if ($body.text().includes('The email has already been taken')) {
                cy.log('User sudah ada, skip register')
            } else {
                cy.url().should('include', '/login')
            }
        });
    })

    // TC-REG-02 – Email bukan domain @gmail.com
    it('TC-REG-02 Menolak email bukan domain gmail', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user@yahoo.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("Email harus menggunakan domain @gmail.com").should('be.visible')
    })

    // TC-REG-03 – Email sudah terdaftar
    it('TC-REG-03 Menolak email yang sudah terdaftar', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The email has already been taken").should('be.visible')
    })

    // TC-REG-04 – Password kurang dari 8 karakter
    it('TC-REG-04 Menolak password kurang dari 8 karakter', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("testuser")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("testuser@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("Usr12#")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("Usr12#")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The password field must be at least 8 characters").should('be.visible')
    })

    // TC-REG-05 – Password tanpa huruf besar / angka / simbol
    it('TC-REG-05 Menolak password tanpa huruf besar/angka/simbol', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("testuser")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("testuser@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("user1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("user1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("Password harus mengandung huruf besar, angka, simbol, serta minimal 8 karakter").should('be.visible')
    })

    // TC-REG-06 – Password tidak sama
    it('TC-REG-06 Menolak konfirmasi password tidak cocok', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("testuser")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("testuser@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#12345")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The password confirmation does not match").should('be.visible')
    })

    // TC-REG-07 – Banyak validasi gagal
    it('TC-REG-07 Menampilkan pesan error spesifik (multiple errors)', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("testuser")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("testuser@yahoo.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("user1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("user12345")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("Email harus menggunakan domain @gmail.com").should('be.visible')
        cy.contains("The password confirmation does not match").should('be.visible')
    })

    // TC-REG-08 – Nama lebih dari 100 karakter
    it('TC-REG-08 Menolak nama > 100 karakter', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("a".repeat(101))
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user255@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The name field must not be greater than 100 characters").should('be.visible')
    })

    // TC-REG-09 – Email lebih dari 100 karakter
    it('TC-REG-09 Menolak email > 100 karakter', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("a".repeat(101) + "@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The email field must not be greater than 100 characters.").should('be.visible')
    })

    // TC-REG-10 – Nama kosong
    it('TC-REG-10 Menolak field nama kosong', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').clear()
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The name field is required").should('be.visible')
    })

    // TC-REG-11 – Email kosong
    it('TC-REG-11 Menolak field email kosong', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').clear()
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The email field is required").should('be.visible')
    })

    // TC-REG-12 – Password kosong
    it('TC-REG-12 Menolak password kosong', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').clear()
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').clear()
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The password field is required").should('be.visible')
    })

    // TC-REG-13 – Konfirmasi password kosong
    it('TC-REG-13 Menolak konfirmasi password kosong', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').clear()
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The password confirmation does not match").should('be.visible')
    })

    // TC-REG-14 – Format email tidak valid
    it('TC-REG-14 Menolak format email tidak valid', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("usergmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("The email field must be a valid email address").should('be.visible')
    })

    // TC-REG-15 – Password tanpa huruf besar
    it('TC-REG-15 Menolak password tanpa huruf besar', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("user#1234")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("user#1234")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("Password harus mengandung huruf besar, angka, simbol, serta minimal 8 karakter").should('be.visible')
    })

    // TC-REG-16 – Password tanpa angka
    it('TC-REG-16 Menolak password tanpa angka', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').should('be.visible').type("user")
        cy.wait(300)
        cy.get('input[name="email"]').should('be.visible').type("user@gmail.com")
        cy.wait(300)
        cy.get('input[name="password"]').should('be.visible').type("User#abcd")
        cy.wait(300)
        cy.get('input[name="password_confirmation"]').should('be.visible').type("User#abcd")
        cy.wait(300)
        cy.get('button[type="submit"]').should('be.visible').click()
        cy.wait(500)
        cy.contains("Password harus mengandung huruf besar, angka, simbol, serta minimal 8 karakter").should('be.visible')
    })

    it('TC-REG-17 Register admin', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').type('admin')
        cy.get('input[name="email"]').type('admin@gmail.com')
        cy.get('input[name="password"]').type('Admin#1234')
        cy.get('input[name="password_confirmation"]').type('Admin#1234')
        cy.get('button[type="submit"]').click()

        cy.get('body').then($body => {
            if ($body.text().includes('The email has already been taken')) {
                cy.log('Admin sudah ada, skip register')
            } else {
                cy.url().should('include', '/login')
            }
        });
    })

    it('TC-REG-18 Register seller', () => {
        cy.visit('/register')
        cy.get('input[name="name"]').type('seller')
        cy.get('input[name="email"]').type('seller@gmail.com')
        cy.get('input[name="password"]').type('Seller#1234')
        cy.get('input[name="password_confirmation"]').type('Seller#1234')
        cy.get('button[type="submit"]').click()
        cy.get('body').then($body => {
            if ($body.text().includes('The email has already been taken')) {
                cy.log('Admin sudah ada, skip register')
            } else {
                cy.url().should('include', '/login')
            }
        });
    })

})
