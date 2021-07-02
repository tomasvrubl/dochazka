
export class Osoba {
    osoba: String;
    id: number;
    personalnum:number;
    code_lo:number;
    code_hi:number;
    new_chip:String;
    old_chip:String;
}

export class Akce {
    id:number;
    lbl: string;
    io:number;
    css: string;
}

export class Response {
    code:number;
    data: any;
    msg: string;
}