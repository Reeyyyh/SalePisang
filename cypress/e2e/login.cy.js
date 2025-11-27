describe('Login Testing', () => {

    // =============================
    // USER LOGIN
    // =============================
    it('Login berhasil dengan kredensial valid', () => {
        cy.visit('/login')

        cy.get('input[name="email"]').type('user@gmail.com')
        cy.wait(700)
        cy.get('input[name="password"]').type('User#1234')
        cy.wait(700)
        cy.get('button[type="submit"]').click()

        cy.url().should('include', '/')
        cy.wait(1000)
    })

    it('Login gagal dengan password salah', () => {
        cy.visit('/login')

        cy.get('input[name="email"]').type('user@gmail.com')
        cy.wait(700)
        cy.get('input[name="password"]').type('SalahBanget!')
        cy.wait(700)
        cy.get('button[type="submit"]').click()

        cy.contains('Email atau password salah').should('exist')
        cy.wait(1000)
    })

    it('Validasi input kosong', () => {
        cy.visit('/login')

        cy.get('button[type="submit"]').click()

        cy.get('input[name="email"]:invalid').should('exist')
        cy.get('input[name="password"]:invalid').should('exist')
        cy.wait(1000)
    })

    // =============================
    // ADMIN LOGIN
    // =============================
    it('Login admin redirect ke dashboard admin', () => {
        cy.visit('/admin-dashboard/login')

        cy.get('#data\\.email').type('admin@gmail.com')
        cy.wait(700)
        cy.get('#data\\.password').type('User#1234')
        cy.wait(700)
        cy.get('button[type="submit"]').click()

        cy.url().should('include', '/admin-dashboard')
        cy.wait(1000)
    })

    // =============================
    // SELLER LOGIN
    // =============================
    it('Login seller redirect ke dashboard seller', () => {
        cy.visit('/seller-dashboard/login')

        cy.get('#data\\.email').type('seller@gmail.com')
        cy.wait(700)
        cy.get('#data\\.password').type('User#1234')
        cy.wait(700)
        cy.get('button[type="submit"]').click()

        cy.url().should('include', '/seller-dashboard')
        cy.wait(5000)
    })

})
