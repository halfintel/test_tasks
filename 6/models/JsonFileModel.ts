import fs from 'fs';
import { tokensBalanceFilteredInterface } from './Web3Model.js';
import { CoinsInterface } from './CoinsModel.js';
import { IncorrectSaveFileError } from './errors/IncorrectSaveFileError.js';


export class JsonFileModel {
    static async getDataFromFile(path:string):Promise<string> {
        return new Promise((resolve, reject) => {
            fs.readFile(path, "utf8", (err, data) => {
                if (err){
                    reject(err);
                } else {
                    resolve(data);
                }
            });
        });
    }

    static async setBalances(tokensBalance:tokensBalanceFilteredInterface):Promise<void | IncorrectSaveFileError> {
        return new Promise((resolve, reject) => {
            const data = JSON.stringify({
                'date': new Date().toISOString(),
                'balances': tokensBalance
            });
            const dir = './files';
            const fileName = dir + '/balances.json';

            resolve(JsonFileModel.setDataToFile(data, dir, fileName));
        });
    }

    static async setCoins(coins:CoinsInterface):Promise<void | IncorrectSaveFileError> {
        return new Promise((resolve, reject) => {
            const data = JSON.stringify(coins);
            const dir:string = './files';
            const fileName:string = dir + '/coins.json';
            resolve(JsonFileModel.setDataToFile(data, dir, fileName));
        });
    }

    private static async setDataToFile(data:string, dir:string, fileName:string):Promise<void | IncorrectSaveFileError> {
        return new Promise((resolve, reject) => {
            if (!fs.existsSync(dir)){
                fs.mkdirSync(dir);
            }
            fs.writeFile(fileName, data, (err) => {
                if (err){
                    console.error(err);
                    reject(new IncorrectSaveFileError());
                } else {
                    resolve();
                }
            });
        });
    }
}
