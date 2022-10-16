import { Request, Response } from 'express';

export class RestView {
    res: Response;

    constructor(res:Response) {
        this.res = res;
    }

    setResponse(status: number, message:any):void {
        this.res.status(status);
        this.res.send({message: message});
    }
}
