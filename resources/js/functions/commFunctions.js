export default function (){
  // 数字→カンマ付き数字
  const numToComma = (val) => {
    return val.toLocaleString();
  }
  // カンマ付き数字→数字
  const commaToNum = (val) => {
    return parseInt(Number(val.replace(/,/g, '')),10);
  }
  // ゼロサプレス
  const zeroPadding = (NUM, LEN)  => {
	  return ( Array(LEN).join('0') + NUM ).slice( -LEN );
  }
  //   配列→CSV
  const arrayToCsv = (val) => {
      let csv = "";
      val.forEach(element => {
          csv += "," + element;
      });
      return csv.substr(1);
  }
// 日付
  const isDate = (str, delim) =>  {
    if (str===undefined) return false;
    if (str===null) return false;
    var arr = str.split(delim);
    if (arr.length !== 3) return false;
    if (Number(arr[0]===false))  return false;
    if (Number(arr[1]===false))  return false;
    if (Number(arr[2]===false))  return false;
    if (arr[0]<1900) return false;
    if (arr[1]<1 || arr[1]>12) return false;
    if (arr[2]<1 || arr[2]>31) return false;
    if (arr[1]==4 || arr[1]==6 || arr[1]==9 || arr[1]==11  ) {
        if (arr[2] > 30) return false;
    }
    if (arr[1]==2) {
        if ((arr[0] % 4)!=0) {
            if ((arr[0] % 100)!=0) {
                if ((arr[0] % 400)==0) {
                    if (arr[2] > 29) return false;
                } else {
                    if (arr[2] > 28) return false;
                }
            } else {
                if (arr[2] > 28) return false;
            }
        } else {
            if (arr[2] > 28) return false;
        }
    }
//     const date = new Date(arr[0], arr[1], arr[2]);
// alert(date.getMonth())
//     if (Number(arr[0]) !== date.getFullYear() || arr[1] !== ('0' + (date.getMonth() + 1)).slice(-2) || arr[2] !== ('0' + date.getDate()).slice(-2)) {
//       return false;
//     } else {
    return true;
    // }
  } 
// 


const getDate = (today = new Date()) => {
    let getusyo = new Date(today.getFullYear(), today.getMonth() , 1);
    let getumatu = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    let yokugetu = new Date(today.getFullYear(), today.getMonth() + 1, 1);
    let zengetu = new Date(today.getFullYear(), today.getMonth() - 1, 2);
    let year = today.getFullYear() - 2000;
    let month = today.getMonth() + 1;
    let day = today.getDate() ;
    let nengetu = year +  '年' + month +  '月'
    let ymd = today.getFullYear() +  '-' + (Number(today.getMonth()) + 1) + '-' + today.getDate()
    let ym = today.getFullYear() +  '-' + (Number(today.getMonth()) + 1)
    let wareki = today.toLocaleDateString("ja-JP-u-ca-japanese",  {year:'numeric',month:'long',day:'numeric'});
    return {today:today,getusyo:getusyo,getumatu:getumatu,yokugetu:yokugetu,zengetu:zengetu,nengetu:nengetu,wareki:wareki,ymd:ymd,ym:ym};
    // return {today:today,getusyo:getusyo,getumatu:getumatu,nengetu:nengetu};
    // return today;
  }
  const getFormatDate = (day = new Date()) => {
    var y = day.getFullYear();
    var m = ('00' + (day.getMonth()+1)).slice(-2);
    var d = ('00' + day.getDate()).slice(-2);
    return (y + '-' + m + '-' + d);
  }
  const getFormatYM = (date) => {
// alert(date)
    const day = new Date(date)
    const year = day.getFullYear();
    const month  = day.getMonth() + 1;
    return year + '年' + month + '月'
  }
  const getFormatYMD = (date) => {
    const date1 = new Date(date)
    const year = date1.getFullYear();
    const month  = date1.getMonth() + 1;
    const day  = date1.getDate() ;
    return year + '年' + month + '月' + day + '日'
  }
  const getFormatMD = (date) => {
    const date1 = new Date(date)
    const month  = date1.getMonth() + 1;
    const day  = date1.getDate() ;
    return  month + '月' + day + '日'
  }
  const getLastDay = (date) => {
    return new Date(date.getFullYear(), date.getMonth() + 1, 0)
  }
  // ステータスフラグ操作
  // (sw=on:1,off:0,no=操作するビットの先頭から数えての位置,対象となるフラグビット文字列)
  const getStatusFlag = (flag,no) => {
    let len = flag.length;
    if (no < len) {
      return flag.slice(no-1, 1)
    } else {
      return null;
    };
  }
  const setStatusFlag = (flag,no,value) => {
    if (no <= flag.length) {
      return flag.slice(0,no-1) + value.toString() +  flag.slice(no,flag.length); 
    } else {
      return null
    }
  }
  //  消費税金額(10%)
  const calcTax =  (amount,state) => {
    let total = Number(amount);
    let tax = 0;
    switch (state) {
      case 1:
        tax = Math.round((amount * 10) / 110) ;
        break;
      case 2:
        tax = Math.round((amount * 10) / 100) ;
        total += tax;
        break;
    }
    return {tax:tax, total: total}
  };
  
  const getBusinessDay = (year,month,day,state=0) => {

    let date;
    if (day>28) {
      date = new Date(year,month-1,1);
      date.setMonth(date.getMonth() + state);
      date.setMonth(date.getMonth() + 1);
      date.setDate(date.getDate() - 1);
    } else {
      date = new Date(year,month-1,day);
      date.setMonth(date.getMonth() + state);
    }
    return date.toLocaleDateString("ja-JP", { year: "numeric",month: "2-digit", day: "2-digit", }).split("/").join("-");

  }

  return {
    numToComma,
    commaToNum,
    zeroPadding,
    arrayToCsv,
    setStatusFlag,
    getStatusFlag,
    calcTax,
    isDate,
    getDate,
    getFormatDate,
    getFormatYM,
    getFormatYMD,
    getFormatMD,
    getBusinessDay,
    getLastDay,
  };
}
