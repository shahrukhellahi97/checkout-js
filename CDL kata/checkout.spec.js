import Checkout from "./checkout";
describe("simple check", () =>{
    it("",() =>{
        expect(10).toBe(10);
    })
})

const priceRules = [

    {


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
})