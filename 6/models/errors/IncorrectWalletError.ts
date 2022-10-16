

export class IncorrectWalletError extends Error {  
    status: number;

    constructor () {
        const message = 'Incorrect wallet';
        super(message);
        Error.captureStackTrace(this, this.constructor);
    
        this.name = this.constructor.name
        this.status = 400;
    }
  
    getStatus():number {
        return this.status;
    }
}
  