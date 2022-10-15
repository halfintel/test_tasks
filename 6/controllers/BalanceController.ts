import { Web3Model } from '../models/Web3Model.js';
import { RestView } from '../views/RestView.js';
import { Request, Response } from 'express';
import fs from 'fs';

export class BalanceController {
    static async getBalance(req:Request, res:Response) {
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

    static async setBalanceToFile() {
        try {
            console.log('setBalanceToFile');

            const configJson = fs.readFileSync("./config.json", "utf8");// TODO: move to another class
            const config = JSON.parse(configJson);

            const web3 = new Web3Model;
            const balance = await web3.getAllBalances(config.address);
            const data = JSON.stringify({
                'date': new Date().toISOString(),
                'balances': balance
            });
            const dir = './files';
            const fileName = dir + '/balances.json';
            if (!fs.existsSync(dir)){
                fs.mkdirSync(dir);
            }
            
  
            fs.writeFile(fileName, data, (err) => {
                if (err){
                    console.log('Can\'n save file', err);
                } else {
                    setTimeout(() => {
                        //BalanceController.setBalanceToFile();// TODO: uncomment
                    }, 1000);
                }
            });

        } catch(e) {
            console.log(e);
        }
    }
}