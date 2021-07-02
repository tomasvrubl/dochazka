import { ThrowStmt } from '@angular/compiler';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit, HostListener} from '@angular/core';
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
  sosoba: Osoba;
  sakce: Akce;
  akce: Akce[];
  resp: Response;
  popChip: Boolean = false;

  constructor(private router: Router,
    private appService: AppService,
    private route: ActivatedRoute) { 
      this.sosoba = null;
      this.resp = null;

   }


   ngOnInit() {
      this.appService.getAkce().then(resp => this.akce = resp);
      setInterval(() => {
         this.cas = new Date();
      }, 1000);
   }

   

   provedAkci(a: Akce){

      var t = this;

      if(!this.sosoba){
         this.popChip = true;
         this.sakce = a;
         return;
      }

      this.sakce = null;
      this.popChip = false;
      this.appService.zapisAkci(this.sosoba, a).then((resp:Response)  =>  {
         this.sosoba = null;   
         t.shopwPopup(resp)}
      );
   }

   @HostListener('window:rfid_ctecka', ['$event'])
   ctecka(ev:any) {

      console.log(`Ctecka: ${ev.detail}`);
     
   
      if(ev == null || ev.detail.length < 1)
         return;
      
      if(this.popChip){
         this.appService.zapisAkci2(ev.detail, this.sakce).then((resp:Response) => {
            this.popChip = false;
            this.shopwPopup(resp);       
         });
      }
      else{
         this.appService.getOsoba(ev.detail).then(resp => this.sosoba = resp);
      }

   }

   goBack(){
      this.popChip = false;
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
      t.sosoba = null;
      t.sakce = null;
      setTimeout(() => {
         t.resp = null;
      }, 800);

   }
 
}
