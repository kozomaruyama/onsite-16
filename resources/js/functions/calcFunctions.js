export default function (){
    //  消費税金額
    const calcTax = (amount,state) => {
        if (amount>0) {
            switch (state) {
                case 1:
                    return Math.round((amount * 10) / 110) ;
                case 2:
                    return  Math.round((amount * 10) / 100) ;
                default:
                    return  0;
            }
        } else {
            return 0;
        }
    };
    //  消費税を反映させた金額
    const calcTotal =  (amount,state) => {
        if (state == 2) {
            return amount + calcTax(amount,state);
        } else {
            return amount;
        }
    };
    return {
        calcTax,
        calcTotal,
    };
}
