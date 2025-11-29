describe('Login Testing', () => {

    // USER LOGIN
    it('Login berhasil dengan kredensial valid', () => {
        cy.visit('/login')

        cy.get('input[name="email"]', { timeout: 8000 }).type('user@gmail.com')
        cy.get('input[name="password"]', { timeout: 8000 }).type('User#1234')
        cy.get('button[type="submit"]').should('be.visible').click()

        cy.url({ timeout: 10000 }).should('include', '/')

        cy.get('body').should('be.visible')
        cy.wait(2000)
    })

    it('Login gagal dengan password salah', () => {
        cy.visit('/login')

        cy.get('input[name="email"]', { timeout: 8000 }).type('user@gmail.com')
        cy.get('input[name="password"]', { timeout: 8000 }).type('SalahBanget!')
        cy.get('button[type="submit"]').should('be.visible').click()

        cy.contains('Email atau password salah.', { timeout: 8000 }).should('be.visible')

        cy.wait(2000)
    })

    it('Validasi input kosong', () => {
        cy.visit('/login')

        cy.get('button[type="submit"]').should('be.visible').click()
        cy.contains('The email field is required.', { timeout: 8000 }).should('exist')
        cy.contains('The password field is required.', { timeout: 8000 }).should('exist')

        cy.wait(2000)
    })

    // ADMIN LOGIN
    it('Login admin redirect ke dashboard admin', () => {
        cy.visit('/admin-dashboard/login')

        cy.get('#data\\.email', { timeout: 8000 }).type('admin@gmail.com')
        cy.get('#data\\.password', { timeout: 8000 }).type('Admin#1234')
        cy.get('button[type="submit"]').should('be.visible').click()

        cy.url({ timeout: 10000 }).should('include', '/admin-dashboard')

        cy.get('.fi-sidebar', { timeout: 15000 }).should('be.visible')
        cy.wait(2000)
    })

    // SELLER LOGIN
    it('Login seller redirect ke dashboard seller', () => {
        cy.visit('/seller-dashboard/login')

        cy.get('#data\\.email', { timeout: 8000 }).type('seller@gmail.com')
        cy.get('#data\\.password', { timeout: 8000 }).type('Seller#1234')
        cy.get('button[type="submit"]').should('be.visible').click()

        cy.url({ timeout: 10000 }).should('include', '/seller-dashboard')

        cy.get('.fi-sidebar', { timeout: 15000 }).should('be.visible')
        cy.wait(2000)
    })

})
