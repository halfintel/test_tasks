import fs from 'fs';
import { JsonFileModel } from './JsonFileModel.js';


interface ConfigInterface {
    web3Url: string;
    address: string;
}


export class ConfigModel {
    private static config:ConfigInterface;

    constructor() {
        const configJson = fs.readFileSync("./config.json", "utf8");
        ConfigModel.config = JSON.parse(configJson);
    }

    static getWeb3Url():string {
        return ConfigModel.config.web3Url;
    }
    static getAddress():string {
        return ConfigModel.config.address;
    }
}
