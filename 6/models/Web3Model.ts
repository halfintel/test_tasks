import Web3 from 'web3';
import Contract from 'web3-eth-contract';
import pkg from '@mycrypto/eth-scan';
const { getTokensBalance } = pkg;
import { IncorrectWalletError } from './errors/IncorrectWalletError.js';
import { CoinsModel } from './CoinsModel.js';
import { ConfigModel } from './ConfigModel.js';


export interface tokensBalanceFilteredInterface {
    [key:string]: string
}


export class Web3Model {
    web3: any;
    Contract: any;
   
    constructor() {
        this.web3 = new Web3(ConfigModel.getWeb3Url());
        this.Contract = Contract;
        this.Contract.setProvider(ConfigModel.getWeb3Url());
    }

    async getAllBalances(walletAddress:string):Promise<tokensBalanceFilteredInterface> {
        if (!this.isAddress(walletAddress)){
            throw new IncorrectWalletError();
        }


        const {hashOfCoins, listOfCoins} = await CoinsModel.getCoins();
        const tokensBalance = await getTokensBalance(this.web3, walletAddress, listOfCoins);

        let tokensBalanceFiltered:tokensBalanceFilteredInterface = {};
        for (let i in tokensBalance){
            if (tokensBalance[i] !== 0n){
                tokensBalanceFiltered[ hashOfCoins[i] ] = await this.fixBalance(tokensBalance[i], i);
            }
        }
        tokensBalanceFiltered['eth'] = await this.getEthBalance(walletAddress);

        return tokensBalanceFiltered;
    }

    private isAddress(walletAddress:string):boolean {
        return this.web3.utils.isAddress(walletAddress);
    }

    private async getEthBalance(walletAddress:string):Promise<string> {
        const balance = await this.web3.eth.getBalance(walletAddress);
        return this.web3.utils.fromWei(balance);
    }

    private async fixBalance(balance:bigint, token:string):Promise<string> {
        let minABI = [
            // decimals
            {
                "constant":true,
                "inputs":[],
                "name":"decimals",
                "outputs":[{"name":"","type":"uint8"}],
                "type":"function"
            }
        ];
        let contract = new this.Contract(minABI, token);
        let decimals = await contract.methods.decimals().call();
        let correctBalance = Number(balance) / 10**decimals;
        return correctBalance.toString();
    }
}
