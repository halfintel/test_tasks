import { JsonFileModel } from './JsonFileModel.js';


interface hashOfCoinsInterface {
    [key:string]: string
}
export interface CoinsInterface {
    hashOfCoins: hashOfCoinsInterface;
    listOfCoins: string[];
}
interface CoinGeckoInterface {
    platforms: {
        ethereum: string
    };
    symbol: string
}


export class CoinsModel {
    static async getCoins():Promise<CoinsInterface> {
        try {
            // can be replaced with a database or a cache
            const coinsFile = await JsonFileModel.getDataFromFile("./files/coins.json");
            const coins:CoinsInterface = JSON.parse(coinsFile);
            return coins;
        } catch(e) {
            //continue
        }

        const fetchCoins:CoinGeckoInterface[] = await fetch('https://api.coingecko.com/api/v3/coins/list?include_platform=true')
            .then((response) => response.json())
            .then((data) => data.filter((obj:CoinGeckoInterface) => obj.platforms.ethereum && obj.platforms.ethereum.length > 0));
        let hashOfCoins:hashOfCoinsInterface = {};
        let listOfCoins:string[] = [];
        for (let i in fetchCoins){
            hashOfCoins[ fetchCoins[i].platforms.ethereum ] = fetchCoins[i].symbol;
            listOfCoins.push(fetchCoins[i].platforms.ethereum);
        }
        const coins:CoinsInterface = {'hashOfCoins': hashOfCoins, 'listOfCoins': listOfCoins};
        try {
            JsonFileModel.setCoins(coins);
        } catch(e) {
            console.error(e);
        }
        return coins;
    }
}
