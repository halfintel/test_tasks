import { Request, Response } from 'express';
import express from 'express';
import { BalanceController } from './controllers/BalanceController.js';
import { RestView } from './views/RestView.js';

const app = express();
const port = 3000;

// Routes:
app.get('/address/:address', BalanceController.getBalance);
app.get('*', function(req:Request, res:Response){
    const restView = new RestView(res);
    restView.setResponse(404, 'Incorrect URL');
});


app.listen(port, () => {
    console.log(`Listening on port ${port}`);
    BalanceController.setBalanceToFile();
});
