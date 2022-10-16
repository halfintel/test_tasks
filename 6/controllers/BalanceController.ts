import { Request, Response } from 'express';
import { Web3Model } from '../models/Web3Model.js';
import { ConfigModel } from '../models/ConfigModel.js';
import { JsonFileModel } from '../models/JsonFileModel.js';
import { RestView } from '../views/RestView.js';


export class BalanceController {
    static async getBalance(req:Request, res:Response):Promise<void> {
        const restView = new RestView(res);
        try {
            const web3 = new Web3Model;
            const balance = await web3.getAllBalances(req.params.address);
            restView.setResponse(200, balance);
        } catch(e) {
            if (e.constructor.name === 'Error'){
                restView.setResponse(500, 'Something goes wrong');
            } else {// custom errors
                restView.setResponse(e.getStatus(), e.message);
            }
        }
    }

    static async setBalanceToFile():Promise<void> {
        try {
            console.log('setBalanceToFile');

            const web3 = new Web3Model;
            const tokensBalance = await web3.getAllBalances(ConfigModel.getAddress());
            await JsonFileModel.setBalances(tokensBalance);

            setTimeout(() => {
                BalanceController.setBalanceToFile();
            }, 1000);
            console.log('save success');

        } catch(e) {
            console.log(e);
        }
    }
}
