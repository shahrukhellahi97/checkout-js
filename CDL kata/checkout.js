class Checkout {
    constructor(priceRules){
        this.priceRules = priceRules;
        this.CurrentTotal = 0;
    }

    scan(item){

        this.CurrentTotal += this.priceRules.find(x => x.item === item).unitPrice;
    }

    total(){
        return this.CurrentTotal;
    }
}

export default Checkout;