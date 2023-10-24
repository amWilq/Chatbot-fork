import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';
import { CustomMatCardComponent } from './custom-mat-card.component';


@NgModule({
  imports: [ CommonModule, FormsModule, IonicModule],
  declarations: [CustomMatCardComponent],
  exports: [CustomMatCardComponent]
})
export class CustomMatCardComponentModule {}
