import { Injectable }    from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams  }    from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Osoba, Akce, Response  } from '../_obj/data';


declare var AppUrlWS:any;

@Injectable()
export class AppService  {
    private url = AppUrlWS;  // URL to web api
    private httpHeaders : HttpHeaders ;

    constructor(private http:  HttpClient) {
        
        this.httpHeaders = new HttpHeaders();
    }

    getOsoby(): Observable<Osoba[]>{            
        let params = { action: 'osoby'};            
        return this.get<Osoba[]>(params);       
    }

    getAkce(): Observable<Akce[]>{            
        let params = { action: 'akce'};            
        return this.get<Akce[]>(params);       
    }
    

    zapisAkci(o: Osoba, b:Akce): Promise<Response>{
       
        let params = {action: 'zapis', oid: o.personid, udalost: b};
        return this.post<Response>(params);
    }


   get<T>(rec?: any) : Observable<T> {
            
        let params = new HttpParams();   
        
        if(rec != null){
            for(var prop in rec) {
                params = params.set(prop, rec[prop] + '');
            }
        }
        
        return this.http.get<T>(AppUrlWS, {params: params})
            .pipe(catchError(this.handleError<T>('get<T>()')));
    }

    

    post<T>(rec?: any) : Promise<T> {
       
        return this.http.post<T>(AppUrlWS,  rec,  { headers: this.httpHeaders, withCredentials: true})
            .pipe(catchError(this.handleError<T>('post<T>'))).toPromise();
    }


    private handleError<T> (operation = 'operation', result?: T) {
        return (error: any): Observable<T> => {
  
          // TODO: send the error to remote logging infrastructure
          console.error(error); // log to console instead
  
          // TODO: better job of transforming error for user consumption
          this.log(`${operation} failed: ${error.message}`);
  
          // Let the app keep running by returning an empty result.
          return of(result as T);
        };
    }

    private log(message: string) {
        console.log(`AppService: ${message}`);
    }
       
}