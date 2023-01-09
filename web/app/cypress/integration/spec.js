/// <reference types="cypress" />

describe('Тестирование загрузки страницы', () => {
    it('has header links', () => {

        // Перебор страниц для проверки на наличие ошибки в консоле
        let pages = [
            '/'
        ]

        pages.forEach((page) => {
            cy.visit(page)
        })

    })
})
