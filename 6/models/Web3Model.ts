import Web3 from 'web3';
import { IncorrectWalletError } from './errors/IncorrectWalletError.js';
import pkg from '@mycrypto/eth-scan';
const { getTokensBalance } = pkg;
import fs from 'fs';


export class Web3Model {
    web3: any;
   
    constructor() {
        const configJson = fs.readFileSync("./config.json", "utf8");// TODO: move to another class
        const config = JSON.parse(configJson);

        this.web3 = new Web3(Web3.givenProvider || config.web3Url);
    }

    async getAllBalances(walletAddress:string) {
        if (!this.isAddress(walletAddress)){
            throw new IncorrectWalletError();
        }


        const {hashOfCoins, listOfCoins} = await this.getCoins();
        


        const tokensBalance = await getTokensBalance(this.web3, walletAddress, listOfCoins);
        let tokensBalanceFiltered = {};
       
        for (let i in tokensBalance){
            if (tokensBalance[i] !== 0n){
                tokensBalanceFiltered[ hashOfCoins[i] ] = tokensBalance[i].toString();
            }
        }
        tokensBalanceFiltered['eth'] = await this.getEthBalance(walletAddress);
        console.log(tokensBalanceFiltered);// TODO: remove
        return tokensBalanceFiltered;
    }

    private async getCoins() {// TODO: move to another class and add to cache/db/file
        const coins = await fetch('https://api.coingecko.com/api/v3/coins/list?include_platform=true')
            .then((response) => response.json())
            .then((data) => data.filter(obj => obj.platforms.ethereum && obj.platforms.ethereum.length > 0));
        let hashOfCoins = {};
        let listOfCoins:string[] = [];
        for (let i in coins){
            hashOfCoins[ coins[i].platforms.ethereum ] = coins[i].symbol;
            listOfCoins.push(coins[i].platforms.ethereum);
        }
        return {'hashOfCoins': hashOfCoins, 'listOfCoins': listOfCoins};
    }

    private isAddress(walletAddress:string) {
        return this.web3.utils.isAddress(walletAddress);
    }

    private async getEthBalance(walletAddress:string) {
        const balance = await this.web3.eth.getBalance(walletAddress);
        return this.web3.utils.fromWei(balance);
    }

  }