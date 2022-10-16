

export class IncorrectSaveFileError extends Error {  
    status: number;

    constructor () {
        const message = 'Can\'n save file';
        super(message);
        Error.captureStackTrace(this, this.constructor);
    
        this.name = this.constructor.name
        this.status = 500;
    }
  
    getStatus():number {
        return this.status;
    }
}
  