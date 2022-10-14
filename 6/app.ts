const express = require('express');
const app = express();
const port = 3000;

app.get('/address/:address', (req, res) => {// address/0xA145ac099E3d2e9781C9c848249E2e6b256b030D
    res.send(req.params);
});

app.listen(port, () => {
    console.log(`Listening on port ${port}`);
    getBalance();
});


async function getBalance(address){
    const Web3 = require('web3');

    const url = 'https://mainnet.infura.io/v3/your-api-key';// TODO: change api-key
    const address1 = '0xdb65702a9b26f8a643a31a4c84b9392589e03d7c';
    const address2 = '0xA145ac099E3d2e9781C9c848249E2e6b256b030D';
    
    const tokenUSDT = '0xdAC17F958D2ee523a2206206994597C13D831ec7';
    // TODO: list of coins:
    /*
        https://api.coingecko.com/api/v3/coins/list?include_platform=true
        {
            "id": "tether",
            "symbol": "usdt",
            "name": "Tether",
            "platforms": {
                "ethereum": "0xdac17f958d2ee523a2206206994597c13d831ec7",
            }
        },
    */
    
    
    
    var web3 = new Web3(Web3.givenProvider || url);

    const ethBalance = await getEthBalance(web3, address2);
    console.log(ethBalance);
    
    const usdtBalance = await getTokenBalance(web3, address2, tokenUSDT);
    console.log(usdtBalance);
}



async function getEthBalance(web3, walletAddress) {
    const balance = await web3.eth.getBalance(walletAddress);
    // TODO: add format
    return balance;
}

async function getTokenBalance(web3, walletAddress, tokenContract) {
    const balanceOfABI = [// TODO: need to figure out the settings
        {
            "constant": true,
            "inputs": [
                {
                    "name": "_owner",
                    "type": "address"
                }
            ],
            "name": "balanceOf",
            "outputs": [
                {
                    "name": "balance",
                    "type": "uint256"
                }
            ],
            "payable": false,
            "stateMutability": "view",
            "type": "function"
        },
    ];
    const contract = new web3.eth.Contract(balanceOfABI, tokenContract)
    let balance = await contract.methods.balanceOf(walletAddress).call();
    // TODO: add format
    return balance;
}

