import { ThrowStmt } from '@angular/compiler';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { Params, Router, ActivatedRoute }            from '@angular/router';


import { Osoba, Akce, Response } from './_obj/data';
import { AppService } from './_service/app';

@Component({
  selector: 'app-root',
  templateUrl: './_view/home.html'
})
export class AppComponent implements OnInit {
  title = 'Docházka GIFF/PWK';
  cas = new Date();
  osoba: Osoba[];
  sosoba: Osoba;
  akce: Akce[];
  resp: Response;

  constructor(private router: Router,
    private appService: AppService,
    private route: ActivatedRoute) { 
      this.osoba = [];
      this.sosoba = null;
      this.resp = null;
   }


   ngOnInit() {

      this.appService.getOsoby().subscribe(resp => this.osoba = resp);
      this.appService.getAkce().subscribe(resp => this.akce = resp);
      setInterval(() => {
         this.cas = new Date();
      }, 1000);

   }

   goHome(){
      this.sosoba = null;
   }
   
   selOsoba(o:Osoba){
      this.sosoba = o;
   }

   provedAkci(a: Akce){

      var t = this;
      this.appService.zapisAkci(this.sosoba, a).then((resp:Response)  =>  {
         t.shopwPopup(resp)}
      );
   }


   shopwPopup(r: Response){
      this.resp = r;

      setTimeout(()=> {
         try{
            var w = -Math.round(document.getElementById("popup").offsetWidth/2);
            document.getElementById("popup").style['margin-left'] = w +'px';
         }catch(Ex){}
      }, 5);
    
      var t = this;
      setTimeout(() => {
         t.resp = null;
         t.sosoba=null;
      }, 2000);

   }
 
}
