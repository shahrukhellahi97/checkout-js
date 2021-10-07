import Checkout from "./checkout";
describe("simple check", () =>{
    it("",() =>{
        expect(10).toBe(10);
    })
})

const priceRules = [

    {
        item: "A",
        unitPrice: 50,
        specialPriceA: {
            count: 3,
            discountValue: 20,
            NewPrice: 130 

        }
    },

    {
        item: "B",
        unitPrice: 30,
        specialPriceB: {
            count: 2,
            discountValue: 15,
            NewPrice: 45
        }
    },
    {
        item: "C",
        unitPrice: 20
    },
    {
        item: "D",
        unitPrice: 15
    }

];


describe("No products Checkout will return", () =>{
    it("0 for no products", () =>{
        const checkout = new Checkout(priceRules);
        expect(checkout.total()).toBe(0);
    })

       it("A = 50", () =>{
        const checkout = new Checkout(priceRules);
        checkout.scan("A");
        expect(checkout.total()).toBe(50);
    })

        it("B = 30", () =>{
            const checkout = new Checkout(priceRules);
            checkout.scan("B");
            expect(checkout.total()).toBe(30);
    })
        it("C = 20", () =>{
            const checkout = new Checkout(priceRules);
            checkout.scan("C");
            expect(checkout.total()).toBe(20);
    })
        it("D = 15", () =>{
            const checkout = new Checkout(priceRules);
            checkout.scan("D");
            expect(checkout.total()).toBe(15);
    })
    
        it("AB = 80", () =>{
            const checkout = new Checkout(priceRules);
            checkout.scan("A");
            checkout.scan("B");
            expect(checkout.total()).toBe(80);
        })

        it("AA = 100",() => { 
            const checkout = new Checkout(priceRules);
            checkout.scan("A");
            checkout.scan("A");
            expect(checkout.total()).toBe(100);
        })

        it("AAA = 130",() => {
            const checkout = new Checkout(priceRules);
            checkout.scan("A");
            checkout.scan("A");
            checkout.scan("A");
            expect(checkout.total()).toBe(130);
        })


})