describe('Login Testing', () => {

    it('TC-LOGIN-01 - Menolak login jika email & password kosong', () => {
        cy.visit('/login')
        cy.get('button[type="submit"]').click()
        cy.contains('The email field is required.', { timeout: 8000 }).should('exist')
        cy.contains('The password field is required.', { timeout: 8000 }).should('exist')
        cy.wait(1000)
    })

    it('TC-LOGIN-02 - Menolak login dengan password salah', () => {
        cy.visit('/login')
        cy.get('input[name="email"]').type('user@gmail.com')
        cy.get('input[name="password"]').type('User#9999')
        cy.get('button[type="submit"]').click()
        cy.contains('Email atau password salah.', { timeout: 8000 }).should('be.visible')
        cy.wait(1000)
    })


    it('TC-LOGIN-03 - Seller diarahkan ke /seller-dashboard', () => {
        cy.visit('/login')
        cy.get('input[name="email"]').type('seller@gmail.com')
        cy.get('input[name="password"]').type('Seller#1234')
        cy.get('button[type="submit"]').click()
        cy.url({ timeout: 15000 }).should('include', '/seller-dashboard')
        cy.get('body', { timeout: 15000 }).should('be.visible')
        cy.wait(1000)
    })

    it('TC-LOGIN-04 - User diarahkan ke landingpage', () => {
        cy.visit('/login')
        cy.get('input[name="email"]').type('user@gmail.com')
        cy.get('input[name="password"]').type('User#1234')
        cy.get('button[type="submit"]').click()
        cy.url({ timeout: 15000 }).should('include', '/')
        cy.get('body').should('be.visible')
        cy.wait(1000)
    })

    it('TC-LOGIN-05 - Remember me berfungsi (tetap login setelah buka browser)', () => {
        cy.visit('/login')
        cy.get('input[name="email"]').type('user@gmail.com')
        cy.get('input[name="password"]').type('User#1234')
        cy.get('input[name="remember"]').check()
        cy.get('button[type="submit"]').click()
        cy.url().should('include', '/')
        cy.wait(500)
        cy.reload()
        cy.url().should('include', '/')
        cy.wait(1000)
    })

    it('TC-LOGIN-06 - Tidak tetap login jika Remember me tidak dicentang', () => {
        cy.visit('/login')
        cy.get('input[name="email"]').type('user@gmail.com')
        cy.get('input[name="password"]').type('User#1234')
        cy.get('button[type="submit"]').click()
        cy.url().should('include', '/')
        cy.wait(500)
        cy.clearCookies()
        cy.reload()
        cy.visit('/')
        cy.visit('/profile')
        cy.url().should('include', '/login')
        cy.wait(1000)
    })

    it('TC-LOGIN-07 - Menolak email format tidak valid', () => {
        cy.visit('/login')
        cy.get('input[name="email"]').type('usergmail.com')
        cy.get('input[name="password"]').type('User#1234')
        cy.get('button[type="submit"]').click()
        cy.contains('The email field must be a valid email address.', { timeout: 8000 })
            .should('be.visible')
        cy.wait(1000)
    })

    it('TC-LOGIN-08 - Menerima email uppercase dan login berhasil', () => {
        cy.visit('/login')
        cy.get('input[name="email"]').type('USER@GMAIL.COM')
        cy.get('input[name="password"]').type('User#1234')
        cy.get('button[type="submit"]').click()
        cy.url({ timeout: 15000 }).should('include', '/')
        cy.wait(1000)
    })

})
