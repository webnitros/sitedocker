Cypress.on('window:before:load', (win) => {
    console.log('Консоль')
    console.log(win.console)
    cy.spy(win.console, 'error');
    cy.spy(win.console, 'warn');
});

afterEach(() => {
    cy.window().then((win) => {
        expect(win.console.error).to.have.callCount(0);
        expect(win.console.warn).to.have.callCount(0);
    });
});
